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
        <script>
            var tricountId = <?= $tricount->id?>;
            let title, errTitle, errWeights;

            function checkTitle(){
                let ok = true;
                errTitle.html("");

                if(title.val().trim().length === 0){
                    errTitle.append("<p>Title cannot be empty.</p>");
                    ok = false;
                }else{
                    if(!(/^.{3,255}$/).test(title.val())){
                        errTitle.append("<p>Title must have at least 3 characters.</p>");
                        ok = false;
                    }
                }
                changeTitleView();
                return ok;
            }

            async function checkTitleExists(){
                const data = await $.post("template/template_exists_service", {newTitle : title.val(), tricountId : tricountId},null, "json");
                if(data){
                    errTitle.html("<p>There's already an existing template with this title. Choose another title</p>");
                }
                changeTitleView();
            }

            function changeTitleView(){
                if(errTitle.text() == ""){
                    $("#okTitle").html("Looks good");
                    $("#title").attr("class","form-control mb-2 is-valid");
                }else{
                    $("#okTitle").html("");
                    $("#title").attr("class", "form-control mb-2 is-invalid");
                }
            }

            function checkWeight(){
                let ok = true;
                $("input[type='number']").on("input", function(){
                    var checkboxes = $("input[type='checkbox']").map(function(){
                        return this.id;
                    }).get();
                    errWeights.html("");
                    okWeights.html("Looks good");
                    for(var i=0; i<checkboxes.length; ++i){
                        var checkbox = $("#" + checkboxes[i]);
                        var weight = $("#" + checkboxes[i] + "_weight");
                        if(weight.val() <= "0"){
                            checkbox.prop("checked", false);
                        }else{
                            checkbox.prop("checked", true);
                        }
                        if(weight.val() === ""){
                            errWeights.html("<p>Weights cannot be empty</p>");
                            ok = false;
                            okWeights.html("");
                        }
                    }
                })
                return ok;
            }

            function handleCheckbox(){
                var checkboxes = $(".checkboxParticipant").map(function(){
                            return this.id;
                        }).get();
                       
                    for (var i = 0; i<checkboxes.length; ++i){
                        var checkbox= $("#" + checkboxes[i]);
                        
                        var weight = $("#" + checkboxes[i]+  "_weight");

                        if(checkbox.prop("checked")==false){
                            weight.val("0");
                        }
                        if(checkbox.prop("checked")==true){
                            weight.val("1");
                        }
                    }
            }


            function checkAll(){
                let ok = checkTitle();
                ok = checkWeight() && ok;
                return ok;
            }

            $(function(){
                title = $("#title");
                errTitle = $("#errTitle");
                errWeights = $("#errWeights");
                okWeights = $("#okWeights");

                title.bind("input", checkTitle);
                title.bind("input", checkTitleExists);

                $(".checkboxParticipant").change(function(){
                    handleCheckbox();
                });

                checkWeight();

                $("input:text:first").focus();
            });
        </script>
    </head>
 

    <body>
        
        <div class="pt-2 ps-3 pe-3 pb-2 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD">
            <a href="Tricount/showTemplates/<?=$tricount->id?>" class="btn btn-outline-danger" >Cancel</a>
            <?=$tricount->title?> &#8594; New template
            <input type="submit" class="btn btn-primary" value="Save" form="addtemplateForm">
        </div> 
        <div class="container">
        <form id="addtemplateForm" action="Tricount/addTemplate/<?=$tricount->id?>" method="post" onsubmit="return checkAll();">
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
                <?php foreach($participants as $participant):  ?>
                    <div class="input-group mb-2 mt-2">
                        <span class="form-control" style="background-color: #E9ECEF">
                            <input class = "checkboxParticipant" type="checkbox" name="checkboxParticipants[]" id="<?=$participant->id?>" value="<?=$participant->id?>" checked>
                        </span>  
                        <span class="input-group-text w-75" style="background-color: #E9ECEF"><?=$participant->full_name?></span>
                        <input class="form-control" type="number" min="0" name="weight[]" id="<?=$participant->id?>_weight" value="1">
                    </div>
                <?php endforeach; ?>
                <div class='text-danger' id='errWeights'></div>
                <div class='text-success' id='okWeights'></div>
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