<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavorisModel extends Model
{
    
    protected $table = 'favoris';
    protected $fillable = ['id', 'annonce_id', 'IdUt'];
    // Relation avec l'utilisateur
    public function utilisateur()
    {
        return $this->belongsTo(UtilisateurModel::class, 'IdUt');  // Assurez-vous que 'UtilisateurModel' est correct
    }

    // Relation avec l'annonce
    public function annonce()
    {
        return $this->belongsTo(AnnonceModel::class, 'annonce_id');  // Assurez-vous que le modèle 'Annonce' existe
    }
}
