<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Login</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/login.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title">Connexion</div>
        
        <div class="main">
            <form id="LoginForm" action="main/login" method="post">
                <table>
                    <tr>
                        <td>Identifiant:</td>
                        <td><input id="Identifiant" name="Identifiant" type="text" size="25" value="<?= $Identifiant ?>"></td>
                    </tr>
                    
                    <tr>
                        <td>Mot de passe:</td>
                        <td><input id="Mot_de_passe" name="Mot_de_passe" type="password" size="16" value="<?= $Mot_de_passe ?>"></td>
                    </tr>
                    
                </table>
                <input type="submit" value="Se connecter">
                
            </form>
            <?php if (count($errors) != 0): ?>
                <div class='errors'>
                    <br><br><p>Veuillez corriger les erreurs suivantes :</p>
                    <ul>
                        <?php foreach ($errors as $errors): ?>
                            <li><?= $errors ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </body>
</html>