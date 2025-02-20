<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword as PasswordResetTrait;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Notifications\VerifyEmail; 
use App\Models\AnnonceModel; 
use Illuminate\Foundation\Auth\User as Authenticatable;

class UtilisateurModel extends Authenticatable implements CanResetPassword, MustVerifyEmail
{
    use HasFactory , HasApiTokens, Notifiable , PasswordResetTrait;
    protected $primaryKey = 'IdUt'; // Clé primaire personnalisée
    public $incrementing = true; // Clé primaire auto-incrémentée
    protected $keyType = 'int'; // Type entier pour la clé primaire
    protected $table = 'utilisateur'; 
    protected $fillable = ['IdUt','nom', 'prenom','email', 'password','role'];
    protected $hidden = ['password', 'remember_token'];
    // protected $cast = [
    //     'email_verified_at' => 'datetime',
    //     'password' => 'hashed',];
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
    /**
     * Envoie la notification de vérification d'email
     * Utilise la notification par défaut `VerifyEmail`
     */

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification);
    }

    // Définissez la relation entre un utilisateur et ses favoris
    public function favoris()
    {
        return $this->hasMany(FavorisModel::class, 'IdUt'); // Assurez-vous que la table 'favoris' est liée à 'user_id'
        //La méthode favoris() déclare une relation "un-à-plusieurs" entre l'utilisateur et les favoris. Cela signifie qu'un utilisateur peut avoir plusieurs favoris.

    }
    // public function annonces()
    // {
    //     return $this->hasMany(AnnonceModel::class, 'IdUt', 'id'); // Vérifiez que 'IdUt' est bien la clé étrangère
    // }
    public function annonces()
    {
        return $this->hasMany(AnnonceModel::class, 'IdUt'); // Vérifiez que 'IdUt' est bien la clé étrangère
    }
    
}
