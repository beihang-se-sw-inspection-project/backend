<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inspection extends AbstractAPIModel
{
    use HasFactory;

    protected $fillable = [       
        'task_id',
        'report',
        'status',
    ];

    public function type()
    {
        return 'inspections';
    }
}
