<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body>


    <div class="pt-3 ps-3 pe-3 pb-3 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD">
        <a href="Tricount/showTricount/<?=$tricount->id?>/" class="btn btn-outline-danger">Back</a>
        <?=$tricount->title?> &#8594; Edit
        <input type="submit" class="btn btn-primary" form="editTricountForm" name="saveButton" value="Save">
    </div>


    <h1 class= "p-1 ms-2 me-2 mb-2">Settings</h1>
        <form action="Tricount/EditTricount/<?= $tricount->id?>" id="editTricountForm" method="post">
            <div class="form-group p-1 ms-2 me-2 mb-2">
                    <label class="pb-1">Title :</label>
                    <input class="form-control "type="text" id="title" name="title" value="<?= $tricount ->title?>">
            </div>
            <div class="form-group p-1 ms-2 me-2 mb-2">
                    <label class="pb-1">Descripition (optional) :</label>
                    <input class="form-control" type="text" id="description" name="description" value=<?= $tricount ->description?>></td>
            </div>
        </form>




    <h2 class= "p-1 ms-2 me-2 mb-2">Subscriptions</h2>


    <ul class="list-group p-1 ms-2 me-2 mb-2">
        <?php foreach($participants as $participant){
                if($participant->id==$user->id){
                    if($participant->has_already_paid($tricount->id)|| $tricount->has_already_paid($participant->id)){// a changer si on ne peut supprimer le createur
                        echo "<li class='list-group-item d-flex justify-content-between'><p>".$participant->full_name." (creator)</p></li>";
                    }
                    else{ 
                        echo "<li class='list-group-item d-flex justify-content-between'><p>".$participant->full_name." (creator)
                        <a href= \"Tricount/deleteParticipant/".$tricount->id."/".$participant->id. "\"></a>
                        </p></li>";
                    }
                }else
                    if($participant->has_already_paid($tricount->id)||$tricount->has_already_paid($participant->id)){
                        echo "<li class='list-group-item d-flex justify-content-between'><p>".$participant->full_name."</p></li>";
                    }
                    else{ 
                        echo "<li class='list-group-item d-flex justify-content-between'><p>".$participant->full_name."
                        <a href= \"Tricount/deleteParticipant/".$tricount->id."/".$participant->id. "\"> <button type=\"button\" name = \"buttonBack\">Poubelle</button></a></p></li>";
                }
            }
        ?>
    </ul>


    <form action="Tricount/add_participant/<?= $tricount->id?>" id="addParticipantFrom" method="post">       
        <div class="input-group p-1 ms-2 me-2 mb-2">
            <select class="form-select" name="participant" id="participant">
                <option value="" selected disabled hidden>--Add a new subscriber--</option>
                <?php foreach ($notSubParticipants as $user){ ?>
                        <option value="<?=$user->id?>"> <?=$user->full_name?> </option>
                <?php } ?>
            </select>
            <input class="me-3 btn btn-primary" type="submit"  value="Add" form="addParticipantFrom">
        </div>
    </form>  

    

        <?php if (count($errors) != 0): ?>
                <div class="text-danger p-1 ms-2 me-2 mb-2">
                    <ul class="list-inline ">
                    <?php foreach ($errors as $errors): ?>
                            <li><?= $errors ?></li>
                    <?php endforeach; ?>
                    </ul>
                </div>
        <?php endif; ?>
        </div> 


        <footer>    
            <div class="text-center">
            <a href="Tricount/showTemplates/<?=$tricount->id?>" class="btn btn-success col-11">Manage repartition templates</a>
            <p></p>
            <a href ="Tricount/delete_tricount/<?=$tricount->id?>" class="btn btn-danger col-11">Delete Tricount</a>
            <br>
            </div>
        </footer>
        <!-- <a href="Tricount/showTemplates/<?=$tricount->id?>"><button type="button" name="manageTemplates">Manage repartition templates</button></a>
        <a href="Tricount/delete_tricount/<?=$tricount->id?>"><button type="button" name="DeleteTricount">Delete Tricount</button></a></body> -->
</body>
</html>