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
        let title ;
        let errorTitle;
        let description;
        let errorDescription;
        
        let subsJson = <?=$subs_json?>;
        let notSubJson = <?=$not_subs_json?>;
        
        let targetSubToAdd;
        let targetSubToDelete;

        let tricountId= <?=$tricount->id?>;
        let userId= <?=$user->id?>;
        let listOfSubs;
        let selectNotSubs;


        function checkTitle(){
                let verification= true;
                errorTitle.html("");
                if(title.val().length === 0){
                    errorTitle.append("<p>Title cannot be empty.</p>");
                    verification=false;
                }
                else {
                    let regex = /^(?!\s*$)[\S\s]{3,16}$/;
                    let titleValue = title.val().replace(/\s/g, ''); 
                    if (!regex.test(titleValue)) {
                        errorTitle.append("<p>Title length must be between 3 and 16.</p>");
                    verification = false;
                    }

                }
            
                
                return verification; 
            
            }

            function checkDescription(){
                let verification= true;
                errorDescription.html("");
                
                if(description.val().length>0){
                    if(description.val().length<3 || description.val().length>16){
                        errorDescription.append("<p>Description length must be between 3 and 16.</p>");
                        verification=false;
                        
                        
                    }
                    changeDescriptionView();
                }
                changeDescriptionView();
                return verification ;
            }
            async function checkTitleExists(){
               
               const data = await $.post("tricount/tricount_exists_service/", {newTitle : title.val()},null, "json");
               if(data){
                   errorTitle.append("<p>Title already exists. please choice another</p>");
               }
               changeTitleView();

           }

           function changeTitleView(){
               if(errorTitle.text() == ""){
                   $("#verificationTitle").html(" • Looks good");
                   $("#title").attr("class","form-control mb-2 is-valid");
               }else{
                   $("#verificationTitle").html("");
                   $("#title").attr("class", "form-control mb-2 is-invalid");
               }
           }

           function changeDescriptionView(){
               if(errorDescription.text()==""){
                   $("#verificationDescription").html(" • Looks good");
                   $("#description").attr("class","form-control mb-2 is-valid");
               }else{
                   $("#verificationDescription").html("");
                   $("#description").attr("class", "form-control mb-2 is-invalid");
               }
               
           }


           function checkAll(){
               let verification = checkTitle();
               verification = checkDescription() && verification; 
               return verification;
           }


        $(function(){
            listOfSubs = $('#subscription');

            title = $("#title");
            errorTitle = $("#errorTitle");
            description = $("#description");
            errorDescription = $("#errorDescription");

            title.bind("input", checkTitle);
            title.bind("input", checkTitleExists);
            description.bind("input", checkDescription);
            displaySubs();
            displayNotSubs();
            hideSelectNotSubsIfNonSubJsonIsEmty();
            $("input:text:first").focus();

            $('#saveButton').attr('onclick', 'saveAll()');

        });


        function displaySubs(){
            listOfSubs = $('#subscription');

            html='<ul id="subscription" class="list-group p-1 ms-2 me-2 mb-2">';


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
                        html += "<li class='list-group-item d-flex justify-content-between'><p>" + sub.full_name + " </p>"
                        html += "<p><a style='float:right'><i onclick='removeParticipant(" + sub.id + ",\"" + sub.full_name + "\")' class='fa-regular fa-trash-can fa-xl'></i></a></p></li>";
                    }
                }
            }

            html+='</ul>'
            listOfSubs.html(html);
        }

        function displayNotSubs(){
            selectNotSubs = $('#add_subscription_selectdiv');

            html='<div class="input-group p-1 ms-2 me-2 mb-2"> <select id="add_subscription_select" class="form-select">'+
                '<option value="" selected disabled hidden>--Add a new subscriber--</option>';
            
                for (let user of notSubJson) {
                
                // Convertir l'objet JSON en une chaîne de caractères JSON
 
                var value = JSON.stringify({ id: user.id, full_name: user.full_name, has_paid: false, is_creator: false });
  
                 // Stocker la valeur de l'objet JSON dans la balise option

                html += '<option value=\'' + value + '\'>' + user.full_name + '</option>';
                }

                html += '</select>';
            

            html+='<input class="me-3 btn btn-primary" type="button" onclick="addParticipant()"  value="Add"></div>';

            selectNotSubs.html(html);

        }

        function addParticipant(){
            updateTargetSubToAdd();
            if(targetSubToAdd!=null){
                addTargetToSubs(targetSubToAdd);
                deleteFromNotSubs(targetSubToAdd.id);
                sortByName(subsJson);
                displaySubs();
                displayNotSubs();

                hideSelectNotSubsIfNonSubJsonIsEmty();
            }
            
        }

        function hideSelectNotSubsIfNonSubJsonIsEmty(){
            if(checkIfNonSubJsonIsEmpty()){
                selectNotSubs.hide();
            }
        }

        function removeParticipant(id,full_name){
            let targetSub = {
                "id" : id,
                "full_name" : full_name,
                "has_paid" : false,
                "is_creator" : false
            };
            
            deleteFromSubs(id);
            addToNonSubs(targetSub);
            sortByName(notSubJson);
            displaySubs();
            displayNotSubs();

            selectNotSubs.show();

  
        }

        function checkIfNonSubJsonIsEmpty(){
            return notSubJson.length === 0;
        }

        function updateTargetSubToAdd(){
            selectNotSubs = $('#add_subscription_select');
            targetSubToAdd = JSON.parse(selectNotSubs.val());
        }

        function addTargetToSubs(){
            subsJson.push(targetSubToAdd);
        }

        function addToNonSubs(target){
            notSubJson.push(target);
        }

        function deleteFromNotSubs(id){
            for (let i = 0; i < notSubJson.length; i++) {
                if (notSubJson[i].id === id) {
                    notSubJson.splice(i, 1);
                    break;
                }
            }
        }

        function deleteFromSubs(id){
            for (let i = 0; i < subsJson.length; i++) {
                if (subsJson[i].id === id) {
                    subsJson.splice(i, 1);
                    break;
                }
            }
        }

        function saveSub(){

            for (let user of subsJson) {
                console.log("hey");
                $.post('Tricount/add_subscriber_service/'+tricountId, { userId : user.id}, function(response) {
                    // La méthode a été appelée avec succès et le résultat est retourné dans 'response'
                    console.log(response);
                }).fail(function(xhr, status, error) {
                    // Une erreur s'est produite lors de l'appel de la méthode
                    console.log('Erreur : ' + error);
                });
            }
        }

        function saveUnsub(){

            for (let user of notSubJson) {
                console.log("hey");
                $.post('Tricount/remove_subscriber_service/'+tricountId, { userId : user.id}, function(response) {
                    // La méthode a été appelée avec succès et le résultat est retourné dans 'response'
                    console.log(response);
                }).fail(function(xhr, status, error) {
                    // Une erreur s'est produite lors de l'appel de la méthode
                    console.log('Erreur : ' + error);
                });
            }
        }

        function saveAll(){
            saveSub();
            saveUnsub();
        }

        
        function sortByName(jsonArray) {
            jsonArray.sort(function(a, b) {
                var nameA = a.full_name.toUpperCase(); // convertir le nom en majuscules pour la comparaison
                var nameB = b.full_name.toUpperCase();

                if (nameA < nameB) {
                    return -1; // a vient avant b dans l'ordre alphabétique
                }
                if (nameA > nameB) {
                    return 1; // a vient après b dans l'ordre alphabétique
                }
                    return 0; // les noms sont égaux
            });
        }








    </script>
</head>

<body>


    <div class="pt-3 ps-3 pe-3 pb-3 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD">
        <a href="Tricount/showTricount/<?=$tricount->id?>/" class="btn btn-outline-danger">Back</a>
        <?=$tricount->title?> &#8594; Edit
        <input id=saveButton type="submit" class="btn btn-primary" form="editTricountForm" name="saveButton" value="Save">
    </div>


    <h1 class= "p-1 ms-2 me-2 mb-2">Settings</h1>
        <form action="Tricount/EditTricount/<?= $tricount->id?>" id="editTricountForm" method="post">
            <div class="form-group p-1 ms-2 me-2 mb-2">
                    <label class="pb-1">Title :</label>
                    <input class="form-control" type="text" id="title" name="title" value="<?= $title?>">
                    <div class = "text-danger" id = "errorTitle"></div> 
                    <div class='text-success' id='verificationTitle'></div>
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
                    <div class = "text-danger" id = "errorDescription"></div> 
                    <div class='text-success' id='verificationDescription'></div>

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
        <div id="add_subscription_selectdiv" value ="hey" class="input-group p-1 ms-2 me-2 mb-2">
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