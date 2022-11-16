<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends AbstractAPIModel
{
    use HasFactory;

    protected $fillable = [
        'path',
        'user_id'
    ];

     /**
     * @return string
     */
    public function type()
    {
        return 'files';
    }

}
