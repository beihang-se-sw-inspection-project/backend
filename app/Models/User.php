<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    use HasFactory;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'avatar'
    ];

    protected $hidden = [
        'password', 'remember_token', 'email_verified_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $attributes = [
        'role' => 'user',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
        
        static::created(function ($model) {
            $model->attributes['accessToken'] = $model->createToken('Password Access Client')->accessToken;
        });

        static::updated(function ($model) {        

            if(isset($model['password'])){
                $model->attributes['accessToken'] = $model->createToken('Password Access Client')->accessToken;
            }            
        });

    }

    public function type()
    {
        return 'users';
    }

    public function allowedAttributes(){
        return collect($this->attributes)->filter(function($item, $key){
            return !collect($this->hidden)->contains($key) && $key !== 'id';
        })->merge([
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ]);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
