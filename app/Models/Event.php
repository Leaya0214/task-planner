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
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStartTimeAttribute()
    {
        return $this->date . ' ' . $this->attributes['start_time'];
    }

    public function getEndTimeAttribute()
    {
        return $this->date . ' ' . $this->attributes['end_time'];
    }
}
