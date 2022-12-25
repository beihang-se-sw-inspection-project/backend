<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends AbstractAPIModel
{
    use HasFactory;

    protected $fillable = [
        'assignee',
        'project_id',
        'status',
        'deadline',
        'priority',
        'title',
        'task_detail',
    ];

    public function type()
    {
        return 'tasks';
    }
}
