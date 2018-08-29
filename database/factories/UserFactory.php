<?php

use Faker\Generator as Faker;
use Carbon\Carbon; //引用 Carbon

$factory->define(App\Models\User::class, function (Faker $faker) {
    static $password;
    $now = Carbon::now()->toDateTimeString(); //用Carbon 生成当前时间戳

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'introduction' => $faker->sentence(),
        //配置创建、更新时间
        'created_at' => $now, 
        'updated_at' => $now,
        //这里我自己写的，设置默认头像
        'avatar' => 'default.jpg',
    ];
});