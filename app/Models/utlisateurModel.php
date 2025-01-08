<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class utlisateurModel extends Model
{
    protected $table = 'utlisateur'; 
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
