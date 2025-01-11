<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategorieModel extends Model
{
    protected $table = 'categorie';
    protected $fillable = ['IdCat', 'nomCat'];

    //Mutateur : Convertir 'nomCat' en majuscules lors de l'insertion ou de la mise à jour
    public function setNomCatAttribute($value)
    {
        $this->attributes['nomCat'] = strtoupper($value);
    }
    // Scope pour filtrer les catégories par nom
    public function scopeNom($query, $nom)
    {
        return $query->where('nomCat', 'like', "%{$nom}%");
    }


}
