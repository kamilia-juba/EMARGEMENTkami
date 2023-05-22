<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title><?= $tricount->title?> -> New expanse</title>
        <base href="<?= $web_root ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="lib/jquery-3.6.3.min.js" type="text/javascript"></script>
        <script src="lib/just-validate-4.2.0.production.min.js" type="text/javascript"></script>
        <link href="css/styles.css" rel="stylesheet" type="text/css"/>
        <script src="lib/just-validate-plugin-date-1.2.0.production.min.js"></script>
        <script src="lib/sweetalert2@11.js"></script>

        <script>

            var tricountId = <?= $tricount->id?>;
            let title,amount, errTitle, errAmount,errWeights,okWeights;
            var justvalidate = "<?= $justvalidate?>";
            let sweetalert = "<?= $sweetalert?>";
            let checkboxes_count;
            let template_name_available;
            let data_changed = false;
            let ini_title = "<?= $title ?>";
            let ini_amount= "<?= $amount ?>";
            let ini_date = "<?= $date ?>" ;

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
                    console.log(weight);
                })
                return ok;
            }


            function checkAll(){
                let ok = checkTitle();
                ok = checkAmount() && ok;
                ok = checkWeight();
                return ok;
            }

            // -----------------------------------------------------------


            let totalAmount;
            let template_json = <?=$templates_json?> ;

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
                checkboxes_count = checkboxes.length;       
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

            function updateDataStatus(title, amount, date){
                data_changed = (title != ini_title) || (amount != ini_amount) || (date != ini_date);
            }

            $(function(){
                title = $("#title");
                errTitle = $("#errTitle");
                errAmount = $("#errAmount");
                amount=$("#amount")
                errWeights = $("#errWeights");
                okWeights = $("#okWeights");
                date = $("#date");

                totalAmount=$("#amount");
                handleAmounts();   

                $("#amount").on("blur", function(){
                    handleAmounts();
                });  


                $(".checkboxParticipant").change(function(){
                    handleCheckbox();
                    handleAmounts();
                    reselect_customRepartition();
                });

                handleTemplates();

                $("#applyTemplateSelect").change(function() {
                    applyItems();
                })

                $("#phpAmountError").hide();
                $("#phpTitleError").hide();
                $("#errCheckboxesPhp").hide();

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
                }else {
                    const validation = new JustValidate('#addOperationForm', {
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
                                console.log("yo");
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
                        updateDataStatus(title.val(), amount.val(), date.val());
                    });

                    amount.on("input", function(){
                        updateDataStatus(title.val(), amount.val(), date.val());
                    });

                    date.on("blur", function(){
                        updateDataStatus(title.val(), amount.val(), date.val());
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
                                    window.location.href="Tricount/showTricount/" + tricountId;
                                }
                            })
                        }
                    });
                }
            });
            
        </script>
    </head>
    <body>
    <div class="pt-3 ps-3 pe-3 pb-3 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD">
        <a href="Tricount/showTricount/<?=$tricount->id?>/" id="btnCancel" class="btn btn-outline-danger">Cancel</a>
        <?=$tricount->title?> &#8594; New expense
        <input type="submit" class="btn btn-primary" form="addOperationForm" name="saveButton" value="Save">
    </div>
        <div class="container min-vh-100 pt-2">
        <form id="applyTemplateForm" action="Operation/add_operation/<?=$tricount->id?>" method="post"></form>
        <form id="addOperationForm" action="Operation/add_operation/<?= $tricount->id?>" method="post">
            <div>
                <input class="form-control mb-2" id="title" name="title" type="text" value="<?= $title?>" placeholder="Title">
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
        
                <input  class = "form-control" id="amount" name="amount" type="text" value="<?= $amount?>" placeholder="Amount">   
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
                <input class ="form-control mb-2" id="date" name="date" type="date" value="<?=$date?>">
            </div>
            Paid by
            <select class="form-select" name="paidBy">
                <?php foreach($participants as $participant){ ?>
                        <option <?=$participant->id==$paidBy ? "selected" : "" ?> value="<?=$participant->id?>"><?=$participant->full_name?></option>
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
            <div  id="checkboxes">
                <?php for($i=0; $i < sizeof($participants); ++$i){ ?>
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
                        <input class="form-control" type="number" min="0" 
                        name="weight[]" 
                        id="<?=$participants[$i]->id?>_weight" value="<?=$weights[$i]?>" 
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
                <span class="form-control" style="background-color: #E9ECEF"><input type="checkbox" id="saveTemplateCheck" name="saveTemplateCheck"></span>
                <span class="input-group-text" style="background-color: #E9ECEF">Save this template</span>
                <input class="form-control w-50" id="newTemplateName" name="newTemplateName" value="<?=$save_template_name?>">
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
        </div>
    </body>
</html>
                        