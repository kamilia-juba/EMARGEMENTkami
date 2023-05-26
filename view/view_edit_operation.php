<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Operation</title>
    <base href="<?= $web_root ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="lib/jquery-3.6.3.min.js" type="text/javascript"></script>
    <script src="lib/just-validate-4.2.0.production.min.js" type="text/javascript"></script>
    <link href="css/styles.css" rel="stylesheet" type="text/css"/>
    <script src="lib/just-validate-plugin-date-1.2.0.production.min.js"></script>
    <script src="lib/sweetalert2@11.js"></script>

    <script>
        var tricountId = <?= $tricount->id?>;
        let operationId = <?= $operation->id?>;
        let title,amount, errTitle, errAmount,errWeights,okWeights;
        var justvalidate = "<?= $justvalidate?>";
        let sweetalert = "<?= $sweetalert?>";
        let checkboxes_count;
        let template_name_available;
        let data_changed = false;
        let ini_title = "<?= $operation->title ?>";
        let ini_amount= "<?= $operation->amount ?>";
        let ini_date = "<?= $operation->operation_date ?>" ;
        let ini_newTemplate = "<?= $save_template_name?>";

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


        function checkAmount() {
        let ok = true;
        errAmount.html("");

        // Supprime les espaces de la valeur d'entrée
        let inputAmount = amount.val().replace(/\s/g, '');

        // Vérifie si la valeur est vide ou non numérique
        if (inputAmount.length === 0 || !/^[0-9.,]+$/.test(inputAmount)) {
            errAmount.append("<p>Amount must be a number.</p>");
            ok = false;
        } else if (inputAmount <= 0) {
            errAmount.append("<p>Amount must be greater than 0.</p>");
            ok = false;
        }
        $("#amount").val(inputAmount);
        changeAmountView();
        return ok;
        }

        function changeTitleView(){
            if(errTitle.text() == ""){
                $("#okTitle").html("<p>Looks good</p>");
                $("#title").attr("class","form-control mb-2 is-valid");
            }else{
                $("#okTitle").html("");
                $("#title").attr("class", "form-control mb-2 is-invalid");
            }
        }            

        function changeAmountView(){
            if(errAmount.text() == ""){
                $("#okAmount").html("<p>Looks good</p>");
                $("#amount").attr("class","form-control mb-2 is-valid");
            }else{
                $("#okAmount").html("");
                $("#amount").attr("class", "form-control mb-2 is-invalid");
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


        function checkAll(){
            //return checkTitle() && checkAmount();
            let ok = checkTitle();
            ok = checkAmount() && ok;
            ok = checkWeight();
            return ok;
        }


        //-------------------------------------------------------------------------------------------------------------


        let totalAmount;
        let template_json = <?=$templates_json?> ;
        let operation =<?= $operation->id?>;

        function handleAmounts (){
            

            var checkboxes = $(".checkboxParticipant").map(function(){
                        return this.id;
                    }).get();
                    
            var sommeTotal = getTotalWeight();
            var onePartAmount = totalAmount.val() / sommeTotal;
            for (var i =0; i<checkboxes.length;++i){
                var checkbox= $("#" + checkboxes[i]);
                var amount = $("#" + checkboxes[i]+"_amount");
                var weight = $("#" + checkboxes[i]+  "_weight");
                var individualAmount = onePartAmount * weight.val();
                if(amount==null){
                    individualAmount=0;
                }
                if(weight.val()<="0"){
                    checkbox.prop("checked", false);
                }
                else{
                    checkbox.prop("checked", true);
                }
                
                
                amount.html("<span class='input-group-text ' style='background-color: #E9ECEF'>" + individualAmount.toFixed(2) + " €</span>")
            }

        }

        function getTotalWeight(){
            var checkboxes = $(".checkboxParticipant").map(function(){
                    return this.id;
                }).get();
            var somme =0;
            for (var i =0; i<checkboxes.length;++i){
                var weight = $("#" + checkboxes[i]+  "_weight");
                somme+= parseInt(weight.val(), 10) || 0;
            }
            return somme;

        }

        function handleCheckbox(){
            var checkboxes = $(".checkboxParticipant").map(function(){
                return this.id;
            }).get();
            errWeights.html("");
            checkboxes_count = checkboxes.length
            for (var i =0; i<checkboxes.length;++i){
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
        }

        function checkCheckboxesCount() {
                if (checkboxes_count === 0){
                       errWeights.html("You must select at least 1 participant");
                    }
            }

        function handleTemplates(){
            $("#applyTemplateBtn").hide();
            let applyTemplateSelect = $("#applyTemplateSelect");
            let html = '<select class="form-select"  id="applyTemplateSelect" name="repartitionTemplates" form="applyTemplateForm">';
            html += '<option value="customRepartition">No, i\'ll use custom repartition</option>';
            for(let template of template_json){
                html+='<option value="'+ template.id +'">'+ template.title +'</option>';
            }
            html+="</select>";
            applyTemplateSelect.html(html);
        }

        function applyItems(){
            var selectedTemplate = $("#applyTemplateSelect").val();
            var checkboxes = $(".checkboxParticipant").map(function(){
                        return this.id;
                    }).get();
            if(selectedTemplate != "customRepartition"){
                checkUserParticipatesTemplate(selectedTemplate);
            }else{
                for(var i =0; i<checkboxes.length;++i){
                    $("#" + checkboxes[i]).prop("checked", true);
                    $("#" + checkboxes[i] + "_weight").val(1);
                    handleAmounts();
                }
            }
        }

        function reselect_customRepartition(){
            $("#applyTemplateSelect").val("customRepartition");
        }


        async function checkUserParticipatesTemplate(template){
                    var checkboxes = $(".checkboxParticipant").map(function(){
                                return this.id;
                            }).get();
                    for(var i =0; i<checkboxes.length;++i){
                        const data = await $.post("template/user_participates_service", {userId : checkboxes[i] , templateId : template},null, "json");
                        const weight = await $.post("template/get_user_weight_service", {userId : checkboxes[i], templateId: template}, null, "json");
                        if(data){
                            $("#" + checkboxes[i]).prop("checked", true);
                            $("#" + checkboxes[i] + "_weight").val(weight);
                        }else{
                            $("#" + checkboxes[i]).prop("checked", false);
                            $("#" + checkboxes[i] + "_weight").val(0);
                        }
                        handleAmounts();
                    }
                }
        async function checkUserParticipatesOperation(operation){
            var checkboxes = $(".checkboxParticipant").map(function(){
                        return this.id;
                    }).get();
            for(var i =0; i<checkboxes.length;++i){
                const data = await $.post("operation/user_participates_service", {userId : checkboxes[i] , operationId : operation},null, "json");
                const weight = await $.post("operation/get_user_weight_service", {userId : checkboxes[i], operationId: operation}, null, "json");
                if(data){
                    $("#" + checkboxes[i]).prop("checked", true);
                    $("#" + checkboxes[i] + "_weight").val(weight);
                }else{
                    $("#" + checkboxes[i]).prop("checked", false);
                    $("#" + checkboxes[i] + "_weight").val(0);
                }
                handleAmounts();


            }
        }

        function updateDataStatus(title, amount, date){
            data_changed = updateDataStatusCheckboxes() 
                            || (title != ini_title) 
                            || (amount != ini_amount) 
                            || (date != ini_date) 
                            || ($("#newTemplateName").val() != ini_newTemplate)
                            || ($("#saveTemplateCheck").prop("checked") == true) ;
        }

        function updateDataStatusCheckboxes() {
            var data_changed = false;
            var checkboxes = $(".checkboxParticipant").map(function() {
                return this.id;
            }).get();

            var checkboxChecked = <?= json_encode($checkbox_checked) ?>;
            var ini_weights = <?= json_encode($weights) ?>;

            for (var i = 0; i < checkboxChecked.length; i++) {
                var checked_value = checkboxChecked[i] == "checked" ? true : false;
                var checkbox = $("#" + checkboxes[i]);
                var weight = $("#" + checkboxes[i] + "_weight");
                if (checkbox.prop("checked") !== checked_value || !(weight.val() === ini_weights[i])) {
                    data_changed = true;
                }
            }
            return data_changed;
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

        async function deleteOperation(){
            await $.get("Operation/delete_operation_service/" + tricountId + "/" + operationId);
        }

        $(function(){

            title = $("#title");
            errTitle = $("#errTitle");
            errAmount = $("#errAmount");
            amount=$("#amount");
            errWeights = $("#errWeights");
            okWeights = $("#okWeights");
            date = $("#date");
            let template_name_exists=false;

            
            $("#applyTemplateBtn").hide();
            totalAmount=$("#amount");
            handleAmounts();                
            checkUserParticipatesOperation(operation)

            $("#amount").on("blur", function(){
                handleAmounts();
            });
            
            $(".checkboxParticipant").change(function(){
                handleCheckbox();
                handleAmounts();
                reselect_customRepartition();
            });

            handleTemplates();

            $("#phpAmountError").hide();
            $("#phpTitleError").hide();
            
            $("#applyTemplateSelect").change(function() {
                applyItems();
            })

            if(justvalidate == "off"){
                amount.bind("input", checkAmount)
                title.bind("input", checkTitle);

                if(title.val()!="" || amount.val()!=""){
                    checkTitle();
                    checkAmount();
                }

                $(".checkboxParticipant").change(function(){
                    checkCheckboxesCount();
                    checkWeight();
                })

                $("#editOperationForm").submit(function(){
                    return checkAll();            
                });
            }else{
                const validation = new JustValidate('#editOperationForm', {
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
                        ],{ successMessage: 'Looks good'})

                        .addField('#amount', [
                            {
                                rule: 'required',
                                errorMessage: 'Amount is required'
                            },
                            {
                                rule: 'customRegexp',
                                value: /^[0-9.,]+$/,
                                errorMessage: 'Amount must be a number'
                            },
                            {
                                rule: 'minNumber',
                                value: 0.01,
                                errorMessage: 'Amount must be >= 0.01€'
                            },
                        ],{ successMessage: 'Looks good'})

                        .addField('#date', [
                            {
                                rule: 'required',
                                errorMessage: 'Date is required'
                            },
                            {
                                plugin: JustValidatePluginDate(() => {
                                    return {
                                        isBefore: new Date()
                                    }
                                }),
                                errorMessage: 'Date cannot be in the future'
                            },
                        ],{ successMessage: 'Looks good'})

                        .addRequiredGroup('#checkboxes', 'You must select at least 1 participant');

                        $("#saveTemplateCheck").change(function() {
                            if($("#saveTemplateCheck").prop("checked") == true){
                                validation
                                    .addField("#newTemplateName", [
                                        {
                                            rule: "required",
                                            errorMessage: "A title is required to be able to save the template"
                                        },
                                    ], { successMessage: "Looks good"})

                                    .onValidate(async function(event) {
                                        template_name_exists = await $.post("template/template_exists_service/", {newTitle: $("#newTemplateName").val(), tricountId: tricountId}, null, "json");
                                        if(template_name_exists){
                                            this.showErrors({"#newTemplateName" : "You already have a template with this name"});
                                        }
                                    }) ;
                            }else {
                                validation
                                    .removeField("#newTemplateName");
                            }
                        })

                    validation
                        .onSuccess(function(event) {
                            if(!template_name_exists){
                                event.target.submit();
                            }
                        });
                        $("input[name='weight[]']").on('input change', function(){
                            setTimeout(function() {
                                validation.revalidateGroup('#checkboxes');
                        }, 100);
                        });
            }
            if(sweetalert == "on"){
                title.on("input", function() {
                    updateDataStatus(title.val(), amount.val(), date.val());
                });

                amount.on("input", function(){
                    updateDataStatus(title.val(), amount.val(), date.val());
                });

                date.on("blur", function(){
                    updateDataStatus(title.val(), amount.val(), date.val());
                });

                $(".checkboxParticipant").change(function() {
                    data_changed = updateDataStatusCheckboxes();
                });

                $("#saveTemplateCheck").change(function(){
                    updateDataStatus(title.val(),amount.val(),date.val());
                })

                $("#newTemplateName").on("input",function() {
                    updateDataStatus(title.val(), amount.val(), date.val());
                })

                updateDataAfterWeightInput();

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
                                window.location.href="Operation/showOperation/" + tricountId + "/" + operationId;
                            }
                        })
                    }
                });

                $("#btnDelete").click(function(event){
                    event.preventDefault();
                    Swal.fire({
                        title: "Are you sure ?",
                        icon: 'warning',
                        html: 'Do you really want to delete this operation ?<br><br> This process cannot be undone.',
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
                                text: 'This operation has been deleted.'
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
        <a id="btnCancel" href="Operation/showOperation/<?=$tricount->id?>/<?=$operation->id?>" class="btn btn-outline-danger">Cancel</a>
        <?=$tricount->title?> &#8594; Edit Expense
        <input type="submit" class="btn btn-primary" form="editOperationForm" name="saveButton" value="Save">
    </div>
    <form id="applyTemplateForm" action="Operation/editOperation/<?=$tricount->id?>/<?=$operation->id?>" method="post"></form>
    <div class="container min-vh-100 pt-2">
        <form id="editOperationForm" action="Operation/editOperation/<?=$tricount->id?>/<?=$operation->id?>" method="post">
            <div>
                <input class="form-control mb-2" id="title" name="title" value="<?=$operation->title?>">
                <div id='errTitle' class='text-danger'></div>       
                <div id='okTitle' class='text-success' ></div>
                <?php if (count($errorsTitle) != 0): ?>
                    <div id="phpTitleError" class='text-danger'>      
                        <ul>
                            <?php foreach ($errorsTitle as $errors): ?>
                                <li><?= $errors ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
            <div class="input-group mb-2">
                <input  class = "form-control" id="amount" name="amount" type="text" value="<?= $operation->amount?>" placeholder="Amount">   
                <span class="input-group-text" style="background-color: #E9ECEF">EUR</span>
            </div>
            <div id='errAmount' class='text-danger'></div>       
           <div id='okAmount' class='text-success' ></div>
            <?php if (count($errorsAmount) != 0): ?>
                <div id="phpAmountError" class='text-danger'>
                    <ul>
                        <?php foreach ($errorsAmount as $errors): ?>
                            <li><?= $errors ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            Date
            <div>
                <input class="form-control mb-2" id="date" name="date" type="date" value="<?=$operation->operation_date?>">
                <?php if (count($errorsDate) != 0): ?>
                    <div class='text-danger' id="errDatesPhp">
                                <ul>
                                <?php foreach ($errorsDate as $errors): ?>
                                    <li><?= $errors ?></li>
                                <?php endforeach; ?>
                                </ul>
                            </div>
                <?php endif; ?>
            </div>
            Paid by
            <select class="form-select" name="paidBy">
                <?php foreach($participants as $participant){ ?>
                        <option <?=$participant->id==$operation->initiator ? "selected" : "" ?> value="<?=$participant->id?>"><?=$participant->full_name?></option>
                <?php } ?>
            </select>
            Use repartition template (optional)
            <div class="input-group mb-2">
                <select class="form-select" id="applyTemplateSelect" name="repartitionTemplates" form="applyTemplateForm">
                    <option value="customRepartition">No, i'll use custom repartition</option>
                        <?php foreach($templates as $template){ ?>
                                <option value="<?=$template->id?>"<?=$template->id==$selected_repartition ? "selected" : "" ?>><?=$template->title?></option>
                        <?php } ?>
                </select>
                <input class="btn btn-outline-secondary" id="applyTemplateBtn" type="submit" name="ApplyTemplate" value="&#10226;" form="applyTemplateForm">
            </div>
            For whom ? (select at least one)
            <div id="checkboxes">
                <?php for($i = 0; $i<sizeof($participants);++$i){ ?>
                    <div class="input-group mb-2 mt-2">
                        <span class="form-control" style="background-color: #E9ECEF">
                            <input type="checkbox" 
                                class = "checkboxParticipant"
                                name="checkboxParticipants[]" 
                                id="<?=$participants[$i]->id?>"
                                value ="<?=$participants[$i]->id?>" 
                                <?= $checkbox_checked[$i] ?>
                            >
                        </span>
                        <span class="input-group-text w-75" style="background-color: #E9ECEF"><?=$participants[$i]->full_name?></span>
                        <span id="<?=$participants[$i]->id?>_amount"> </span>
                        <input class="form-control" type="number" min="0" name="weight[]" 
                        id="<?=$participants[$i]->id?>_weight" value="<?=$weights[$i]==null ? "0" :  $weights[$i]?>" 
                        oninput="if(this.value < 0) this.value = 0"
                        onblur="handleAmounts(); reselect_customRepartition()">
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
            </div>
            Add a new repartition template
            <div class="input-group mb-2 pt-2 pb-2">
                <span class="form-control" style="background-color: #E9ECEF"><input type="checkbox" name="saveTemplateCheck" id="saveTemplateCheck" <?=$save_template_checked?>></span>
                <span class="input-group-text" style="background-color: #E9ECEF">Save this template</span>
                <input class="form-control w-50" id="newTemplateName" name="newTemplateName" value="<?=$newTemplateName?>">
            </div>
        </form>
        <?php if (count($errorsSaveTemplate) != 0): ?>
                        <div class='text-danger'>
                            <ul>
                            <?php foreach ($errorsSaveTemplate as $errors): ?>
                                <li><?= $errors ?></li>
                            <?php endforeach; ?>
                            </ul>
                        </div>
        <?php endif; ?>
        <a href="Operation/delete_operation/<?=$tricount->id?>/<?=$operation->id?>" class="btn btn-danger w-100" id="btnDelete">Delete Operation</a>
    </div>
</body>
</html>