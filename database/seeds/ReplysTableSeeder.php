<?php

use Illuminate\Database\Seeder;
use App\Models\Reply;
use App\Models\User;
use App\Models\Topic;

class ReplysTableSeeder extends Seeder
{
    public function run()
    {
        // 获取两组外键 id
        $userIds = User::all()->pluck('id')->toArray();
        $topicIds = Topic::all()->pluck('id')->toArray();

        // 实例化 faker
        $faker = app(Faker\Generator::class);

        // 生成假数据
        $replies = factory(Reply::class)
            ->times(1000)
            ->make()
            ->each(function($reply, $index) use ($userIds, $topicIds, $faker) {
                // 随机填充 user.id 和 topic.id
                $reply->user_id = $faker->randomElement($userIds);
                $reply->topic_id = $faker->randomElement($topicIds);
            });

        // 插入数据库
        Reply::insert($replies->toArray());
    }
}

