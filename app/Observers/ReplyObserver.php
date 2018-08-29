<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        $reply->content = clean($reply->content, 'user_topic_body');
    }

    public function created(Reply $reply)
    {
        // $reply->topic->increment('reply_count', 1);

        $topic = $reply->topic; // 获取回复的帖子的实例

        $topic->increment('reply_count', 1); //这是之前的逻辑：增加帖子的回复数量

        $topic->user->notify(new TopicReplied($reply)); //发送通知(实例化 TopicReplied 通知类)
    }
}