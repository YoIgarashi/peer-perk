<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use App\Models\SlackUser;
use App\Models\User;

class SlackController extends Controller
{
    /**
     * @var string $token Slackのトークン
     */
    private $token;

    /**
     * SlackController constructor.
     */
    public function __construct()
    {
        $this->token = env('SLACK_TOKEN');
    }

    /**
     * Slackのユーザー情報を取得してDBに登録する
     * @param Request $request
     * @return void
     */
    public function createUsers(Request $request)
    {
        $client = new Client();
        $retryAttempts = 10; // 再試行回数
        $retryDelay = 1; // 待機時間（秒）
        $response = null;
        $attempts = 0;

        do {
            try {
                $response = $client->request('GET', 'https://slack.com/api/users.list', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->token,
                        'Content-type' => 'application/json'
                    ]
                ]);
                $users = json_decode($response->getBody())->members;
                foreach ($users as $user) {
                    if (isset($user->profile->email) && !SlackUser::where('email', $user->profile->email)->exists()) {
                        DB::table('slack_users')->insert([
                            [
                                'name' => $user->profile->real_name,
                                'display_name' => $user->profile->display_name,
                                'email' => $user->profile->email,
                                'icon' => $user->profile->image_512,
                                'slackID' => $user->id,
                                'department_name' => $user->profile->title,
                                'created_at' => now(),
                            ]
                        ]);
                    }
                }
                SlackUser::wherenotIn('slackID', array_column($users, 'id'))->delete();
            } catch (RequestException $e) {
                if ($e->getResponse()->getStatusCode() == 429) { // レート制限エラー
                    if ($attempts >= $retryAttempts) {
                        // 再試行回数を超えた場合はエラーを投げる
                        throw $e;
                    }
                    // 待機時間を設けてから再試行する
                    sleep($retryDelay);
                    $attempts++;
                } else {
                    // その他のエラーは例外を投げる
                    throw $e;
                }
            }
        } while ($response === null);

        return Redirect::route('admin.users.index');
    }

    /**
     * Slackに通知を送信する
     * @param Request $request
     * @param array $payload 送信する通知のペイロード
     * @return void
     */
    public function sendNotification(Request $request)
    {
        // 送信する通知のペイロードを作成
        // https://api.slack.com/methods/chat.postMessage
        /**
         * @var array $payload 送信する通知のペイロード
         * @var string $payload['channel'] チャンネル名
         * @var string $payload['text'] 送信するテキスト
         */
        $payload = [
            'channel' => 'U0572LXKNLA',
            'text' => '<@U056W35F71C> さんがあなたの本を借りました.',
        ];

        // Slack APIにPOSTリクエストを送信
        $response = Http::withToken($this->token)
            ->post('https://hooks.slack.com/api/chat.postMessage', $payload);

        // 実行する
        $response->throw();
    }

    /**
     * Slackにチャンネルを作成する
     * @param Request $request
     * @param string $channel_name 作成するチャンネル名
     * @param string $invite_users 招待するユーザーのSlackID
     * @return void
     */
    public function createChannel($event_title, $is_private)
    {
        $user = Auth::user();

        if (empty($user)) {
            $create_user = "";
            $channel_name = $event_title;
        } else {
            $create_user = User::where('id', $user->id)->pluck('slackID')->join(', ');
            $channel_name = 'peerevent-' . $event_title;
        }

        $admin_users = User::where('is_admin', 1)->pluck('slackID')->join(', ');
        $invite_users = $create_user . ', ' . $admin_users;

        $valid_channel_name = strtolower(str_replace([' ', '.'], '', $channel_name));

        $channel_data = [
            'name' => $valid_channel_name,
            'is_private' => $is_private,
        ];

        $response = Http::withToken($this->token)
            ->post('https://slack.com/api/conversations.create', $channel_data);

        if ($response->json()['ok']) {
            $channel_id = $response->json()['channel']['id'];
            $this->inviteUsersToChannel($channel_id, $invite_users);
        } else {
            return Redirect::route('events.index')->with(['flush.message' => 'なんらかのエラーが発生してイベントを作成できませんでした。', 'flush.alert_type' => 'error']);
        }

        return $channel_id;
    }

    /**
     * プライベートチャンネルを検索する
     * @param string $channel_name 取得するチャンネル名
     * @return string|null チャンネルID
     * @return null チャンネルが見つからなかった場合
     */
    public function searchChannelId($channel_name)
    {
        $response = Http::withToken($this->token)
            ->get('https://slack.com/api/conversations.list', [
                'types' => 'private_channel',
            ]);

        $channels = $response['channels'];

        foreach ($channels as $channel) {
            if ($channel['name'] === $channel_name) {
                return $channel['id'];
            }
        }

        return Redirect::route('admin.users.index')->with(['flush.message' => 'なんらかのエラーが発生して処理を行えませんでした。', 'flush.alert_type' => 'error']);
    }

    /**
     * Slackにチャンネルにユーザーを招待する
     * @param string $channel_id 招待するチャンネルID
     * @param string $invite_users 招待するユーザーのSlackID
     * @return void
     */
    public function inviteUsersToChannel($channel_id, $invite_users)
    {
        $invite_data = [
            'channel' => $channel_id,
            'users' => $invite_users,
        ];

        $response = Http::withToken($this->token)
            ->post('https://slack.com/api/conversations.invite', $invite_data);

        if ($response->json()['ok']) {
            return;
        } else {
            return Redirect::route('admin.users.index')->with(['flush.message' => 'なんらかのエラーが発生して処理を行えませんでした。', 'flush.alert_type' => 'error']);
        }
    }

    /**
     * Slackチャンネルからユーザーを削除する
     * @param string $channel_id 削除するチャンネルID
     * @param string $delete_user 削除するユーザーのSlackID
     * @return void
     */
    public function removeUserFromChannel($channel_id, $delete_user)
    {
        $delete_data = [
            'channel' => $channel_id,
            'user' => $delete_user,
        ];

        $response = Http::withToken($this->token)
            ->post('https://slack.com/api/conversations.kick', $delete_data);

        if ($response->json()['ok']) {
            return;
        } else {
            return Redirect::route('admin.users.index')->with(['flush.message' => 'なんらかのエラーが発生して処理を行えませんでした。', 'flush.alert_type' => 'error']);
        }
    }
}
