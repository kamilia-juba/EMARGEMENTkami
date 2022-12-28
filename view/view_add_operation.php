<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= $tricount->title?> -> New expanse</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <a href ="Tricount/showTricount/<?= $tricount->id?>"> Back </a>
    </head>
    <body>
        <div class="main">
            <form id="addOperationForm" action="operation/add_operation" method="post">
                <table>
                    <tr>
                        <td><input id="title" name="title" type="text" placeholder="Title"></td>
                    </tr>
                    <tr>
                        <td><input id="amount" name="amount" type="number" placeholder="Amount"></td>
                    </tr>
                    <tr>
                        <td><input id="date" name="date" type="date" value="<?php $timezone = date_default_timezone_get(); echo date("Y-m-d")?>"></td>
                    </tr>
                    <tr>
                        <td><select name="paidBy" id="paidBy">
                            <?php foreach ($data as $data): ?>
                            <option value=<?= $data->full_name ?>></option>
                        <?php endforeach; ?> </select></td>
                    </tr>
                    </table>
                <input type="submit" value="Save">
            </form>
            <?php if (count($errors) != 0): ?>
                <div class='errors'>
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
        </div>
    </body>
</html>