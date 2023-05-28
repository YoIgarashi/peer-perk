<?php

use App\Http\Controllers\SlackController;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('display_name')->nullable();
            $table->integer('earned_point')->default(0);
            $table->string('icon');
            $table->integer('distribution_point')->default(5000);
            $table->boolean('is_admin')->default(0);
            $table->string('slackID');
            $table->foreignId('department_id')->nullable()->constrained('departments');
            $table->softDeletes();
        });

        DB::table('users')->insert([
            [
                'name' => '高梨 彩音 / Ayane Takahashi',
                'display_name' => 'あやね',
                'email' => 'manaki.endou@anti-pattern.co.jp',
                'password' => Hash::make('password'),
                'icon' => 'https://avatars.slack-edge.com/2023-05-10/5264743457040_ac27bba61b8057355862_512.jpg',
                'slackID' => 'U056W35F71C',
                'is_admin' => 0,
                'department_id' => 2,
            ],
            [
                'name' => '井戸宗達/Ido Sohtatu',
                'display_name' => 'sohtatu ido',
                'email' => 'sohtatsu.ido@keio.jp',
                'password' => Hash::make('password'),
                'icon' => 'https://secure.gravatar.com/avatar/043535e31b28f2131d9f5111526d8aa3.jpg?s=512&d=https%3A%2F%2Fa.slack-edge.com%2Fdf10d%2Fimg%2Favatars%2Fava_0009-512.png',
                'slackID' => 'U056N55T9AB',
                'is_admin' => 1,
                'department_id' => 1,
            ],
            [
                //yuta
                'name' => '本城裕大 / Yuta Honjo',
                'display_name' => '本城先生',
                'email' => 'yutahonjo@keio.jp',
                'password' => Hash::make('password'),
                'icon' => 'https://avatars.slack-edge.com/2023-05-10/5253561557201_8877a441f0599a9a63c3_512.png',
                'slackID' => 'U057SC3MRKJ',
                'is_admin' => 0,
                'department_id' => 5,
            ],
            [
                //manaki4869管理者
                'name' => '遠藤愛期 / Manaki Endo',
                'display_name' => 'まなき',
                'email' => '48690114s@gmail.com',
                'password' => Hash::make('password'),
                'icon' => 'https://avatars.slack-edge.com/2023-05-10/5264402931744_123cd38dcc55af7397e6_512.jpg',
                'slackID' => 'U0572LXKNLA',
                'is_admin' => 1,
                'department_id' => 2,
            ],
            [
                //yoshitaka
                'name' => '五十嵐佳貴 /Yoshitaka Igarashi',
                'display_name' => '15歳までまるこめ',
                'email' => 'm22y10m20.yoshikun@gmail.com',
                'password' => Hash::make('password'),
                'icon' => 'https://avatars.slack-edge.com/2023-05-23/5320682617089_b8913a0eb43d81b5acb8_512.jpg',
                'slackID' => 'U057562KC7N',
                'is_admin' => 0,
                'department_id' => null,
            ]
        ]);

        $event_controller = new SlackController;
        $event_controller->createChannel("","peerperk管理者", true);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            Schema::dropIfExists('users');
        });
    }
};
