<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageModel extends Model
{
    protected $table = 'message';
    protected $fillable = ['IdMsg', 'contenu', 'dateMsg','expediteur_id','destinataire_id','annonce_id'];
    //Relation avec le modèle Utilisateur (expéditeur)
    public function expediteur()
    {
        return $this->belongsTo(UtilisateurModel::class, 'expediteur_id', 'IdUt');
    }
    //Relation avec le modèle Utilisateur (destinataire).
    public function destinataire()
    {
        return $this->belongsTo(UtilisateurModel::class, 'destinataire_id', 'IdUt');
    }
    public function annonce()
    {
        return $this->belongsTo(AnnonceModel::class, 'annonce_id', 'IdAn');
    }
    
    //Scope pour récupérer les messages d'une annonce spécifique.
    public function scopeParAnnonce($query, $annonceId)
    {
        return $query->where('annonce_id', $annonceId);
    }

}
