<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'date',
        'start_time',
        'end_time',
        'task_id',
        'created_by',
        'description',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
