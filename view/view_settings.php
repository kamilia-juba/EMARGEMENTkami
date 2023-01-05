<!DOCTYPE html>
<html>
    <head>
        <a href = "tricount/yourTricounts"> Back </a>
        <meta charset="UTF-8">
        <title>Settings</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div class="title"> Hey <?= $user->full_name ?>!</div>
        <br><br>
        <div class="text"> I know your email address is <?= $user->mail?>. </div>
        <br><br>
        <a href = "user/edit_profile"> Edit Profile </a>
        <br><br>
        <a href = "user/change_password"> Change Password </a>
        <br><br>
        <a href = "user/logout"> Logout </a>
        <br><br>
    </body>
</html>