<!DOCTYPE html>
<html lang="fr">
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <meta charset="UTF-8">
        <title>Edit profile</title>
        <base href="<?= $web_root ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="lib/jquery-3.6.4.js" type="text/javascript"></script>
        <script src="lib/just-validate-4.2.0.production.min.js" type="text/javascript"></script>
        <script src="lib/sweetalert2@11.js"></script>
        <script>
            var justvalidate = "<?= $justvalidate?>";
            let sweetalert = "<?= $sweetalert?>";


            $(function(){



                title = $("#title");
                errTitle = $("#errTitle");
                errAmount = $("#errAmount");
                amount=$("#amount");
                errWeights = $("#errWeights");
                okWeights = $("#okWeights");

                $("#phpMailError").hide();
                $("#phpNameError").hide();
                $("#phpIbanError").hide();

                if(justvalidate == "off"){

                }
                else{
                    const validation = new JustValidate('#changeProfileForm', {
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
                            .addField('#iban',[
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
                }
            });


        </script>
    </head>
    <body>
        <div class="pt-3 ps-3 pe-3 pb-3 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD">
            <a href="user/settings" class="btn btn-outline-danger">Back</a>
            Edit profile
            <button form="changeProfileForm" class="btn btn-primary" type="submit">Save</button>
        </div>
        <form id="changeProfileForm" action="user/edit_profile" method="post">
            <div class="form-group pt-3 ps-3 pe-3 pb-3">
                <label class="pb-3">Mail :</label>
                <input class="form-control" id="mail" name="mail" type="text" size="16" value="<?= $mail ?>">
            </div>
            <?php if (count($errorsMail) != 0): ?>
                    <div id="phpMailError" class='text-danger'>
                        <ul>
                        <?php foreach ($errorsMail as $errors): ?>
                            <li><?= $errors ?></li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
            <?php endif; ?>
            <div class="form-group pt-3 ps-3 pe-3 pb-3">
                <label class="pb-3">Full name :</label>
                <input class="form-control" id="full_name" name="full_name" type="text" size="16" value="<?= $full_name ?>" placeholder="Enter your name">
            </div>
            <?php if (count($errorsName) != 0): ?>
                    <div id="phpNameError" class='text-danger'>
                        <ul>
                        <?php foreach ($errorsName as $errors): ?>
                            <li><?= $errors ?></li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
            <?php endif; ?>
            <div class="form-group ps-3 pt-3 pe-3 pb-3">
                <label class="pb-3">IBAN :</label>
                <input class="form-control" id="iban" name="iban" type="text" size="40" value="<?= $iban ?>" placeholder="Enter your IBAN">
            </div>
            <?php if (count($errorsIban) != 0): ?>
                    <div id="phpIbanError" class='text-danger'>
                        <ul>
                        <?php foreach ($errorsIban as $errors): ?>
                            <li><?= $errors ?></li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
        <?php endif; ?>
        </form>
    </body>
</html>