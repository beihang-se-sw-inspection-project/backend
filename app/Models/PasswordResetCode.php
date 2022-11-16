<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Mail\PasswordResetCodeSent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class PasswordResetCode extends AbstractAPIModel
{
    use HasFactory;

    protected $fillable = [
        'email',
    ];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {

            $id = 0;

            do{
                $id = mt_rand(100000, 999999);
    
                $rules = ['id' => 'unique:password_reset_codes'];
            
                $validate = Validator::make(['id'=>$id], $rules)->passes();
  
            }while(!$validate);

            $model->id = $id;
        });
       
        static::created(function ($model) {

            // Send email to user
            Mail::to($model->email)->send(new PasswordResetCodeSent($model->id));

            $model->id = 'xxx'; //don't send Id back to user (used as reset code)

        });
    }

    public function type()
    {
        return 'passwordresetcode';
    }

}
