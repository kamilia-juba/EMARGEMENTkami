<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Balance</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
    <div class="pt-3 ps-3 pe-3 pb-3 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD"> 
        <a href= "Tricount/showTricount/<?=$tricount->id?>" class= "btn btn-outline-danger" id = "buttonBack">Back</a> 
        <?=$tricount->title?> &#8594; Balance
    </div>
    <div class="container pt-2 ">
        <div  style="font-size:15px;">
            
            <?php foreach($participants as $participant){?>
            <div class="row g-0 p-1">
            <?php if( $participant->account > 0) : ?>
                <?php if($participant->id==$user->id): ?>
                    <div class="col text-end ">
                        <span class="align-middle" style="font-weight:bolder"><?=$participant->full_name?>&nbsp;</span>
                    </div>
                    <div class="col">
                        <div class="progress" style=" height:28px; border-radius:0px; background-color: #FFFFFF">
                            <div class="progress-bar bg-success text-start" role="progressbar" style="width: <?=$participant->account/$sum?>%; border-radius: 0px 6px 6px 0px;" aria-valuenow= "0" aria-valuemin="0" aria-valuemax="100">
                                <span class="align-middle"  style= "font-weight:bolder; position: absolute; color: black; text-align: right ;overflow: visible;color:black;font-size:15px">&nbsp;<?=abs(round($participant->account,2))?>&nbsp;€</span>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col text-end">
                        <span class="align-middle"><?=$participant->full_name?>&nbsp;</span>
                    </div>
                    <div class="col">
                        <div class="progress" style=" height:28px; border-radius:0px; background-color: #FFFFFF">
                            <div class="progress-bar bg-success text-start" role="progressbar" style="width: <?=$participant->account/$sum?>%; border-radius: 0px 6px 6px 0px;" aria-valuenow= "0" aria-valuemin="0" aria-valuemax="100">
                                <span class="align-middle"  style= "position: absolute; color: black; text-align: right ;overflow: visible;color:black;font-size:15px">&nbsp;<?=abs(round($participant->account,2))?>&nbsp;€</span>
                            </div>
                        </div>
                    </div>
                <?php endif;  ?>
            </div>

        
        
        <?php elseif($participant->account<0) : ?>
            <div class="row g-0 p-1">
            <?php if($participant->id==$user->id) : ?>
                <div class="col justify-content-end ">
                    <div class="progress" style="direction: rtl; height:28px; border-radius:0px ; background-color: #FFFFFF;">
                        <div class="progress-bar bg-danger text-end" role="progressbar" style="width: <?=abs($participant->account)/$sum?>%; border-radius: 6px 0px 0px 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            <span class="align-middle" style=  "font-weight:bolder ;position: absolute; color: black; text-align: right ;overflow: visible; color:black;font-size:15px">&nbsp;€&nbsp;<?=abs(round($participant->account,2))?>-</span>
                        </div>
                    </div>
                </div>
                <div class="col">
                        <span class="align-middle" style="font-weight:bolder ;">&nbsp;<?=$participant->full_name?>&nbsp;(me)</span>
                </div>
            </div>
            <?php else: ?>
                <div class="col justify-content-end ">
                    <div class="progress" style="direction: rtl; height:28px; border-radius:0px ; background-color: #FFFFFF;">
                        <div class="progress-bar bg-danger text-end" role="progressbar" style="width: <?=abs($participant->account)/$sum?>%; border-radius: 6px 0px 0px 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            <span class="align-middle" style=  "position: absolute; color: black; text-align: right ;overflow: visible; color:black;font-size:15px">&nbsp;€&nbsp;<?=abs(round($participant->account,2))?>-</span>
                        </div>
                    </div>
                </div>
                <div class="col">
                        <span class="align-middle" >&nbsp;<?=$participant->full_name?>&nbsp;</span>
                </div>
            <?php endif;  ?>
            
        <?php endif; } ?>
        </div>
    </div>
    
    
    <div class="text-center">
    <?php foreach($participants as $participant){?>           
        <?php if($participant->account==0) : ?>
            <?php if($participant->id==$user->id) : ?>
                <p class="align-middle" style="font-weight:bolder ;">&nbsp;<?=$participant->full_name?>&nbsp;(me)</p>
            <?php else: ?>
                <p class="align-middle" >&nbsp;<?=$participant->full_name?>&nbsp;</p>
            <?php endif;?>
        <?php endif; }?>
    </div>
</div>
</body>
</html>