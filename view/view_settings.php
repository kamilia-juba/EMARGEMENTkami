<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Settings</title>
        <base href="<?= $web_root ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    </head>
    <body>
        <div class="pt-3 ps-3 pe-3 pb-3 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD"> 
            <a href= "" class= "btn btn-outline-danger">Back</a> Settings
        </div>
        <div class="pt-3 ps-3 pe-3 pb-3 " style="font-size:14px"> 
            <div style="display:inline">Hey <div style="display:inline;font-weight:bold;"><?= $user->full_name ?></div>!</div><br><br>
            <div style="display:inline">  I know your email address is <div style="display:inline" class="text-danger"><?= $user->mail?>.</div></div><br><br>
            <p>What can I do you for you?</p>
        </div>

        <footer>    
            <div class="text-center fixed-bottom">
            <a href = "user/edit_profile" class="btn btn-outline-primary col-11">Edit Profile</a>
            <p></p>
            <a href = "user/change_password" class="btn btn-outline-primary col-11">Change Password</a>
            <p></p>
            <a href = "user/logout" class="btn btn-danger col-11">Logout</a>
            <br>
            </div>
        </footer>
    </body>
</html>