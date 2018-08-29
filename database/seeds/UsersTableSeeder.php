<?php

use Illuminate\Database\Seeder;
use App\Models\User; //引用模型

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fakeUsers = factory(User::class)->times(10)->make(); //生成10条假数据

        $insertingUsers = $fakeUsers->makeVisible(['password', 'remember_token'])->toArray(); //让隐藏字段 password 和 remember_token 可见

        User::insert($insertingUsers); //插入数据

        // 配置用户
        $user = User::find(1);
        $user->name = 'prohorry';
        $user->email = 'woshimiexiaoming@foxmail.com';
        $user->password = bcrypt('woshiceshiyonghu');
        $user->save();
        
        // 配置权限  $user->assignRole('角色')
        $user->assignRole('Founder');

        $user = User::find(2); //重新找2号用户存当前 user
        $user->assignRole('Maintainer'); //给普通管理员角色
    }
}
