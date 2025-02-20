<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UtilisateurModel;
class GenerateApiToken extends Command
{
    
    // protected $description = 'Command description';
    protected $signature = 'generate:api-token {user_id}';
    protected $description = 'Générer un jeton d\'API pour un utilisateur';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Récupérer l'ID de l'utilisateur à partir de l'argument
        $userId = $this->argument('user_id');

        // Trouver l'utilisateur par son ID (en utilisant la clé primaire personnalisée)
        $user = UtilisateurModel::find($userId);

        // Vérifier si l'utilisateur existe
        if ($user) {
            // Générer un jeton d'accès pour l'utilisateur
            $token = $user->createToken('NomDeVotreApplication')->plainTextToken;

            // Afficher le jeton généré
            $this->info("Le jeton d'accès pour l'utilisateur {$user->nom} {$user->prenom} est: {$token}");
        } else {
            // Afficher un message d'erreur si l'utilisateur n'est pas trouvé
            $this->error('Utilisateur non trouvé');
        }
        
    }
}

