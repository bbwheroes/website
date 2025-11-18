<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    protected $fillable = [
        'module',
        'teacher',
        'task_name',
        'slugified_task_name',
        'username',
        'user_id',
        'approved',
    ];

    protected $casts = [
        'approved' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRepoNameAttribute(): string
    {
        return "{$this->module}-{$this->teacher}-{$this->slugified_task_name}-{$this->username}";
    }
}
