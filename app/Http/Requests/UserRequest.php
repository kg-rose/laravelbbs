<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class UserRequest extends FormRequest
{
    /**
     * 权限认证
     */
    public function authorize()
    {
        return true;
    }

    /**
     * 请求发送过来的数据验证
     */
    public function rules()
    {
        return [
            'name' => 'required|between:3,25|regex:/^[A-Za-z0-9\-\_]+$/|unique:users,name,' . Auth::id(),
            'introduction' => 'max:80',

            /*
                // 如果用 ImageUploadHandler 上传的话需要执行以下验证
                'avatar' => 'mimes:jpeg,bmp,png,gif|dimensions:min_width=200,min_height=200',
            */
        ];
    }

    /**
     * 验证不通过后的错误提示信息
     */
    public function messages()
    {
        return [
            'name.unique' => '用户名已被占用，请重新填写',
            'name.regex' => '用户名只支持英文、数字、横杠和下划线。',
            'name.between' => '用户名必须介于 3 - 25 个字符之间。',
            'name.required' => '用户名不能为空。',

            /*
                // 如果用 ImageUploadHandler 上传的话需要执行以下验证
                'avatar.mimes' =>'头像必须是 jpeg, bmp, png, gif 格式的图片',
                'avatar.dimensions' => '图片的清晰度不够，宽和高需要 200px 以上',
            */
        ];
    }
}
