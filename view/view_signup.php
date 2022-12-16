<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Sign Up</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title">Sign Up</div>
        <div class="menu">
            <a href="index.php">Home</a>
        </div>
        <div class="main">
            Please enter your details to sign up :
            <br><br>
            <form id="signupForm" action="main/signup" method="post">
                <table>
                    <tr>
                        <td>mail:</td>
                        <td><input id="mail" name="mail" type="text" size="25" value="<?= $mail ?>"></td>
                    </tr>
                    <tr>
                        <td>full_name:</td>
                        <td><input id="full_name" name="full_name" type="text" size="16" value="<?= $full_name ?>"></td>
                    </tr>
                    <tr>
                        <td>IBAN:</td>
                        <td><input id="IBAN" name="IBAN" type="text" size="40" value="<?= $IBAN ?>"></td>
                    </tr>
                    <tr>
                        <td>Password:</td>
                        <td><input id="password" name="password" type="password" size="16" value="<?= $password ?>"></td>
                    </tr>
                    <tr>
                        <td>Confirm Password:</td>
                        <td><input id="password_confirm" name="password_confirm" type="password" size="16" value="<?= $password_confirm ?>"></td>
                    </tr>
                </table>
                <input type="submit" value="Sign Up">
            </form>
            <?php if (count($errors) != 0): ?>
                <div class='errors'>
                    <br><br><p>Please correct the following error(s) :</p>
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