<?php

namespace Database\Seeders;

use App\Models\Request;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EventSeeder extends Seeder
{
    public $slack_channels = [];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('ja_JP');
        $user_ids = User::getUserIds();
        $request_ids = Request::getRequestIds();
        for ($i = 1; $i <= 30; $i++) {
            $user_id = $faker->randomElement($user_ids);
            $date = $faker->dateTimeBetween('today', '+1 month');
            if ($i <= 10) {
                //開催済み
                $completed_at = $date;
                $deleted_at = null;
            } elseif ($i <= 20) {
                //キャンセル済み
                $completed_at = null;
                $deleted_at = now();
            } else {
                //募集中
                $completed_at = null;
                $deleted_at = null;
            }
            $events_array[] = [
                'user_id' => $user_id,
                'title' => 'イベント' . $i,
                'description' => "イベント" . $i . "の説明\nイベント" . $i . "の説明",
                'date' => $faker->randomElement([null, $date]),
                'location' => $faker->randomElement(['オンライン', $faker->address]),
                'slack_channel' => $this->channelId(),
                'completed_at' => $completed_at,
                'created_at' => now(),
                'request_id' => $faker->randomElement([null, $faker->randomElement($request_ids)]),
                'deleted_at' => $deleted_at
            ];
        }
        DB::table('events')->insert($events_array);
    }
    public function channelId()
    {
        do {
            $id = str_pad(rand(1, 99999999), 8, "0", STR_PAD_LEFT);
            $this->slack_channels[] = 'C' . $id;
        } while (in_array($id, $this->slack_channels));
        return 'C' . $id;
    }
}