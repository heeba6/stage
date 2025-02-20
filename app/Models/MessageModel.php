<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\AnnonceModel;
class MessageModel extends Model
{
    protected $table = 'message';

    protected $fillable = ['id','sujet', 'nom', 'telephone', 'email', 'contenu', 'annonce_id'];

    // Si la table ne contient pas de colonnes 'created_at' et 'updated_at'
    public $timestamps = false;

    protected $casts = [
        'dateMsg' => 'datetime',
    ];

    // Relation avec l'annonce
    public function annonce()
    {
        return $this->belongsTo(AnnonceModel::class, 'annonce_id', 'id');
    }
}
