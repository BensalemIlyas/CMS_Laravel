<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Theme extends Model
{
    protected $fillable = ['name','police', 'background_color', 'couleur_sep'];

    public function sites()
    {
        return $this->hasOne(Site::class);
    }


    use HasFactory;
}
