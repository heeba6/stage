<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavorisModel extends Model
{
    protected $table = 'favoris';
    protected $fillable = ['IdFa', 'IdAn', 'IdUt'];
}
