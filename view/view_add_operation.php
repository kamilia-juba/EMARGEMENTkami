<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?= $tricount->title?> -> New expanse</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <a href ="Tricount/showTricount/<?= $tricount->id?>"> Cancel </a>
        
    </head>
    <body>
        <div class="main">
            <form id="addOperationForm" action="Operation/add_operation/<?= $tricount->id?>" method="post">
            <input type="submit" value="Save">
                <table>
                    <tr>
                        <td><input id="title" name="title" type="text" value="<?= $title?>" placeholder="Title">
                            <?php if (count($errorsTitle) != 0): ?>
                            <div class='errorsTitle'>
                                <?php foreach ($errorsTitle as $errors): ?>
                                    <li><?= $errors ?></li>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?></td>
                    </tr>
                    <tr>
                        <td><input id="amount" name="amount" type="number" value="<?= $amount?>" placeholder="Amount">
                            <?php if (count($errorsAmount) != 0): ?>
                                <div class='errorsAmount'>
                                    <?php foreach ($errorsAmount as $errors): ?>
                                        <li><?= $errors ?></li>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?></td>
                    </tr>
                    <tr>
                        <td><input id="date" name="date" type="date" value="<?php $timezone = date_default_timezone_get(); echo date("Y-m-d")?>"></td>
                    </tr>
                    <tr>
                        <td><select name="paidBy" id="paidBy">
                            <?php foreach ($datas as $data)
                            echo '<option value="' . ($data->id) . '">' .$data->full_name . '</option>';
                        ?> </select></td>
                    </tr>
                    </table>
            </form>
        </div>
    </body>
</html>