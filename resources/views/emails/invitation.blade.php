<!DOCTYPE html>
<html>
<head>
    <title>Invitation à rejoindre le site de généalogie</title>
</head>
<body>
    <h1>Bonjour,</h1>
    <p>{{ $invitation->inviter->name }} vous a invité(e) à rejoindre le site de généalogie.</p>
    <p>Veuillez cliquer sur le lien ci-dessous pour accepter l'invitation :</p>
    <a href="{{ route('invitations.accept', $invitation->token) }}">
        Accepter l'Invitation
    </a>
    <p>Merci d'utiliser notre plateforme pour construire votre arbre généalogique !</p>
    <br>
    <p>Cordialement,</p>
    <p>L'équipe de Généalogie</p>
</body>
</html>
