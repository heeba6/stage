<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification d'email</title>
</head>
<body>
    <h1>Vérifiez votre adresse email</h1>
    <p>Bonjour,</p>
    <p>Merci de vous être inscrit sur notre plateforme. Pour activer votre compte, veuillez cliquer sur le lien ci-dessous pour vérifier votre adresse email :</p>
    <p>
        <a href="{{ url('/verify-email/' . $token) }}" style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">
            Vérifier mon email
        </a>
    </p>
    <p>Une fois votre email vérifié, vous serez redirigé vers la page de connexion pour vous connecter à votre compte.</p>
    <p>Si vous n'avez pas créé de compte, vous pouvez ignorer cet email.</p>
    <p>Cordialement,<br>L'équipe de {{ config('app.name') }}</p>
</body>
</html>