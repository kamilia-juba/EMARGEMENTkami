<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title><?= $template->title?> -> Edit template</title>
        <base href="<?= $web_root ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    </head>
    <body>
        <div class="pt-2 ps-3 pe-3 pb-2 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD">
            <a href="Tricount/showTemplates/<?=$tricount->id?>" class="btn btn-outline-danger">Cancel</a>
            <b><?=$tricount->title?> &#8594; Edit Template</b>
            <input type="submit" class="btn btn-primary" form="applyTemplateForm" value="Save">
        </div>
        <div class="container min-vh-100 pt-2">
            <form id="applyTemplateForm" action="Template/edit_template/<?=$tricount->id?>/<?=$template->id?>" method="post">
                Title : 
                <input class="form-control mb-2" id="title" name="title" type="text" value="<?= $template->title?>" placeholder="Title">
                Template items :
                <?php for($i = 0; $i<sizeof($participants_and_weights);++$i){  ?>
                    <div class="input-group mb-2 mt-2">
                        <span class="form-control" style="background-color: #E9ECEF">
                            <input type="checkbox" 
                                name="checkboxParticipants[]" 
                                value ="<?=$participants_and_weights[$i][0]->id?>" 
                                <?php if($participants_and_weights[$i][2]){ ?>
                                            checked
                                <?php } ?>
                            >
                        </span>
                        <span class="input-group-text w-75" style="background-color: #E9ECEF"><?=$participants_and_weights[$i][0]->full_name?></span>
                        <input class="form-control" type="number" min="0" name="weight[]" value="<?=$participants_and_weights[$i][1]?>">
                    </div>
                <?php } ?> 
            </form>
            <?php if (count($errors) != 0): ?>
                <div class="text-danger">
                    <ul class="list-inline">
                    <?php foreach ($errors as $errors): ?>
                            <li><?= $errors ?></li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <a href="Template/deleteTemplate/<?=$tricount->id?>/<?=$template->id?>" class="btn btn-danger w-100">Delete Template</a> 
        </div>
    </body>
</html>