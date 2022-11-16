<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostLike extends AbstractAPIModel
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id'
    ];

    /**
     * @return string
     */
    public function type()
    {
        return 'postlikes';
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    public function users()
    {
        return $this->user();
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
