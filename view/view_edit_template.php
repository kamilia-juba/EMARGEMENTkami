<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title><?= $template->title?> -> Edit template</title>
        <base href="<?= $web_root ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="lib/jquery-3.6.4.js" type="text/javascript"></script>
        <script>
            var templateId = <?= $template->id ?>;
            let title,errTitle,okTitle,errWeights,okWeights;

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
                const data = await $.post("template/template_title_other_exists_service", {newTitle : title.val(), templateId : templateId }, null, "json");
                if(data){
                    errTitle.html("<p>There's already an existing template with this title. Choose another title</p>");
                }
                changeTitleView();
            }

            function changeTitleView(){
                if (errTitle.text() == ""){
                    okTitle.html("Looks good");
                    title.attr("class", "form-control mb-2 is-valid");
                }else{
                    okTitle.html("");
                    title.attr("class", "form-control mb-2 is-invalid");
                }
            }

            $(function() {
                title = $("#title");
                errTitle = $("#errTitle");
                errWeights = $("#errWeights");
                okTitle = $("#okTitle");
                okWeights = $("#okWeights");

                title.bind("input", checkTitle);
                title.bind("input", checkTitleExists);

                $("input:text:first");
            })
        </script>
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
                <input class="form-control mb-2" id="title" name="title" type="text" value="<?=$title?>" placeholder="Title">
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
            <a href="Template/deleteTemplate/<?=$tricount->id?>/<?=$template->id?>" class="btn btn-danger w-100">Delete Template</a> 
        </div>
    </body>
</html>