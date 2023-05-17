<!DOCTYPE html>
<html lang="fr">
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">   
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Templates</title>
        <base href="<?= $web_root ?>">
    </head>
    <body>
        <div class="pt-3 ps-3 pe-3 pb-3 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD">   
            <a href="Tricount/edit_tricount/<?=$tricount->id?>" class="btn btn-outline-danger">Back</a>
            <?=$tricount->title?> &#8594; Templates
            <a href="Tricount/add_template/<?=$tricount->id?>" class="btn btn-primary">Add</a>
        </div>
        <ul class="list-group p-2">
            <?php for($i = 0; $i<sizeof($templates_items);++$i){ ?>
                <li class="list-group-item ps-3"> <a href="Template/edit_template/<?=$tricount->id?>/<?=$templates_items[$i][1]->id?>" class="text-decoration-none text-dark">
                    <div>
                        <h4><?= $templates_items[$i][1]->title ?></h4>
                        <ul>
                            <?php foreach($templates_items[$i][0] as $user) { ?>
                                 <li ><?=$user->full_name?> (<?=$templates_items[$i][1]->get_repartition_user_weight($user)?>/<?=$templates_items[$i][1]->get_repartition_total_weight()?>)</li>
                             <?php } ?>
                        </ul>
                    </div>
                </a></li>
            <?php } ?>
        </ul>
    </body>
</html>