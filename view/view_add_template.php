<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Add Template</title>
        <base href="<?= $web_root ?>"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    </head>
 

    <body>
        
        <div class="pt-2 ps-3 pe-3 pb-2 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD">
            <a href="Tricount/showTemplates/<?=$tricount->id?>" class="btn btn-outline-danger" name="buttonCancel">Cancel</a>
            <?=$tricount->title?> &#8594 New template
            <form id="addtemplateForm" action="Tricount/addTemplate/<?=$tricount->id?>" method="post">
            <input type="submit"class="btn btn-primary" value="Save" form="addtemplateForm"></a><br>
        </div> 
        <div class="container">
            Title : 
            <input class="form-control mb-2" id="title" name="title" type="text" placeholder="Title">
            Template items :
                <?php foreach($participants as $participant) { ?>
                    <div class="input-group mb-2 mt-2">
                        <span class="form-control" style="background-color: #E9ECEF">
                        
                        <input type="checkbox"name="checkboxParticipants[]"value="<?=$participant->id?>"checked>
                            
                        
                        
                        </span>  
                        <span class="input-group-text w-75" style="background-color: #E9ECEF"><?=$participant->full_name?></span>
                        <input class="form-control" type="number" min="0" name="weight[]" value="1">
                    </div>
                <?php } ?>
        <?php if (count($errors) != 0): ?>
            <div class="text-danger">
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
            </div>
         <?php endif; ?> 
        </div>
    </body>
</html>