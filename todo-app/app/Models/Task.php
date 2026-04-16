<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TaskList;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'is_completed',
        'list_id',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    public function list()
    {
        return $this->belongsTo(TaskList::class, 'list_id');
    }

    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_completed', false);
    }

    public function toggleCompleted(): bool
    {
        $this->is_completed = ! $this->is_completed;
        return $this->save();
    }
}
