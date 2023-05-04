<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Todo;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'task_name',
        'todo_id'
    ];

    public function todo()
    {
        return $this->belongsTo(Todo::class);
    }
}
