<!DOCTYPE html>
<html lang="fr">
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <meta charset="UTF-8">
        <title>Edit profile</title>
        <base href="<?= $web_root ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            <div class="form-group pt-3 ps-3 pe-3 pb-3">
                    <label class="pb-3">Full name :</label>
                    <input class="form-control" id="full_name" name="full_name" type="text" size="16" value="<?= $full_name ?>" placeholder="Enter your name">
            </div>
            <div class="form-group ps-3 pt-3 pe-3 pb-3">
                    <label class="pb-3">IBAN :</label>
                    <input class="form-control" id="iban" name="iban" type="text" size="40" value="<?= $iban ?>" placeholder="Enter your IBAN">
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
        <?php endif; ?>
    </body>
</html>