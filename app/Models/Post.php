<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'content', 'published_at','image_path','user_id'];

    public function comments()
    {
        return $this->hasMany(Comment::class, 'article_id');
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    use HasFactory;
}
