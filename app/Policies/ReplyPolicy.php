<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{
    public function update(User $user, Reply $reply)
    {
        // return $reply->user_id == $user->id;
        return true;
    }

    public function destroy(User $user, Reply $reply)
    {   
        // 只有发送回复的以及帖主可以删帖。
        //（调用之前在 User 模型中定义的 isAuthorOf() 方法判定外键表的 user_id 字段是不是等于当前用户的 id）
        return $user->isAuthorOf($reply) || $user->isAuthorOf($reply->topic);
    }
}
