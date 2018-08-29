<?php

namespace App\Models;

class Reply extends Model
{
    protected $fillable = ['content'];

    /**
     * n:1 User
     */
    public function user()
    {  
        return $this->belongsTo(User::class);
    }

    /**
     * n:1 Topic
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
