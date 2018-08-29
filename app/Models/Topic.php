<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = ['title', 'body', 'category_id', 'excerpt', 'slug'];

    /**
     * n:1 User,Category
     * 绑定关系后可以通过 $topic->category 读取话题分类， ...->user 读取话题发送者
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 根据字段 created_at 和 updated_at 生成排序条件
     */
    public function scopeWithOrder($query, $order) { //判断排序方法生成模型查询构造器
        switch ($order) {
            // 如果 $order == 'recent' 即排序条件为“最新发布”
            case 'recent':
                $query->recent();
                break;
            
            // 默认为 “最后回复”
            default:
                $query->recentReplied();
                break;
        }

        // 返回查询条件 orderBy('swtich 决定')->with('防止 N+1 查询')
        return $query->with('user', 'category');
    }
    public function scopeRecent($query) { //生成查询条件 orderBy('created_at', 'desc)
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeRecentReplied($query) { //生成查询条件 orderBy('updated_at', 'desc)
        return $query->orderBy('updated_at', 'desc');
    }
    
    /**
     * 生成带 slug 的路由地址
     */
    public function link($params = [])
    {
        return route('topics.show', array_merge([$this->id, $this->slug], $params));
    }

    /**
     * 1:n Reply
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}
