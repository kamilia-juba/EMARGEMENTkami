<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Balance</title>
    <base href="<?= $web_root ?>"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <div class="pt-3 ps-3 pe-3 pb-3 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD "> 
        <a href= "" class= "btn btn-outline-danger" name = "buttonBack">Back</a> <?=$tricount->title?> &#8594 Balance
    </div>
</head>
<body>



    <div class="container-fluid">
        <?php foreach($participants as $participant){?>
        <div class="row g-0">
            <?php if($participant->account>=0):?>
            <div class="col text-end">
                <span><?=$participant->full_name?>&nbsp</span>
            </div>
            <div class="col">
                <div class="progress" style=" height:20px; border-radius:0px; background-color: #FFFFFF">
                    <div class="progress-bar bg-success text-start" role="progressbar" style="width: <?=$participant->account/$sum?>%; border-radius: 0px 6px 6px 0px;" aria-valuenow= "0" aria-valuemin="0" aria-valuemax="100"><span>&nbsp<?=abs(round($participant->account,2))?></div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="row g-0">
            <div class="col justify-content-end ">
                <div class="progress" style="direction: rtl; height:20px; border-radius:0px ; background-color: #FFFFFF;">
                    <div class="progress-bar bg-danger text-end" role="progressbar" style="width: <?=abs($participant->account)/$sum?>%; border-radius: 6px 0px 0px 6px;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="120"><span>&nbsp<?=abs(round($participant->account,2))?>-</span></div>
                </div>
            </div>
            <div class="col">
                <span>&nbsp<?=$participant->full_name?></span>
            </div>
        </div>
        <?php endif; } ?>
    </div>
</body>
</html>