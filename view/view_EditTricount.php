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
    <script src="lib/just-validate-4.2.0.production.min.js" type="text/javascript"></script>
    <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    <script src="lib/sweetalert2@11.js"></script>
    <script>
        let title ;
        let errorTitle;
        let description;
        let errorDescription;
        let originalTitle;
        
        let subsJson = <?=$subs_json?>;
        let notSubJson = <?=$not_subs_json?>;
        let sweetalert = "<?= $sweetalert?>";
        let targetSubToAdd;
        let targetSubToDelete;

        let tricountId= <?=$tricount->id?>;
        let userId= <?=$user->id?>;
        let listOfSubs;
        
        let selectNotSubs;

        let ini_title = "<?= $title ?>";
        let ini_description = "<?= $description ?>";
        
        var justvalidate = "<?= $justvalidate?>";
        let titleAvailable;


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
                    let regex = /^(?!\s*$)[\S\s]{3,16}$/;
                    let descriptionValue = description.val().replace(/\s/g, ''); 
                    if (!regex.test(descriptionValue)) {
                        errorDescription.append("<p>Title length must be between 3 and 16.</p>");
                    verification = false;
                    }
                    changeDescriptionView();
                }
                changeDescriptionView();
                return verification ;
            }
            async function checkTitleExists(){
               
               const data = await $.post("tricount/tricount_exists_service/", {newTitle : title.val()},null, "json");
               if(data && originalTitle.trim()!=title.val().trim()){
                   errorTitle.append("<p>Title already exists. please choice another</p>");
               }
               changeTitleView();

           }

           function changeTitleView(){
               if(errorTitle.text() == ""){
                   $("#verificationTitle").html("Looks good");
                   $("#title").attr("class","form-control mb-2 is-valid");
               }else{
                   $("#verificationTitle").html("");
                   $("#title").attr("class", "form-control mb-2 is-invalid");
               }
           }

           function changeDescriptionView(){
               if(errorDescription.text()==""){
                   $("#verificationDescription").html("Looks good");
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

        function hideTitles(){
            $(":header").hide();
        }

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

        function saveSub() {
            subsJson.forEach(function(user) {
                $.ajax({
                url: 'Tricount/add_subscriber_service/' + tricountId,
                type: 'POST',
                data: { userId: user.id },
                });
            });
            }

        function saveUnsub() {
            notSubJson.forEach(function(user) {
                $.ajax({
                url: 'Tricount/remove_subscriber_service/' + tricountId,
                type: 'POST',
                data: { userId: user.id },
                });
            });
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



        async function deleteTricount(){
            await $.get("Tricount/delete_tricount_service" + tricountId)
        }
        $(function(){
                $("#erreurphp").hide();
                hideTitles();
                title = $("#title");
                originalTitle=title.val();
                errorTitle = $("#errorTitle");
                description = $("#description");
                errorDescription = $("#errorDescription");
             
                if (justvalidate == "off") {
                    listOfSubs = $('#subscription');




                    if(title.val()!=""){
                            checkTitle();
                    }
                   


                    title.bind("input", checkTitle);
                    title.bind("input", checkTitleExists);
                    description.bind("input", checkDescription);
                    displaySubs();
                    displayNotSubs();
                    hideSelectNotSubsIfNonSubJsonIsEmty();
                    $("input:text:first").focus();

                    $('#saveButton').attr('onclick', 'saveAll()');
                } else {
                    const validation = new JustValidate('#editTricountForm', {
                        validateBeforeSubmitting: true,
                        lockForm: true,
                        focusInvalidField: false,
                        successLabelCssClass: 'valid-feedback',
                        errorLabelCssClass: 'invalid-feedback',
                        errorFieldCssClass: 'is-invalid',
                        successFieldCssClass: 'is-valid',
                    });

                    validation
                        .addField('#title', [
                            {
                                rule: 'required',
                                errorMessage: 'Field is required'
                            },
                            {
                                rule: 'minLength',
                                value: 3,
                                errorMessage: 'Minimum 3 characters'
                            },
                            {
                                rule: 'maxLength',
                                value: 16,
                                errorMessage: 'Maximum 16 characters'
                            },
                           
                        ], { successMessage: 'Looks good !' })

                        .addField('#description', [
                            {
                                rule: 'minLength',
                                value: 3,
                                errorMessage: 'Minimum 3 characters'
                            },
                            {
                                rule: 'maxLength',
                                value: 16,
                                errorMessage: 'Maximum 16 characters'
                            },
                        ], { successMessage: 'Looks good !' })

                        .onValidate(async function(event) {
                            titleAvailable = await $.post("tricount/tricount_exists_service/", {newTitle: $("#title").val()},null,"json");
                            if (titleAvailable){
                                this.showErrors({ '#title': 'Title already exists' });
                            }   
                        })
                        
                        .onSuccess(function(event) {
                           
                                event.target.submit(); //par défaut le form n'est pas soumis
                        })

                    $("input:text:first").focus();
                }
                if(sweetalert == "on"){
                            title.on("input", function() {
                                data_changed = (title.val() != ini_title) || (description.val() != ini_description);
                            });

                        $('#cancelBtn').click(function() {
                            if(data_changed){
                                event.preventDefault()
                                Swal.fire({
                                    title: 'Unsaved changes !',
                                    text: 'Are you sure you want to leave this form ? Changes you made will not be saved.',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Leave page',
                                    cancelButtonText: 'cancel'
                               }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = "Tricount/yourTricounts";
                                        }
                                    });
                                }
                            });
                
                            +                $("#btnDelete").click(function(event){
                    event.preventDefault();
                    Swal.fire({
                        title: "Are you sure ?",
                        icon: 'warning',
                        html: 'Do you really want to delete this tricount ?<br><br> This process cannot be undone.',
                        showCancelButton: true,
                        cancelButtonColor: '#d33',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if(result.isConfirmed){
                            deleteOperation();
                            Swal.fire({
                                title: 'Deleted',
                                icon: 'success',
                                text: 'This tricount has been deleted.'
                            }).then((result) => {
                                if(result.isConfirmed){
                                    window.location.href="Tricount/showTricount/" + tricountId;
                                }
                            })
                        }
                    })
                })
                }


            });

    </script>
</head>

<body>


    <div class="pt-3 ps-3 pe-3 pb-3 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD">
        <a href="Tricount/showTricount/<?=$tricount->id?>/" id="cancelBtn" class="btn btn-outline-danger">Back</a>
        <?=$tricount->title?> &#8594; Edit
        <input id=saveButton type="submit" class="btn btn-primary" form="editTricountForm" name="saveButton" value="Save">
    </div>


    <h1 class= "p-1 ms-2 me-2 mb-2">Settings</h1>
        <form action="Tricount/edit_tricount/<?= $tricount->id?>" id="editTricountForm" method="post">
            <div class="form-group p-1 ms-2 me-2 mb-2">
                    <label class="pb-1">Title :</label>
                    <input class="form-control" type="text" id="title" name="title" value="<?= $title?>">
                    <div class = "text-danger" id = "errorTitle"></div> 
                    <div class='text-success' id='verificationTitle'></div>
                    <?php if (count($errorsTitle) != 0): ?>
                    <div class='text-danger' id="erreurphp">
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
                    <div class='text-danger' id="erreurphp">
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
                        <a href= \"Tricount/delete_participant/".$tricount->id."/".$participant->id. "\"></a>
                        </p></li>";
                    }
                }else
                    if($participant->has_already_paid($tricount)||$tricount->has_already_paid($participant)){
                        echo "<li class='list-group-item d-flex justify-content-between'><p>".$participant->full_name."</p></li>";
                    }
                    else{ 
                        echo "<li class='list-group-item d-flex justify-content-between'><p>".$participant->full_name.'</p>';
                        echo "<p><a href='Tricount/delete_participant/".$tricount->id."/".$participant->id."' class='float-end'><img src='ressources/images/trash-can.png' alt='Delete participant' style='width:25px;height:25px;'></a></p></li>";
                    }
            }
        ?>
    </ul>



    <form action="Tricount/add_participant/<?= $tricount->id?>" id="addParticipantFrom" method="post">       
        <div id="add_subscription_selectdiv" class="input-group p-1 ms-2 me-2 mb-2">
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
            <a href="Tricount/show_templates/<?=$tricount->id?>" class="btn btn-success col-11">Manage repartition templates</a>
            <p></p>
            <a href ="Tricount/delete_tricount/<?=$tricount->id?>" class="btn btn-danger col-11">Delete Tricount</a>
            <br>
            </div>
        </footer>
        <!-- <a href="Tricount/show_templates/<?=$tricount->id?>"><button type="button" name="manageTemplates">Manage repartition templates</button></a>
        <a href="Tricount/delete_tricount/<?=$tricount->id?>"><button type="button" name="DeleteTricount">Delete Tricount</button></a></body> -->
</body>
</html>