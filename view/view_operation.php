<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <base href="<?= $web_root ?>"/>
        <title><?=$operation->title?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    </head>
    <body>
        <div class="pt-3 ps-3 pe-3 pb-3 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD">
            <a href="Tricount/showTricount/<?=$tricount->id?>" class="btn btn-outline-danger" name="buttonBack">Back</a>
            <?=$tricount->title?> &#8594 <?=$operation->title?>
            <a href="Operation/editOperation/<?=$tricount->id?>/<?=$operation->id?>" class="btn btn-primary" name="buttonEdit">Edit</a>
            </div>
        <h1 class="text-center p-3"><?=$operation->amount?> €</h1>
        
        <div class="container ps-3 pe-3 pb-3">
            <div class="d-flex justify-content-between">
                <p>Paid by <?= $user->id==$paidBy->id ? "<b>me</b>" : $paidBy->full_name; ?></p>
                <p><?= $operation->operation_date?></p>
            </div>
            <p>For <?= sizeof($users)==1 ? sizeof($users)." participant" : sizeof($users)." participants" ?><?= $user_participates ? ", including <b>me</b>" : ""?></p>
        </div>
        <ul class="list-group p-2 ms-2 me-2 mb-2">
            <?php foreach($users as $participant){ 
                    if($participant[0]->id==$user->id){
                        echo "<li class='list-group-item d-flex justify-content-between'><p><b>".$participant[0]->full_name." (me)</b></p>";
                        echo "<p><b>".$participant[1]." €</b></p></li>";
                    }else{
                        echo "<li class='list-group-item d-flex justify-content-between'><p>".$participant[0]->full_name."</p>";
                        echo "<p>".$participant[1]." €</p></li>";
                    }
                }
            ?>
        </ul>
        <div class="container w-100 p-5 mb-5 mt-5">
        </div>
        <footer class="footer mt-auto fixed-bottom pt-2 ps-2 pe-2 pb-2 fs-5 d-flex justify-content-between" style="background-color: #E3F3FD">
            <?php 
                if($currentIndex!=0){ ?>
                    <a href="Operation/showOperation/<?=$tricount->id?>/<?=$operations[$currentIndex-1]->id?>"class="btn btn-primary" name='buttonPrevious'>Previous</a>
                <?php }else { ?>
                    <p></p>
                <?php } ?>
            <?php
                if($currentIndex<sizeof($operations)-1){ ?>
                    <a href="Operation/showOperation/<?=$tricount->id?>/<?=$operations[$currentIndex+1]->id?>"class="btn btn-primary" name="buttonNext">Next</button></a>
                <?php }else { ?>
                    <p></p>
                <?php } ?>
        </footer>
        
    </body>
</html>