<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{
    /**
     * 登陆认证
     */
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['show', ],
        ]);
    }

    /**
     * 显示个人信息
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * 编辑个人信息的表单视图
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);

        return view('users.edit', compact('user'));
    }

    /**
     * 更新个人信息
     */
    public function update(UserRequest $request, ImageUploadHandler $uploader, User $user)
    {   
        $this->authorize('update', $user);

        // 获取表单数据
        $data = $request->all();
        // 如果是 ajax 上传，会多传一个 file 进来，释放它
        unset($data['file']);

        /*
            // 如果用 ImageUploadHandler 上传（非异步）
            if($request->avatar) {
                $res = $uploader->save($request->avatar, 'avatars', $user->id, 362);
                if($res) {
                    $data['avatar'] = $res['path'];
                }
            } 
        */
       

        // 更新数据
        $user->update($data);

        // 发送提示消息
        session()->flash('success', '编辑个人资料成功');

        // 重定向到个人信息页
        return redirect()->route('users.show', $user);
    }
}
