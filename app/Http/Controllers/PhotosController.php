<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotosController extends Controller
{
    public function store(Request $request)
    {
        // 判断文件
        if (!$request->hasFile('file')) {
            return response()->json(['message' => '没有上传文件'], 422);
        }
        
        // 看看文件有没有到临时空间
        if (!$request->file->isValid()) {
            return response()->json(['message' => '文件上传过程中出错了'], 422);
        }

        // 上传格式验证
        $allow = ['image/jpeg', 'image/png', 'image/gif'];
        $type = $request->file->getMimeType(); //获取准确文件后缀
        if (!in_array($type, $allow)) {
            return response()->json(['message' => '文件类型错误，只能上传图片'], 422);
        }

        // 文件大小验证
        $max_size = 1024 * 1024 * 2;
        $size = $request->file->getClientSize(); //获取文件准确大小
        if ($size > $max_size) {
            return response()->json(['message' => '文件大小不能超过2M'], 422);
        }

        $path = $request->file->store('public/images'); //存储文件
        $url = env('APP_URL') . Storage::url($path); //获取文件地址
        return response()->json(['message' => 'success', 'data' => $url], 200);
    }
}
