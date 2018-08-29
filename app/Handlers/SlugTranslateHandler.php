<?php

namespace App\Handlers;

use GuzzleHttp\Client;
use Overtrue\Pinyin\Pinyin;

class SlugTranslateHandler
{
    public function translate($text) //参数就传帖子标题
    {
        // 实例化 GuzzleHttp/Client
        $http = new Client;

        // 初始化配置信息
        $appid = config('services.baidu_translate.appid'); //读取 config/services.php 文件中的信息
        $key = config('services.baidu_translate.key');

        // 如果没有配置百度翻译，自动使用兼容的拼音方案
        if (empty($appid) || empty($key)) {
            return $this->pinyin($text);
        }

        // 根据文档，生成 sign
        // appid+q+salt+密钥 的MD5值
        $api = 'http://api.fanyi.baidu.com/api/trans/vip/translate?'; //请求接口地址
        $salt = time(); //按接口要求需要有一个 appid+查询关键字q+加盐代码+密钥 生成的 md5 认证，这里将加盐代码弄成当前时间戳
        $sign = md5($appid. $text . $salt . $key); //生成请求签名

        // 构建请求参数
        $query = http_build_query([
            "q"     =>  $text, //查询关键字
            "from"  => "zh", //过去的语言
            "to"    => "en", //要求翻译成的语言
            "appid" => $appid, //appip
            "salt"  => $salt, //加盐代码
            "sign"  => $sign, //签名
        ]); //最后生成 => 'q=$text&from=zh&to=en&appid=$appid&salg=$slat&sign=$sign'

        // 发送 HTTP Get 请求
        $response = $http->get($api.$query); // 请求地址就是 接口地址 . $query
        
        // 返回的是json，用 $response->getBody() 接收，然后用 json_decode 转成数组
        $result = json_decode($response->getBody(), true);

        /*
            获取结果，如果请求成功，dd($result) 结果如下：

            [▼
                "from" => "zh"
                "to" => "en"
                "trans_result" => array:1 [▼
                    0 => array:2 [▼
                        "src" => "XSS 安全漏洞" //请求发送过去的文字
                        "dst" => "XSS security vulnerability" //翻译后的文字（我们需要的）
                    ]
                ]
            ]
        */

        // 尝试获取获取翻译结果
        if (isset($result['trans_result'][0]['dst'])) {
            return str_slug($result['trans_result'][0]['dst']);
        } else {
            // 如果百度翻译没有结果，使用拼音作为后备计划。
            return $this->pinyin($text);
        }
    }

    public function pinyin($text)
    {
        return str_slug(app(Pinyin::class)->permalink($text));
    }
}