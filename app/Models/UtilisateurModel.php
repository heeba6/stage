<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UtilisateurModel extends Model
{
    use HasFactory;
    protected $primaryKey = 'IdUt'; // Clé primaire personnalisée
    public $incrementing = true; // Clé primaire auto-incrémentée
    protected $keyType = 'int'; // Type entier pour la clé primaire

    protected $table = 'utilisateur'; 

    protected $fillable = ['IdUt', 'nom', 'prenom','email', 'mtP','role'];
    public function getFullNameAttribute()
    {
        return $this->nom . ' ' . $this->prenom;
    }
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }
    public function scopeIsAdmin($query)
    {
        return $query->where('role', 'admin');
    }
    
}
