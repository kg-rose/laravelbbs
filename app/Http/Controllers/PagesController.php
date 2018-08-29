<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    /**
     * 跳转到首页视图
     */
    public function root()
    {
        return view('pages.root');
    }

    /**
     * 后台无权限提醒页
     */
    public function permissionDenied()
    {
        // 如果当前用户有权限访问后台，直接跳转访问
        if (config('administrator.permission')()) {
            return redirect(url(config('administrator.uri')), 302);
        }
        // 否则使用视图
        return view('pages.permission_denied');
    }
}
