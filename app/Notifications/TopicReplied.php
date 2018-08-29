<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Reply;

class TopicReplied extends Notification implements ShouldQueue
{
    use Queueable;

    public $reply;

    public function __construct(Reply $reply)
    {
        // 注入回复实体，方便 toDatabase 方法中的使用
        $this->reply = $reply;
    }

    public function via($notifiable)
    {
        // 开启通知的频道
        return ['database', 'mail'];
    }

    /**
     * 数据库通知
     */
    public function toDatabase($notifiable)
    {
        $topic = $this->reply->topic;
        $link =  $topic->link(['#reply' . $this->reply->id]);

        // 存入数据库里的数据
        return [
            'reply_id' => $this->reply->id,
            'reply_content' => $this->reply->content,
            'user_id' => $this->reply->user->id,
            'user_name' => $this->reply->user->name,
            'user_avatar' => $this->reply->user->avatar,
            'topic_link' => $link,
            'topic_id' => $topic->id,
            'topic_title' => $topic->title,
        ];
    }

    /**
     * 邮件通知
     */
    public function toMail()
    {   
        $topic = $this->reply->topic;

        $link =  $topic->link(['#reply' . $this->reply->id]);
        
        return (new MailMessage)
            ->subject('有新回复')
            ->line('你发布的话题《' . $topic->title . '》有新回复')
            ->action('点击查看', $link);
    }
}