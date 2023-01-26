<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$tricount->title?></title>
</head>
<body>
    <div class="titlebar">
        <a href= ""><button type="button" name = "buttonBack">Back</button></a>
        <?=$tricount->title?> &#8594 Expenses
        <a href="Tricount/EditTricount/<?= $tricount->id?>"><button type="button" name = "buttonEdit">Edit</button>    </div>
    <a href="Tricount/showBalance/<?= $tricount->id?>"><button type="button" name = "buttonViewBalance">&#8644 View balance</button></a>
    <ul>
       <?php foreach($operations as $operation){ ?>
                <li><a href="Operation/showOperation/<?=$operation->id?>">
                    <h1><?=$operation->title;?></h1><div class="amount"><h1><?=$operation->amount;?> €</h1></div><br>
                    Paid by <?=$operation->get_payer()->full_name;?><div class="dateOperation"><?=$operation->operation_date;?></div>
                </a></li>
       <?php } ?>
    </ul>
    <section id="bottomBar">
        <section id="myTotal">
            My total<br>
            <?=$tricount->get_logged_user_total($user->id)?> €
        </section>
        <button name="plusButton">+</button>
        <section id="totalExpenses">
            Total expenses<br>
            <?=$tricount->get_total_expenses();?> €
        </section>
    </section>
</body>
</html>