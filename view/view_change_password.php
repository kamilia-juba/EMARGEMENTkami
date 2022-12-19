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
        <div class="title">Change Password</div>
        <div class="menu">
        </div>
        <div class="main">
            Please enter your details to sign up :
            <br><br>
            <form id="changeProfileForm" action="user/change_password" method="post">
                <table>
                <tr>
                        <td>Password:</td>
                        <td><input id="password" name="password" type="password" size="16" value=""></td>
                    </tr>
                    <tr>
                        <td>Confirm Password:</td>
                        <td><input id="password_confirm" name="password_confirm" type="password" size="16" value=""></td>
                    </tr>
                    </table>
                <input type="submit" value="Save">
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
                <?php elseif (strlen($success) != 0): ?>
                <p><span class='success'><?= $success ?></span></p>
            <?php endif; ?>
        </div>
    </body>
</html>