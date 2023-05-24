<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title><?= $template->title?> -> Edit template</title>
        <base href="<?= $web_root ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="lib/jquery-3.6.4.js" type="text/javascript"></script>
        <script src="lib/just-validate-4.2.0.production.min.js" type="text/javascript"></script>
        <script src="lib/sweetalert2@11.js"></script>
        <script>
            var templateId = <?= $template->id ?>;
            let title,errTitle,okTitle,errWeights,okWeights;
            var justvalidate = "<?= $justvalidate?>";
            let sweetalert = "<?= $sweetalert?>";
            let checkboxes_count;
            let template_name_available;
            let data_changed = false;
            let ini_title = "<?=$title?>";
            let tricountId = "<?=$tricountId?>";


            function checkTitle(){
                let ok = true;
                errTitle.html("");

                if(title.val().trim().length === 0){
                    errTitle.append("<p>Title cannot be empty.</p>");
                    ok = false;
                }
                else {
                    let regex = /^(?!\s*$)[\S\s]{3,16}$/;
                    let titleValue = title.val().replace(/\s/g, ''); 
                    if (!regex.test(titleValue)) {
                        errTitle.append("<p>Title length must be between 3 and 16.</p>");
                    verification = false;
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
                    okTitle.html("<p>Looks good</p>");
                    title.attr("class", "form-control mb-2 is-valid");
                }else{
                    okTitle.html("");
                    title.attr("class", "form-control mb-2 is-invalid");
                }
            }

            function checkWeight(){
                let ok = true;
                $("input[type='number']").on("input", function(){
                    var checkboxes = $("input[type='checkbox']").map(function(){
                        return this.id;
                    }).get();
                    errWeights.html("");
                    okWeights.html("<p>Looks good</p>");
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
                        errWeights.html("");
                       var checkboxes_count = checkboxes.length;
                    for (var i = 0; i<checkboxes.length; ++i){
                        var checkbox= $("#" + checkboxes[i]);
                        
                        var weight = $("#" + checkboxes[i]+  "_weight");
                        var weightval = weight.val();

                        if(checkbox.prop("checked")==false){
                            weight.val("0");
                            checkboxes_count--;
                        }
                        if(checkbox.prop("checked")==true){
                            if(weight.val()==="0"){
                                weight.val("1");
                            }
                            else(weight.val(weightval));
                            checkboxes_count++;
                        }
                    }
                    
                    if (checkboxes_count === 0){
                        
                     errWeights.html("You must select at least 1 participant");
                    }

            }
            function hide_php_errors(){
                $("#errTitlePhp").hide();
                $("#errCheckboxesPhp").hide();
            }

            function checkAll(){
                let ok = checkTitle();
                ok = checkWeight() && ok;
                hide_php_errors();
                return ok;
            }

            function updateDataStatus(title) {
                data_changed =  title != ini_title;
                console.log(title);
                console.log(ini_title);
            }


            

            function updateDataAfterWeightInput(){
                var checkboxes = $(".checkboxParticipant").map(function() {
                    return this.id;
                }).get();
                for(var i = 0; i<checkboxes.length; ++i){
                    var weight = $("#" + checkboxes[i] + "_weight");
                    weight.on("input", function(){
                        data_changed = updateDataStatusCheckboxes();
                    });
                }
            }

            async function deleteTemplate(){
            await $.get("Template/delete_template_service/" + templateId);
        }

            $(function() {
                title = $("#title");
                errTitle = $("#errTitle");
                okTitle = $("#okTitle");
                errWeights = $("#errWeights");
                okWeights = $("#okWeights");
                data_changed=false;

                handleCheckbox();
                checkWeight();

                $(".checkboxParticipant").change(function(){
                        handleCheckbox();
                    })
                    

                hide_php_errors();

                if(justvalidate == "off"){
                    

                    title.bind("input", checkTitle);
                    title.bind("input", checkTitleExists);


                    if(title.val()!="" || title.val()!=""){
                        checkTitle();
                        checkAmount();
                    }

                    $(".checkboxParticipant").change(function(){
                        handleCheckbox();
                    })

                    checkWeight();
                }else {
                    const validation = new JustValidate('#applyTemplateForm', {
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
                                errorMessage: 'Title cannot be empty'
                            },
                            {
                                rule: 'minLength',
                                value: 3,
                                errorMessage: 'Title must be at least 3 characters'
                            },
                            {
                                rule:'maxLength',
                                value: 256,
                                errorMessage: "Title can't have more than 256 characters"
                            },
                        ],{ successMessage: 'Looks good'});
                        
                        
                    validation
                        .onSuccess(function(event) {
                            event.target.submit();
                        });
                        $("input[name='weight[]']").on('input change', function(){
                            setTimeout(function() {
                                validation.revalidateGroup('#checkboxes');
                        }, 100);
                        });
                }

                if(sweetalert == "on"){
                    title.on("input", function() {
                        updateDataStatus(title.val());
                        console.log("je change en " + data_changed);
                    });
                    $(".checkboxParticipant").change(function() {
                        updateDataStatusCheckboxes();
                    });

                    $("#btnCancel").click(function(event){
                        if(data_changed){
                            event.preventDefault();
                            Swal.fire({
                                title: 'Unsaved changes !',
                                text: 'Are you sure you want to leave this form ? Changes you made will not be saved.',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'Leave page'
                            }).then((result) => {
                                if(result.isConfirmed){
                                    window.location.href="Tricount/show_templates/" + tricountId;
                                }
                            })
                        }
                    });

                    $("#btnDelete").click(function(event){
                        console.log(data_changed);
                    event.preventDefault();
                    Swal.fire({
                        title: "Are you sure ?",
                        icon: 'warning',
                        html: 'Do you really want to delete this template ?<br><br> This process cannot be undone.',
                        showCancelButton: true,
                        cancelButtonColor: '#d33',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if(result.isConfirmed){
                            deleteTemplate();
                            Swal.fire({
                                title: 'Deleted',
                                icon: 'success',
                                text: 'This template has been deleted.'
                            }).then((result) => {
                                if(result.isConfirmed){
                                    window.location.href="Tricount/show_templates/" + tricountId;
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
        <div class="pt-2 ps-3 pe-3 pb-2 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD">
            <a href="Tricount/show_templates/<?=$tricount->id?>" class="btn btn-outline-danger" id="btnCancel">Cancel</a>
            <b><?=$tricount->title?> &#8594; Edit Template</b>
            <input type="submit" class="btn btn-primary" form="applyTemplateForm" value="Save">
        </div>
        <div class="container min-vh-100 pt-2">
            <form id="applyTemplateForm" action="Template/edit_template/<?=$tricount->id?>/<?=$template->id?>" method="post" onsubmit="return checkAll();">
                Title : 
                <input class="form-control mb-2" id="title" name="title" type="text" value="<?=$title?>" placeholder="Title">
                <div class='text-danger' id='errTitle'></div>
                <div class='text-success' id='okTitle'></div>
                <?php if (count($errorsTitle) != 0): ?>
                    <div class='text-danger' id="errTitlePhp">
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
                                class="checkboxParticipant"
                                name="checkboxParticipants[]" 
                                id="<?=$participants_and_weights[$i][0]->id?>" 
                                value ="<?=$participants_and_weights[$i][0]->id?>" 
                                <?= $checkbox_checked[$i] ?>
                            >
                        </span>
                        <span class="input-group-text w-75" style="background-color: #E9ECEF"><?=$participants_and_weights[$i][0]->full_name?></span>
                        <input class="form-control" type="number" min="0" name="weight[]" id="<?=$participants_and_weights[$i][0]->id?>_weight" value="<?=$participants_and_weights[$i][1]?>" oninput="if(this.value < 0) this.value = 0">
                    </div>
                <?php } ?> 
                <div class='text-danger' id='errWeights'></div>
                <div class='text-success' id='okWeights'></div>
                <?php if (count($errorsCheckboxes) != 0): ?>
                    <div class='text-danger' id="errCheckboxesPhp">
                            <ul>
                            <?php foreach ($errorsCheckboxes as $errors): ?>
                                <li><?= $errors ?></li>
                            <?php endforeach; ?>
                            </ul>
                        </div>
                <?php endif; ?>
            </form>
            <a href="Template/deleteTemplate/<?=$tricount->id?>/<?=$template->id?>" class="btn btn-danger w-100" id="btnDelete">Delete Template</a> 
        </div>
    </body>
</html>