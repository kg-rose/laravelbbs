<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

// 引用模型
use App\Models\Topic;
use App\Handlers\SlugTranslateHandler;

class TranslateSlug implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $topic; //配置等下需要用的参数

    public function __construct(Topic $topic) //构造函数 通常用来给成员属性赋值 （配置参数）
    {
        $this->topic = $topic;
    }

    public function handle()
    {
        $slug = app(SlugTranslateHandler::class)->translate($this->topic->title); //调用 Handler 请求接口翻译 slug

        // 这里必须用 \DB::table() 来读取表数据然后修改，而不能实例化模型
        \DB::table('topics')->where('id', $this->topic->id)->update(['slug' => $slug]);
    }
}