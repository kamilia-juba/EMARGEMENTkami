<!DOCTYPE html>
<html lang="fr">
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
           
        <meta charset="UTF-8">
        <title>addtricount</title>
        <base href="<?= $web_root ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="lib/jquery-3.6.3.min.js" type="text/javascript"></script>
        <script src="lib/just-validate-4.2.0.production.min.js" type="text/javascript"></script>
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <script src="lib/just-validate-plugin-date-1.2.0.production.min.js" type="text/javascript"></script>
        <script src="lib/sweetalert2@11.js"></script>
        <script>
            
            let title ;
            let errorTitle;
            let description;
            let errorDescription;
            var justvalidate = "<?= $justvalidate?>";
            let sweetalert = "<?= $sweetalert?>";
            let data_changed = false;
            let ini_title = "<?= $title ?>";
            let ini_description = "<?= $description ?>";
            let titleAvailable ;
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

            function hide_php_errors(){
                $("#errorsTitlePhp").hide();
                $("#errorsDescPhp").hide();
            }

            $(function(){
                hide_php_errors();

                title = $("#title");
                errorTitle = $("#errorTitle");
                description = $("#description");
                errorDescription = $("#errorDescription");

                if (justvalidate == "off") {


                    title.bind("input", checkTitle);
                    title.bind("input", checkTitleExists);
                    description.bind("input", checkDescription);

                    $("#addTricount").submit(function(){

                        return checkAll();            
                    });

                    $("input:text:first").focus();
                } else {
                    const validation = new JustValidate('#addTricount', {
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
                            if(!titleAvailable){
                                event.target.submit(); //par défaut le form n'est pas soumis
                            }
                        })



                    $("input:text:first").focus();
                }
                      if(sweetalert == "on"){
                            title.on("input", function() {
                                data_changed = (title.val() != ini_title) || (description.val() != ini_description);
                            });

                            description.on("input", function(){
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
                        }
                });

        </script>
    </head>

    <body>
    <div class="pt-3 ps-3 pe-3 pb-3 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD">   
            <a href = "Tricount/yourTricounts/" id="cancelBtn"  class="btn btn-outline-danger" >Cancel</a>
            Tricount &#8594; add    
       
            <button form="addTricount" class="btn btn-primary" type="submit">Save</button>
    </div> 
    <form id="addTricount" action="Tricount/addtricount" method="post">
        <div class="form-group pt-3 ps-3 pe-3 pb-3">
             <label class="pb-3">Title :</label>
             <input class="form-control" id="title" name="title" type="text" size="16" value="<?= $title ?>" placeholder="Enter a title">
            <div class = "text-danger" id = "errorTitle"></div> 
            <div class='text-success' id='verificationTitle'></div>
        </div>
        <?php if (count($errorsTitle) != 0): ?>
                    <div id="errorsTitlePhp" class='text-danger'>
                        <ul>
                        <?php foreach ($errorsTitle as $errors): ?>
                            <li><?= $errors ?></li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
        <?php endif; ?>
        <div class="form-group pt-3 ps-3 pe-3 pb-3">
             <label class="pb-3">Description (optional) :</label>
             <input class="form-control" id="description" name="description" type="text" size="32" value="<?= $description ?>">
             <div class = "text-danger" id = "errorDescription"></div> 
             <div class='text-success' id='verificationDescription'></div>
        </div>
        <?php if (count($errorsDescription) != 0): ?>
                    <div id="errorsDescPhp" class='text-danger'>
                        <ul>
                        <?php foreach ($errorsDescription as $errors): ?>
                            <li><?= $errors ?></li>
                        <?php endforeach; ?>
                        </ul>
                    </div>
        <?php endif; ?>
    </form>  
    </body>
</html>