<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    protected $signature = 'test:email';
    protected $description = 'Tester l\'envoi d\'un e-mail';

    public function handle()
    {
        Mail::raw('Test d\'envoi d\'email', function ($message) {
            $message->to('votre-adresse-destinataire@example.com')
                    ->subject('Test Laravel Mail');
        });

        $this->info('E-mail envoyé avec succès !');
    }
}
