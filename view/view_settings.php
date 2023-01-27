<!DOCTYPE html>
<html>
    <head>
    <div class="pt-3 ps-3 pe-3 pb-3 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD "> 
        <a href= "" class= "btn btn-outline-danger" name = "buttonBack">Back</a> Settings</div>
        <meta charset="UTF-8">
        <title>Settings</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    </head>
    <body>
        <div class="pt-3 ps-3 pe-3 pb-3 "> 
            <p>Hey <?= $user->full_name ?>!</p>
            <p style="display:inline">  I know your email address is <p style="display:inline" class="text-danger"><?= $user->mail?></p></p>
            <p>What can I do you for you?</p>
        </div>

    </body>
    <footer>    
        <div class="text-center">
        <a href = "user/edit_profile"><button type="button" class="btn btn-outline-primary col-11">Edit Profile</button></a>
        <p></p>
        <a href = "user/change_password"><button type="button" class="btn btn-outline-primary col-11">Change Password</button></a>
        <p></p>
        <a href = "user/logout"><button type="button" class="btn btn-danger col-11">Logout</button></a>
        <br></br>
        </div>
    </footer>
</html>