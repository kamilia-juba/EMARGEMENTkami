<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$tricount->title?></title>
</head>
<body>
    <div class="titlebar">
        <button type="button" name = "buttonBack">Back</button>
        <?=$tricount->title?> &#8594 Expenses
        <button type="button" name = "buttonEdit">Edit</button>
    </div>
    <button type="button" name = "buttonViewBalance">&#8644 View balance</button>
    <ul>
       <?php foreach($operations as $operation){ ?>
                <li><?=$operation->title;?></li>
       <?php } ?>
    </ul>
</body>
</html>