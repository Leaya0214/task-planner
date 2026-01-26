<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'assigned_to',
        'status',
        'priority',
        'due_date',
    ];

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Check if the task is assigned to the given user.
     */
    public function isAssignedTo(User $user): bool
    {
        return $this->assigned_to === $user->id;
    }
}
