<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContributionRequest extends Model
{
    protected $fillable = [
        'module',
        'teacher',
        'task_name',
        'slugified_task_name',
        'collaborators',
        'github_username',
        'status',
        'admin_notes',
    ];

    protected $casts = [
        'collaborators' => 'array',
    ];

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeDeclined($query)
    {
        return $query->where('status', 'declined');
    }
}
