<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Eloquent\Model; //这里引用下框架提供的模型
// 这里是 laravel_permission 扩展提供的两个模型文件
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SeedRolesAndPermissionsData extends Migration
{
    public function up()
    {
        // 清除缓存
        app()['cache']->forget('spatie.permission.cache');

        // 创建权限 Persisson::create(['name' => '权限名'])
        Permission::create(['name' => 'manage_contents']);
        Permission::create(['name' => 'manage_users']);
        Permission::create(['name' => 'edit_settings']);

        // 创建角色 Role::create(['name' => '角色名'])
        $founder = Role::create(['name' => 'Founder']); //站长
        // 赋予权限 $角色->givePermessionTo('权限名')
        $founder->givePermissionTo('manage_contents');
        $founder->givePermissionTo('manage_users');
        $founder->givePermissionTo('edit_settings');

        // 创建管理员角色
        $maintainer = Role::create(['name' => 'Maintainer']);
        $maintainer->givePermissionTo('manage_contents');
    }

    public function down()
    {
        // 清除缓存
        app()['cache']->forget('spatie.permission.cache');

        // 清空所有数据表数据
        $tableNames = config('permission.table_names'); //这一步应该是读取数据表名前缀配置项
        Model::unguard(); //记得解除模型保护
        DB::table($tableNames['role_has_permissions'])->delete(); // 扩展包的数据表名都叫做 $前缀['数据表名']
        DB::table($tableNames['model_has_roles'])->delete();
        DB::table($tableNames['model_has_permissions'])->delete();
        DB::table($tableNames['roles'])->delete();
        DB::table($tableNames['permissions'])->delete();
        Model::reguard(); //最后重新开启模型保护
    }
}