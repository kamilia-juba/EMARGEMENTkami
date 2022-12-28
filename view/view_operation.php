<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <base href="<?= $web_root ?>"/>
        <title>Document</title>
    </head>
    <body>
        <section id="titlebar">
            <button name="buttonBack">Back</button>
            <?=$tricount->title?> &#8594 <?=$operation->title?>
            <button name="buttonEdit">Edit</button>
        </section>
        <h1><?=$operation->amount?> €</h1>
        <table>
        
        <tr><td>Paid by <?= $user->id==$paidBy->id ? "<b>me</b>" : $paidBy->full_name; ?></td><td><?= $operation->operation_date?></td></tr>
        <tr><td>For <?= sizeof($participants)==1 ? sizeof($participants)." participant" : sizeof($participants)." participants" ?><?= $user_participates ? ", including <b>me</b>" : ""?></td></tr>

        </table>
        
    </body>
</html>