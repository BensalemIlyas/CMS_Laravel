<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $fillable = [
        'user_id', 'menu_preferences', // autres colonnes
    ];

    protected $casts = [
        'menu_preferences' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
    use HasFactory;
}
