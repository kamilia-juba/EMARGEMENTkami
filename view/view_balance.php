<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Balance</title>
</head>
<body>
    <table>
        <?php
            for($i=0;$i<sizeof($tricount->get_totals());++$i){
                echo "<tr><td>".$tricount->get_totals()[$i][0]."</td><td>".$tricount->get_totals()[$i][1]."</td></tr>";
            }
        ?>
        
    </table>
</body>
</html>