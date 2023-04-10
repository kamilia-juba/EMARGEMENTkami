<!DOCTYPE html>
<html lang="fr">
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
           
        <meta charset="UTF-8">
        <title>addtricount</title>
        <base href="<?= $web_root ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="lib/jquery-3.6.3.min.js" type="text/javascript"></script>
        <script>
            
            let title ;
            let errorTitle;
            let description;
            let errorDescription;

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

                title.bind("input", checkTitle);
                title.bind("input", checkTitleExists);
                description.bind("input", checkDescription);
                

                $("input:text:first").focus();
            }

            );


        </script>
    </head>

    <body>
    <div class="pt-3 ps-3 pe-3 pb-3 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD">   
            <a href = "Tricount/yourTricounts/"  class="btn btn-outline-danger" >Cancel</a>
            Tricount &#8594; add    
       
            <button form="addTricount" class="btn btn-primary" type="submit">Save</button>
    </div> 
    <form id="addTricount" action="Tricount/addtricount" method="post" onsubmit="return checkAll();">
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