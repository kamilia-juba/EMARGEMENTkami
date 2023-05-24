<!DOCTYPE html>
<html lang="fr">
    <head>
    <meta charset="UTF-8">
        <title>Sign up</title>
        <base href="<?= $web_root ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <link rel=”stylesheet” href=”https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css” />
        <link rel="stylesheet" href="https://kit.fontawesome.com/991f3da7c3.js" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/fd46891f37.js" crossorigin="anonymous"></script>
        <script src="lib/jquery-3.6.3.min.js" type="text/javascript"></script>
        <script src="lib/just-validate-4.2.0.production.min.js" type="text/javascript"></script>
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <script src="lib/sweetalert2@11.js"></script>
        <script>
           

             var justvalidate = "<?= $justvalidate?>";
             let mailAvailable ;

             function hide_php_errors(){
                 $("#errorEmail").hide();
                 $("#ErrorName").hide();
                 $("#errorIban").hide();
                 $("#ErrorPasswordConfirme").hide();
                
             }

             function confirmPasswordRule(value) {
  // Get the value of the "Password" field
  var passwordValue = document.querySelector('#password').value;
  
  // Perform the validation by comparing the values
  return value === passwordValue;
}
             $(function(){
                hide_php_errors();

                if(justvalidate =="on"){
                    const validation = new JustValidate('#signupForm', {
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
                            }
                        ], { successMessage: 'Looks good !' })
                        validation
                            .addField('#full_name',[
                                {
                                    rule: 'required',
                                    errorMessage: 'Name is required'
                                },
                                {
                                    rule: 'minLength',
                                    value: 3,
                                    errorMessage: 'Minimum 3 characters'
                                },
                                {
                                    rule: 'maxLength',
                                    value: 16,
                                errorMessage: 'Maximum 16 characters'
                                },
                            ],{ successMessage: 'Looks good !' })
                        validation
                            .addField('#IBAN',[
                                {
                                rule: 'required',
                                errorMessage: 'mail is required'
                            },
                            {
                                rule: 'customRegexp',
                                value : /^(?=.{5,34}$)[A-Z]{2}\d{2}[A-Za-z0-9]{1,30}$/,
                                errorMessage: 'IBAN saisi n\'est pas valide. Veuillez vérifier et entrer un IBAN correct.'
                            }
                            ],{ successMessage: 'Looks good !' })

                        validation
                            .addField('#password',[
                                {
                                    rule: 'required',
                                    errorMessage: 'Password is required'
                                },
                                {
                                    rule: 'minLength',
                                    value: 8,
                                    errorMessage: 'Minimum 8 characters'
                                },
                                {
                                    rule: 'maxLength',
                                    value: 16,
                                errorMessage: 'Maximum 16 characters'
                                },
                                {
                                    rule: 'customRegexp',
                                    value : /^(?=.*[A-Z])(?=.*\d)(?=.*['\";:,.\/?!\\-]).+$/,
                                    errorMessage: 'Le mot de passe doit contenir au moins une lettre majuscule, un chiffre et un caractère spécial ' 
                                }
                                
                            ],{ successMessage: 'Looks good !' })  
                            
                            

                            .onValidate(async function(event) {
                                titleAvailable = await $.post("user/Mail_exists_service/", {newMail: $("#mail").val()},null,"json");

                                }
                            )}
                            $("input:text:first").focus();

                    
                });
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
                <div class="container border-bottom text-center pt-4 pb-4"><h1>Sign up</h1></div>
            <br><br>
            <form id="signupForm" action="main/signup" method="post">
                <div class="input-group mb-3 mt-3">
                    <span class="input-group-text" ><i class="fa-solid fa-at"></i></span>
                    <input class="form-control" id="mail" name="mail" type="text"  value="<?= $mail ?>" placeholder="Email" >
                </div>
                <?php if (count($errorsEmail) != 0): ?>
                    <div class='text-danger' id="errorEmail">      
                        <ul>
                            <?php foreach ($errorsEmail as $errors): ?>
                                <li><?= $errors ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <div class="input-group mb-3 mt-3">
                    <span class="input-group-text" ><i class="fa-solid fa-user"></i></span>
                    <input class="form-control" id="full_name" name="full_name" type="text"  value="<?= $full_name ?>" placeholder="Full Name" >
                </div>
                <?php if (count($errorsName) != 0): ?>
                    <div id="ErrorName" class='text-danger'>      
                        <ul>
                            <?php foreach ($errorsName as $errors): ?>
                                <li><?= $errors ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                <div class="input-group mb-3 mt-3">
                    <span class="input-group-text" ><i class="fa-sharp fa-solid fa-credit-card"></i></span>
                    <input class="form-control" id="IBAN" name="IBAN" type="text"  value="<?= $IBAN ?>" placeholder="IBAN" >
                </div>
                <?php if (count($errorsIban) != 0): ?>
                    <div id="errorIban" class='text-danger'>      
                        <ul>
                            <?php foreach ($errorsIban as $errors): ?>
                                <li><?= $errors ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <div class="input-group mb-3 mt-3">
                    <span class="input-group-text" ><i class="fa-solid fa-lock"></i></span>
                    <input class="form-control" id="password" name="password" type="password"  value="<?= $password ?>" placeholder="password" >
                </div>
                <div class="input-group mb-3 mt-3">
                    <span class="input-group-text" ><i class="fa-solid fa-lock"></i></span>
                    <input class="form-control" id="passwordConfirm" name="password_confirm" type="password"  value="<?= $password_confirm ?>" placeholder="Confirm your password" >
                </div>
                <?php if (count($errorsPasswordConfirm) != 0): ?>
                    <div id="ErrorPasswordConfirme" class='text-danger'>      
                        <ul>
                            <?php foreach ($errorsPasswordConfirm as $errors): ?>
                                <li><?= $errors ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <input type="submit" class="btn btn-primary w-100 mb-3" value="Sign Up" form="signupForm"><br>
                <a href="" class="btn btn-outline-danger w-100 mb-3">Back</a>
                   
                
            </form>
        </div>
    </div>

       
    </body>
</html>