<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'published_at'];

    // public function comments()
    // {
    //     return $this->hasMany(Comment::class);
    // }
    use HasFactory;
}
