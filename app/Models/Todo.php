<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use App\Models\User;

class Todo extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'user_id'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function user_info()
    {
        return $this->belongsTo(User::class);
    }
}
