<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends AbstractAPIModel
{
    use HasFactory;

    protected $fillable = [
        'report',
        'status',
    ];

    public function type()
    {
        return 'tasks';
    }
}
