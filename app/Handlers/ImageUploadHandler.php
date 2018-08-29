<?php

namespace App\Handlers;
use Image;

class ImageUploadHandler
{
    // 合法文件后缀
    protected $allowed_ext = ["png", "jpg", "gif", 'jpeg'];

    /**
     * 图片上传
     */
    public function save($file, $folder, $file_prefix, $max_width = false) //参数 (文件对象, 文件夹名称, 文件前缀名用上传图片的用户的id, 图片最大宽度，如果传参就将进行裁剪)
    {
        // 文件夹切割能让查找效率更高： ../uploads/images/文件夹名称/年月/日/
        $folder_name = "uploads/images/$folder/" . date("Ym/d", time());

        // 文件具体存储的物理路径： public_path() = 框架根/public
        $upload_path = public_path() . '/' . $folder_name;

        // 获取文件后缀名： $file->getClientOriginalExtension() 获取文件的后缀名，如果没有默认为 png
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // 拼出文件名 用户id_当前时间戳_随机10个字符.后缀名
        $filename = $file_prefix . '_' . time() . '_' . str_random(10) . '.' . $extension;

        // in_array(变量, 数组)： 看变量是否存在于数组中，即判断后缀名是否合法
        if (!in_array($extension, $this->allowed_ext)) {
            return false; //不合法就直接返回 false 
        }

        // 将图片移动到我们的目标存储路径中 move(移动到的目录, 新的文件名)
        $file->move($upload_path, $filename);

        // 如果限制了图片宽度，就进行裁剪
        if ($max_width && $extension != 'gif') {

            // 此类中封装的函数，用于裁剪图片
            $this->reduceSize($upload_path . '/' . $filename, $max_width);
        }

        // 将图片地址最终返回 config('app.url') 即我们在 .env 里面配置的 APP_URL，这里因为 public/ 是入口文件所在地址，所以需要省略 public/ 
        return [
            'path' => config('app.url') . "/$folder_name/$filename",
        ];
    }

    /**
     * 图片裁剪
     */
    public function reduceSize($file_path, $max_width)
    {
        // 先实例化，传参是文件的磁盘物理路径
        $image = Image::make($file_path);

        // 进行大小调整的操作
        $image->resize($max_width, null, function ($constraint) {

            // 设定宽度是 $max_width，高度等比例双方缩放
            $constraint->aspectRatio();

            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });

        // 对图片修改后进行保存
        $image->save();
    }
}