<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Balance</title>
    <base href="<?= $web_root ?>"/>
</head>
<body>
    <section id="titlebar">
    <a href="Tricount/showTricount/<?= $tricount->id?>"><button type ="button" name="buttonBack">Back</button></a>
        <?=$tricount->title?> &#8594 Balance
    </section>
    <table>
        <?php
            foreach($participants as $participant){
                echo $participant->account;
            }

            
        ?>
        
    </table>
</body>
</html>