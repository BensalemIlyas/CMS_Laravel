<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['nom', 'contenu', 'statut','article_id'];

    public function article()
    {
        return $this->belongsTo(Post::class,'article_id');
    }
    use HasFactory;
}
