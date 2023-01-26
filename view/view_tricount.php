<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= $web_root ?>"/>
    <title><?=$tricount->title?></title>
</head>
<body>
    <div class="titlebar">
        <a href= ""><button type="button" name = "buttonBack">Back</button></a>
        <?=$tricount->title?> &#8594 Expenses
        <a href="Tricount/EditTricount/<?= $tricount->id?>"><button type="button" name = "buttonEdit">Edit</button>    </div>
    <a href="Tricount/showBalance/<?= $tricount->id?>"><button type="button" name = "buttonViewBalance">&#8644 View balance</button></a>
    <ul>
    </div>
    <?php if($alone || $noExpenses){ ?>
            <table>
                <tr>
                    <td><?= $alone ? "You are alone!" : "Your Tricount is empty!" ?></td>
                </tr>
                <tr>
                    <td>
                        <?= $alone ? "Click below to add your friends!" : "Click below to add your first expense!" ?><br>
                        <?= $alone ?"<a href=''><button type='button' name='addFriendOrExpenseBtn'>Add friends</button></a>" 
                                    : "<a href='Operation/add_operation/$tricount->id'><button type='button' name='addFriendOrExpenseBtn'>Add an expense</button></a>" ?>
                    </td>
                </tr>
            </table>
    <?php }else{ ?>
        <a href="Tricount/showBalance/<?= $tricount->id?>"><button type="button" name = "buttonViewBalance">&#8644 View balance</button></a>
        <ul>
       <?php foreach($operations as $operation){ ?>
                <li><a href="Operation/showOperation/<?=$tricount->id?>/<?=$operation->id?>">
                    <h1><?=$operation->title;?></h1><div class="amount"><h1><?=$operation->amount;?> €</h1></div><br>
                    Paid by <?=$operation->get_payer()->full_name;?><div class="dateOperation"><?=$operation->operation_date;?></div>
                </a></li>
       <?php } ?>
        </ul>
    <?php } ?>
    
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