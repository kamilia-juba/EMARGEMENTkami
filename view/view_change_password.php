<!DOCTYPE html>
<html>
    <head>
        <div class="pt-3 ps-3 pe-3 pb-3 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD "> 
        <a href= "user/settings" class= "btn btn-outline-danger" name = "buttonBack">Back</a> Change Password</div>
        <meta charset="UTF-8">
        <title>Sign Up</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    </head>
    <body>
        <div class="menu">
        </div>
        <div class="main">
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
                <input class="btn btn-outline-primary col-11" type="submit" value="Save">
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