<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <base href="<?= $web_root ?>"/>
        <title><?=$operation->title?></title>
    </head>
    <body>
        <section id="titlebar">
            <a href="Tricount/showTricount/<?=$tricount->id?>"><button name="buttonBack">Back</button></a>
            <?=$tricount->title?> &#8594 <?=$operation->title?>
            <a href="Operation/editOperation/<?=$tricount->id?>/<?=$operation->id?>"><button name="buttonEdit">Edit</button></a>
        </section>
        <h1><?=$operation->amount?> €</h1>
        <table>
        
        <tr><td>Paid by <?= $user->id==$paidBy->id ? "<b>me</b>" : $paidBy->full_name; ?></td><td><?= $operation->operation_date?></td></tr>
        <tr><td>For <?= sizeof($users)==1 ? sizeof($users)." participant" : sizeof($users)." participants" ?><?= $user_participates ? ", including <b>me</b>" : ""?></td></tr>

        </table>
        <table>
            <?php
                foreach($users as $participant){
                    if($participant[0]->id==$user->id){
                    echo "<tr><td><b>".$participant[0]->full_name." (me)</b></td><td><b>".$participant[1]." €</b></td></tr>";
                    }else{
                        echo "<tr><td>".$participant[0]->full_name."</td><td>".$participant[1]." €</td></tr>";
                    }
                }
            ?>
        </table>
        <section id="bottombar">
            <?php 
                if($currentIndex!=0){ ?>
                    <a href="Operation/showOperation/<?=$tricount->id?>/<?=$operations[$currentIndex-1]->id?>"><button type='button' name='buttonPrevious'>Previous</button></a>
                <?php } ?>
            <?php
                if($currentIndex<sizeof($operations)-1){ ?>
                    <a href="Operation/showOperation/<?=$tricount->id?>/<?=$operations[$currentIndex+1]->id?>"><button type="button" name="buttonNext">Next</button></a>
                <?php } ?>
        </section>
        
    </body>
</html>