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
    <section id="titlebar">
    <a href="Tricount/showTricount/<?= $tricount->id?>"><button type ="button" name="buttonBack">Back</button></a>
        <?=$tricount->title?> &#8594 Balance
    </section>
    <table>
        <?php
            foreach($participants as $participant){
                echo round($participant->account,2). "  ". $participant->full_name;
            }

            echo $maxUser->account;

            
        ?>
    </table>
    <table><td>
<tr><div class="progress"  style="height:20px; border-radius: 0; background-color: #FFFFFF">
  <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="25"></div>
</div>
</tr>
</td></table>
    </table>
</body>
</html>