<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Sign Up</title>
        <base href="<?= $web_root ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="lib/just-validate-4.2.0.production.min.js" type="text/javascript"></script>
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <script src="lib/just-validate-plugin-date-1.2.0.production.min.js"></script>
        <script src="lib/sweetalert2@11.js"></script>
        <script src="lib/jquery-3.6.3.min.js" type="text/javascript"></script>
        <script>
            let justvalidate = "<?= $justvalidate ?>";
            let sweetalert = "<?=$sweetalert?>";

            $(function(){
                if(justvalidate == "on"){
                    const validation = new JustValidate('#changeMotdepasseForm', {
                        validateBeforeSubmitting: true,
                        lockForm: true,
                        focusInvalidField: false,
                        successLabelCssClass: 'valid-feedback',
                        errorLabelCssClass: 'invalid-feedback',
                        errorFieldCssClass: 'is-invalid',
                        successFieldCssClass: 'is-valid',
                    });

                    validation
                        .addField("#actual_password", [
                            {
                                rule: "required",
                                errorMessage: "This field can't be empty"
                            },
                        ], {successMessage: "Looks good"})
                        .addField("#password", [
                            {
                                rule: "required",
                                errorMessage: "This field can't be empty"
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
                                errorMessage: 'Password must contain at least one capital letter, one special character and one number' 
                            },
                        ], {successMessage: "Looks good"})
                        .addField("#password_confirm", [
                            {
                                rule: "required",
                                errorMessage: "This field can't be empty"
                            },
                        ], {successMessage: "Looks good"})
                        .onValidate(async function(){
                            var correctPassword = await $.post("User/check_correct_password_service", {actualPassword: $("#actual_password").val()}, null, "json");
                            if (correctPassword){
                                this.showErrors({"#actual_password" : "Wrong password"});
                            }
                            var correctPassword = await $.post("User/check_correct_password_service", {actualPassword: $("#password").val()}, null, "json");
                            if(!correctPassword){
                                this.showErrors({"#password" : "New password can't be the same as the old one"});
                            }
                            var passwords_dont_match = await $.post("User/passwords_matches_service", {password: $("#password").val(), password_confirm: $("#password_confirm").val()}, null, "json");
                            if(passwords_dont_match){
                                this.showErrors({"#password_confirm" : "Passwords must match"});
                            }
                        })
                        .onSuccess(function(event){
                            event.target.submit();
                        });
                }
            })

        </script>
    </head>
    <body>
        <div class="pt-3 ps-3 pe-3 pb-3 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD "> 
            <a href= "user/settings" class= "btn btn-outline-danger">Back</a>
            Change password
            <button form="changeMotdepasseForm" class="btn btn-outline-primary" type="submit">Save</button>
        </div>
        <form id="changeMotdepasseForm" action="user/change_password" method="post">
            <div class="form-group pt-3 ps-3 pe-3 pb-3">
                <label class="pb-3">Actual Password :</label>
                <input class="form-control" id="actual_password" name="actual_password" type="password" size="16" value="<?=$actual_password?>">
            </div>
            <div class="form-group pt-3 ps-3 pe-3 pb-3">
                <label class="pb-3">New Password :</label>
                <input class="form-control" id="password" name="password" type="password" size="16" value="<?=$password?>">
            </div>
            <div class="form-group pt-3 ps-3 pe-3 pb-3">
                <label class="pb-3">Confirm New Password: :</label>
                <input class="form-control" id="password_confirm" name="password_confirm" type="password" size="16" value="<?=$password_confirm?>">
            </div>
        </form>
        <?php if (count($errors) != 0): ?>
            <div class='text-danger ps-3 pt-3 pe-3 pb-3'>
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
    </body>
</html>