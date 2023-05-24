<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Log In</title>
        <base href="<?= $web_root ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link rel=”stylesheet” href=”https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css”>
        <link rel="stylesheet" href="https://kit.fontawesome.com/991f3da7c3.css" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/fd46891f37.js" crossorigin="anonymous"></script>
        <script src="lib/jquery-3.6.3.min.js" type="text/javascript"></script>
        <script src="lib/just-validate-4.2.0.production.min.js" type="text/javascript"></script>
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <script src="lib/sweetalert2@11.js"></script>
        <script>
            console.log("<?= $justvalidate?>");
            var justvalidate = "<?= $justvalidate?>";
             let mailAvailable ;

             $(function(){
                if(justvalidate =="on"){
                    const validation = new JustValidate('#LoginForm', {
                        validateBeforeSubmitting: true,
                            lockForm: true,
                        focusInvalidField: false,
                            successLabelCssClass: 'valid-feedback',
                            errorLabelCssClass: 'invalid-feedback',
                            errorFieldCssClass: 'is-invalid',
                            successFieldCssClass: 'is-valid',
                    });

                        validation
                            .addField('#mail',[
                            {
                                rule: 'required',
                                errorMessage: 'mail is required'
                            },
                            {
                                rule: 'customRegexp',
                                value : /^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/,
                                errorMessage: 'Veuillez saisir une adresse e-mail valide au format example@domaine.com.'
                            },
                        ], { successMessage: 'Looks good !' })


                        validation
                            .addField('#password',[
                                {
                                    rule: 'required',
                                    errorMessage: 'Password is required'
                                },
                                
                            ],{ successMessage: 'Looks good !' }) 
                            
                        .onValidate(async function(event) {
                            mailAvailable = await $.post("User/Mail_exists_service/", {newEmail: $("#mail").val()},null,"json");
                            if (mailAvailable){
                                this.showErrors({ '#mail': 'this mail doesnt exist' });
                            }   
                        })

                        .onSuccess(function(event) {
                           
                           event.target.submit(); //par défaut le form n'est pas soumis
                   })
                }   
            
             }) ;
        </script>
    </head>
    <body>
        <div class="bg-primary p-3 fs-5 text-light">
            <header>
                <i class="fas fa-cat"></i> Tricount
            </header>
        </div>
        <div class="container d-flex align-items-center min-vh-100">
            <div class="container border rounded ">
                <div class="container border-bottom text-center pt-4 pb-4"><h1>Sign in</h1></div>
                <form action="main/login" id="LoginForm" method="post">
                    <div class="input-group mb-3 mt-3">
                        <span class="input-group-text"><i class="fa-sharp fa-solid fa-user"></i></span>
                        <input class="form-control" id="mail" name="mail" type="text" value="<?= $mail ?>">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa-sharp fa-solid fa-lock"></i></span>
                        <input id="password" class="form-control" name="password" type="password" value="<?= $password ?>">
                    </div>
                    <input type="submit" class="btn btn-primary w-100 mb-3" value="Log In"><br>
                    <div class="text-center mb-3">
                        <a href="main/signup" class="text-decoration-none">New here ? Click here to join party <i class="fa-solid fa-party-horn"></i> !</a>
                    </div>
                </form>
                <?php if (count($errors) != 0): ?>
                    <div class='text-center text-danger'>
                        <ul class="list-inline">
                            <?php foreach ($errors as $error): ?>
                                <li class="list-inline-item"><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    </body>
</html>
