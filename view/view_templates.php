<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Templates</title>
    <base href="<?= $web_root ?>"/>
</head>
<body>
    <a href=""><button type="button" name="backButton">Back</button></a>
    <?=$tricount->title?> &#8594 Templates
    <a href=""><button type="button" name="addButton">Add</button></a>
    <div class="listTemplates">
        <table>
            <?php for($i = 0; $i<sizeof($templates_items);++$i){ ?>
                    <tr>
                        <td><a href="">
                            <?= $templates_items[$i][1]->title ?>
                            <ul>
                                <?php foreach($templates_items[$i][0] as $user) { ?>
                                        <li><?=$user->full_name?> (<?=$templates_items[$i][1]->get_repartition_user_weight($user->id)?>/<?=$templates_items[$i][1]->get_repartition_total_weight()?>)</li>
                                <?php } ?>
                            </ul>
                        </a></td>
                    </tr>
            <?php } ?>
        </table>
    </div>
</body>
</html>