<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Edit <?=$tricount->title?></title>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/fd46891f37.js" crossorigin="anonymous"></script>
    <script src="lib/jquery-3.6.3.min.js" type="text/javascript"></script>
    <script>
        
        let subsJson = <?=$subs_json?>;
        let notSubJson = <?=$not_subs_json?>;
        
        let userId= <?=$user->id?>;
        let listOfSubs;
        let selectNotSubs;

        $(function(){
            listOfSubs = $('#subscription');
            
            displaySubs();
            displayNotSubs();
        });


        function displaySubs(){
            listOfSubs = $('#subscription');

            html='';


            for (let sub of subsJson) {
                if(sub.has_paid){
                    if(sub.is_creator){
                        html += "<li class='list-group-item d-flex justify-content-between'><p>" + sub.full_name + " (Creator)</p></li>";
                    }
                    else{
                        html += "<li class='list-group-item d-flex justify-content-between'><p>" + sub.full_name + "</p></li>";
                    }

                } else {
                    if(sub.is_creator){
                        html += "<li class='list-group-item d-flex justify-content-between'><p>" + sub.full_name + " (Creator)</p></li>";
                    }
                    else{
                        html += "<li class='list-group-item d-flex justify-content-between'><p>" + sub.full_name + "add to delete Json</p></li>";
                    }
                }
            }
            listOfSubs.html(html);
        }

        function displayNotSubs(){
            selectNotSubs = $('#add_subscription_select');

            html='<select  class="form-select" name="participant" id="participant">'+
                '<option value="" selected disabled hidden>--Add a new subscriber--</option>';
            
            for (let user of notSubJson) {
                html+="<option value=" + user.id+ ">" + user.full_name + "</option>";
            }


            html+='</select>';
            




            //html+='<input class="me-3 btn btn-primary" type="submit"  value="Add"">";

            selectNotSubs.html(html);

        }

        function addToDeleteJson(){}

        function addToAddJson(){}











    </script>
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
                    <input class="form-control" type="text" id="title" name="title" value="<?= $title?>">
                    <?php if (count($errorsTitle) != 0): ?>
                    <div class='text-danger'>
                        <ul>
                            <?php foreach ($errorsTitle as $errors): ?>
                                 <li><?= $errors ?></li>
                             <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
            </div>
            <div class="form-group p-1 ms-2 me-2 mb-2">
                    <label class="pb-1">Descripition (optional) :</label>
                    <input class="form-control" type="text" id="description" name="description" value="<?=$description?>">
                    <?php if (count($errorsDescription) != 0): ?>
                    <div class='text-danger'>
                        <ul>
                            <?php foreach ($errorsDescription as $errors): ?>
                                 <li><?= $errors ?></li>
                             <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    
            </div>
        </form>


    <h2 class= "p-1 ms-2 me-2 mb-2">Subscriptions</h2>

    <ul id="subscription" class="list-group p-1 ms-2 me-2 mb-2">
        <?php foreach($participants as $participant){
                if($participant->id==$user->id){
                    if($participant->has_already_paid($tricount)|| $tricount->has_already_paid($participant)){// a changer si on ne peut supprimer le createur
                        echo "<li class='list-group-item d-flex justify-content-between'><p>".$participant->full_name." (creator)</p></li>";
                    }
                    else{ 
                        echo "<li class=' list-group-item d-flex justify-content-between'><p>".$participant->full_name." (creator)
                        <a href= \"Tricount/deleteParticipant/".$tricount->id."/".$participant->id. "\"></a>
                        </p></li>";
                    }
                }else
                    if($participant->has_already_paid($tricount)||$tricount->has_already_paid($participant)){
                        echo "<li class='list-group-item d-flex justify-content-between'><p>".$participant->full_name."</p></li>";
                    }
                    else{ 
                        echo "<li class='list-group-item d-flex justify-content-between'><p>".$participant->full_name.'</p>';
                        echo "<p><a href= \"Tricount/deleteParticipant/".$tricount->id."/".$participant->id. "\" 'style='float:right' <i style='color:red' class='fa-regular fa-trash-can fa-xl  '> </a></p></li>";
                }
            }
        ?>
    </ul>



    <form action="Tricount/add_participant/<?= $tricount->id?>" id="addParticipantFrom" method="post">       
        <div id="add_subscription_select" class="input-group p-1 ms-2 me-2 mb-2">
            <select  class="form-select" name="participant" id="participant">
                <option value="" selected disabled hidden>--Add a new subscriber--</option>
                <?php foreach ($notSubParticipants as $user){ ?>
                        <option value="<?=$user->id?>"> <?=$user->full_name?> </option>
                <?php } ?>
            </select>
            <input class="me-3 btn btn-primary" type="submit"  value="Add" form="addParticipantFrom">
        </div>
    </form>  

    

      
         


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