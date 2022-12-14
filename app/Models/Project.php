<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends AbstractAPIModel
{
    use HasFactory;

    protected $fillable = [
        'project_name',
        'project_manager',
        'assignee',
        'date',
        'priority',
        'overview',
        'to_do'
    ];

    public function type()
    {
        return 'projects';
    }
}
