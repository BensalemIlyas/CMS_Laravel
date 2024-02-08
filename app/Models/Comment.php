<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['nom', 'contenu', 'statut'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
    use HasFactory;
}
