<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Category;
use App\Models\User;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'description', 
        'status', 
        'due_date', 
        'category_id', 
        'user_id'
    ];

    protected $casts = [
        'due_date' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'completed' => 'bg-success',
            'pending' => 'bg-warning',
            default => 'bg-secondary'
        };
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeDueDate($query, $filter)
    {
        return match($filter) {
            'overdue' => $query->where('due_date', '<', now())->where('status', '!=', 'completed'),
            'today' => $query->whereDate('due_date', now()),
            'week' => $query->whereBetween('due_date', [now(), now()->endOfWeek()]),
            'month' => $query->whereBetween('due_date', [now(), now()->endOfMonth()]),
            default => $query
        };
    }
}
