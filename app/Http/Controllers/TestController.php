<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

class TestController extends Controller
{
    public function sendTestEmail()
    {
        Mail::raw('Test d\'envoi d\'email', function ($message) {
            $message->to('hibaakid6@gmail.com')
                    ->subject('Test Laravel Mail');
        });

        return response()->json(['message' => 'E-mail envoyé avec succès !'], 200);
    }
}
