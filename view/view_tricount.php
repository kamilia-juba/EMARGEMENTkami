<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= $web_root ?>"/>
    <title><?=$tricount->title?></title>
</head>
<body>
    <div class="titlebar">
        <a href= ""><button type="button" name = "buttonBack">Back</button></a>
        <?=$tricount->title?> &#8594 Expenses
        <button type="button" name = "buttonEdit">Edit</button>
    </div>
    <button type="button" name = "buttonViewBalance">&#8644 View balance</button>
    <ul>
       <?php foreach($operations as $operation){ ?>
                <li>
                    <h1><?=$operation->title;?></h1><div class="amount"><h1><?=$operation->amount;?> €</h1></div><br>
                    Paid by <?=$operation->get_payer()->full_name;?><div class="dateOperation"><?=$operation->operation_date;?></div>
                </li>
       <?php } ?>
    </ul>
    <section id="bottomBar">
        <section id="myTotal">
            My total<br>
            <?=$tricount->get_logged_user_total($user->id)?> €
        </section>
        <a href = "Operation/add_operation/<?= $tricount->id?>"><button name="plusButton">+  </button></a>
        <section id="totalExpenses">
            Total expenses<br>
            <?=$tricount->get_total_expenses();?> €
        </section>
    </section>
</body>
</html>