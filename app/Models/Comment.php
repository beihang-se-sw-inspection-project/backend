<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends AbstractAPIModel
{
    use HasFactory;

    protected $fillable = [
        'message',
        'user_id',
        'post_id'
    ];

    /**
     * @return string
     */
    public function type()
    {
        return 'comments';
    }

    public function users()
    {
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function posts()
    {
        return $this->post();
    }
}
