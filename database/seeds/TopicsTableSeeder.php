<?php

use Illuminate\Database\Seeder;
// 引用模型
use App\Models\Topic;
use App\Models\User;
use App\Models\Category;

class TopicsTableSeeder extends Seeder
{
    public function run()
    {
        // 获取所有的 user.id 和 category.id
        $userIds = User::all()->pluck('id')->toArray();
        $cateIds = Category::all()->pluck('id')->toArray();

        // 实例化 Faker
        $faker = app(Faker\Generator::class);

        // 创建数据
        $topics = factory(Topic::class)
            ->times(50)
            ->make()
            ->each(function($topic, $index) use ($userIds, $cateIds, $faker) { //用 each 遍历 make 出来的数据
                // 挨个给每条数据赋上随机的 user_id 和 category_id
                $topic->user_id = $faker->randomElement($userIds);
                $topic->category_id = $faker->randomElement($cateIds);
            });
        
        // 插入数据
        Topic::insert($topics->toArray());
    }
}