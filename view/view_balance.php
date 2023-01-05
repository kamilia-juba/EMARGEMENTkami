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
            for($i=0;$i<sizeof($tricount->get_totals());++$i){
                if($tricount->get_totals()[$i][0]<0){
                    echo "<tr><td>".$tricount->get_totals()[$i][0]."</td><td>".$tricount->get_totals()[$i][1]."</td></tr>";
                } else {
                    echo "<tr><td>".$tricount->get_totals()[$i][1]."</td><td>".$tricount->get_totals()[$i][0]."</td></tr>";
                }
            }
        ?>
        
    </table>
</body>
</html>