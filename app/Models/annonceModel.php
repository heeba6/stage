<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AnnonceModel extends Model
{
    use HasFactory;

    protected $table = 'annoncee'; // Nom de la table

    protected $fillable = ['id', 'titre', 'description','prix', 'surface_habitable','adresse','ville', 'vocation','type','datePub','etat','IdUt','photos',
    'espaceExterieur', // Nouveau champ
    'parking', // Nouveau champ
    'chauffage', // Nouveau champ
    'proximite', // Nouveau champ
];

    public function getPriceAttribute($value)
    {
        return number_format($value, 2, ',', ' ') . ' dt';
    }

    public function setPriceAttribute($value)
    {
        if (!is_numeric($value) || $value < 0) {
            throw new \InvalidArgumentException("Le prix doit être un nombre positif.");
        }
        $this->attributes['prix'] = floatval($value);
    }

    public function getTitreAttribute($value)
    {
        return ucwords($value); // Exemple : "ma maison" → "Ma Maison"
    }

    

    public function getFormattedDateAttribute()
    {
        return date('d-m-Y', strtotime($this->datePub));
    }
    public function scopeActives($query)//récupérer uniquement les annonces actives
    {
        return $query->where('etat', 1);//$query représente l'objet de requête en cours 
    }
    
    public function scopeType($query, $type)// filtre les annonces par type 
    {
        return $query->where('type', $type);
    }
    public function scopePrixEntre($query, $min, $max)//Ce scope filtre les annonces dont le prix est compris entre deux valeurs ($min et $max).
    {
        return $query->whereBetween('prix', [$min, $max]);
    }
    public function estRecente()
    {
        $datePub = strtotime($this->datePub);
        $semaineDerniere = strtotime('-7 days');
        return $datePub >= $semaineDerniere;
    }
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return asset('uploads/annonces/' . $this->photo);//pour générer une URL complète (accessible depuis le web) vers un fichier stocké dans le répertoire de ton application Laravel
            
        }
        return asset('uploads/annonces/default.png'); // Image par défaut
    }

    public function utilisateur()
    {
        return $this->belongsTo(UtilisateurModel::class, 'IdUt');
    }
//     public function utilisateur()
// {
//     return $this->belongsTo(UtilisateurModel::class, 'IdUt', 'id');
// }








}
