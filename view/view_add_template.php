<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Add Template</title>
        <base href="<?= $web_root ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="lib/jquery-3.6.4.js" type="text/javascript"></script>
        <script src="scripts/add_template_jquery.js"></script>
    </head>
 

    <body>
        
        <div class="pt-2 ps-3 pe-3 pb-2 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD">
            <a href="Tricount/showTemplates/<?=$tricount->id?>" class="btn btn-outline-danger" >Cancel</a>
            <?=$tricount->title?> &#8594; ew template
            <input type="submit" class="btn btn-primary" value="Save" form="addtemplateForm">
        </div> 
        <div class="container">
        <form id="addtemplateForm" action="Tricount/addTemplate/<?=$tricount->id?>" method="post">
            Title : 
            <input class="form-control mb-2" id="title" name="title" type="text" placeholder="Title" value="<?=$title?>">
            <div class='text-danger' id='errTitle'></div>
            <div class='text-success' id='okTitle'></div>
            <?php if (count($errorsTitle) != 0): ?>
                <div class='text-danger'>
                    <ul>
                        <?php foreach ($errorsTitle as $errors): ?>
                            <li><?= $errors ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            Template items :
                <?php foreach($participants as $participant) { ?>
                    <div class="input-group mb-2 mt-2">
                        <span class="form-control" style="background-color: #E9ECEF">
                            <input type="checkbox" name="checkboxParticipants[]" value="<?=$participant->id?>" checked>
                        </span>  
                        <span class="input-group-text w-75" style="background-color: #E9ECEF"><?=$participant->full_name?></span>
                        <input class="form-control" type="number" min="0" name="weight[]" value="1">
                    </div>
                <?php } ?>
                <?php if (count($errorsCheckboxes) != 0): ?>
                        <div class='text-danger'>
                            <ul>
                            <?php foreach ($errorsCheckboxes as $errors): ?>
                                <li><?= $errors ?></li>
                            <?php endforeach; ?>
                            </ul>
                        </div>
                <?php endif; ?>
        </form>
        </div>
    </body>
</html>