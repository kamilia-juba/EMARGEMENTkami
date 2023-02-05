<!DOCTYPE html>
<html lang="fr">
    <head>
    <meta charset="UTF-8">
        <title>Sign up</title>
        <base href="<?= $web_root ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link rel=”stylesheet” href=”https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css” >
        <link rel="stylesheet" href="https://kit.fontawesome.com/991f3da7c3.css" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/fd46891f37.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <div class="bg-primary p-3 fs-5 text-light">
            <header>
                <i class="fas fa-cat"></i> Tricount
            </header>
        </div>
        <div class="container d-flex align-items-center min-vh-100">
            <div class="container border rounded ">
                <div class="container border-bottom text-center pt-4 pb-4"><h1>Sign up</h1></div>
            <br><br>
            <form id="signupForm" action="main/signup" method="post">
                <div class="input-group mb-3 mt-3">
                    <span class="input-group-text" ><i class="fa-solid fa-at"></i></span>
                    <input class="form-control" id="mail" name="mail" type="text"  value="<?= $mail ?>" placeholder="Email" >
                </div>
                <div class="input-group mb-3 mt-3">
                    <span class="input-group-text" ><i class="fa-solid fa-user"></i></span>
                    <input class="form-control" id="full_name" name="full_name" type="text"  value="<?= $full_name ?>" placeholder="full_name" >
                </div>
                <div class="input-group mb-3 mt-3">
                    <span class="input-group-text" ><i class="fa-sharp fa-solid fa-credit-card"></i></span>
                    <input class="form-control" id="IBAN" name="IBAN" type="text"  value="<?= $IBAN ?>" placeholder="IBAN" >
                </div>
                
                <div class="input-group mb-3 mt-3">
                    <span class="input-group-text" ><i class="fa-solid fa-lock"></i></span>
                    <input class="form-control" id="password" name="password" type="password"  value="<?= $password ?>" placeholder="password" >
                </div>
                <div class="input-group mb-3 mt-3">
                    <span class="input-group-text" ><i class="fa-solid fa-lock"></i></span>
                    <input class="form-control" id="password_confirm" name="password_confirm" type="password"  value="<?= $password_confirm ?>" placeholder="Confirm your password" >
                </div>
                
                <input type="submit" class="btn btn-primary w-100 mb-3" value="Sign Up" form="signupForm"><br>
                <a href="" class="btn btn-outline-danger w-100 mb-3">Back</a>
                   
                
            </form>
            
            <?php if (count($errors) != 0): ?>
                <div class='text-center text-danger'>
                    <br><br><p>Please correct the following error(s) :</p>
                    <ul >
                        <?php foreach ($errors as $error): ?>
                            <li class="list-inline-item"><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>

       
    </body>
</html>