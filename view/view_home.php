<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Accueil</title>
    <base href="<?= $web_root ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/home.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<div class="header">
        <!-- Menu horizontal avec le logo à gauche -->
        <div class="menu">
            <!-- Chemin relatif vers le dossier "ressources/images" -->
            <div class="logo"><img src="ressources\images\logo_polytech.png"></div>
            <ul>
                <li><a href="#">Accueil</a></li>
                <li class="profile-icon"><?= strtoupper(substr($user->Nom_secretaire, 0, 1) . substr($user->Prenom_secretaire, 0, 1)) ?></a></li>
            </ul>
        </div>
    </div>

    <div class="main">
        <!-- Texte de bienvenue -->
        <p>Bonjour <?= $user->Prenom_secretaire ?>! Bienvenue dans votre gestionnaire d’émargement.</p>

        <!-- Phrase et choix de promotion -->
        <p>Veuillez choisir une promotion :</p>
        <form method="post" action="home/choose_promotion">
            <button type="submit" name="promotion" value="3A">3A</button>
            <button type="submit" name="promotion" value="4A">4A</button>
            <button type="submit" name="promotion" value="5A">5A</button>
        </form>
    </div>
</body>
</html>
