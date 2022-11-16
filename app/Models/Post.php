<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Log;

class Post extends AbstractAPIModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id'
    ];

    protected $hidden = ['users'];

    protected $appends = ['my_like_id'];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function postlikes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function getMyLikeIdAttribute(){                                            

        $like = $this->postlikes()->firstWhere('user_id', Auth::user()->id);

        if ($like) {
            return (string)$like->id;
        }

        return (string)-1;                
    }

    public function type()
    {
        return 'posts';
    }

}
