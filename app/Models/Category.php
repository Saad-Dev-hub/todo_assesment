<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color'];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
