<!DOCTYPE html>
<html>
    <head>
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
        <button onclick="location.href = 'main/logout';" id="myButton" class="float-left submit-button" >Logout</button>
        <br><br>
    </body>
</html>