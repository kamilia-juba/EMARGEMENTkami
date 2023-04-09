<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title><?= $tricount->title?> -> New expanse</title>
        <base href="<?= $web_root ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <script src="lib/jquery-3.6.3.min.js" type="text/javascript"></script>

        <script>

            var tricountId = <?= $tricount->id?>;
            let title,amount, errTitle, errAmount;

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
            
            
            function checkAmount(){
                let ok = true;
                errAmount.html("");

                if(amount.val().trim().length === 0){
                    errAmount.append("");
                    errAmount.append("<p>Amount cannot be empty.</p>");
                    ok = false;
                }
                else if(amount.val()<=0){
                    errAmount.append("");
                    errAmount.append("<p>Amount must be greater than 0</p>");
                    ok = false;
                }
                changeAmountView();
                return ok;
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
            
            function changeAmountView(){
                if(errAmount.text() == ""){
                    $("#okAmount").html("Looks good");
                    $("#amount").attr("class","form-control mb-2 is-valid");
                }else{
                    $("#okAmount").html("");
                    $("#amount").attr("class", "form-control mb-2 is-invalid");
                }
            }

            function checkAll(){
                //return checkTitle() && checkAmount();
                let ok = checkTitle();
                ok = checkAmount() && ok;
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
                    
                   
                    amount.html("<span class='input-group-text ' style='background-color: #E9ECEF'>" + individualAmount + " €</span>")
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
                       
                    for (var i =0; i<checkboxes.length;++i){
                         var checkbox= $("#" + checkboxes[i]);
                        
                         var weight = $("#" + checkboxes[i]+  "_weight");
                         var weightval = weight.val();

                        if(checkbox.prop("checked")==false){
                            weight.val("0");
                        }
                        if(checkbox.prop("checked")==true){
                            if(weight.val()==="0"){
                                weight.val("1");
                            }
                            else(weight.val(weightval));
                        }
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
            $(function(){
                title = $("#title");
                errTitle = $("#errTitle");
                errAmount = $("#errAmount");
                amount=$("#amount")
                
                amount.bind("input", checkAmount)
                title.bind("input", checkTitle);

                if(title.val()!="" || amount.val()!=""){
                    checkTitle();
                    checkAmount();
                }
                

                totalAmount=$("#amount");
                handleAmounts();                

                $("input[type='number']").on("blur", function(){
                    handleAmounts();
                });                
                
                $(".checkboxParticipant").change(function(){
                   handleCheckbox();
                   handleAmounts();
                });

                handleTemplates();

                $("#phpAmountError").hide();
                $("#phpTitleError").hide();

                
                $("#applyTemplateSelect").change(function() {
                    applyItems();
                })
            });
        </script>
    </head>
    <body>
    <div class="pt-3 ps-3 pe-3 pb-3 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD">
        <a href="Tricount/showTricount/<?=$tricount->id?>/" class="btn btn-outline-danger">Cancel</a>
        <?=$tricount->title?> &#8594; New expense
        <input type="submit" class="btn btn-primary" form="addOperationForm" name="saveButton" value="Save">
    </div>
        <div class="container min-vh-100 pt-2">
        <form id="applyTemplateForm" action="Operation/add_operation/<?=$tricount->id?>" method="post"></form>
        <form id="addOperationForm" action="Operation/add_operation/<?= $tricount->id?>" method="post">
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
            <div class="input-group mb-2">           
                <input  class = "form-control" id="amount" name="amount" type="number" value="<?= $amount?>" placeholder="Amount">   
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
            <input class ="form-control mb-2" id="date" name="date" type="date" value="<?php $timezone = date_default_timezone_get(); echo date("Y-m-d")?>">
            Paid by
            <select class="form-select" name="paidBy">
                <?php for($i = 0; $i<sizeof($participants_and_weights);++$i){ ?>
                        <option <?=$participants_and_weights[$i][0]->id==$user->id ? "selected" : "" ?> value="<?=$participants_and_weights[$i][0]->id?>"><?=$participants_and_weights[$i][0]->full_name?></option>
                <?php } ?>
            </select>
            Use repartition template (optional)
            <div class="input-group mb-2">
                <select class="form-select" id="applyTemplateSelect" name="repartitionTemplates" form="applyTemplateForm">
                    <option value="customRepartition">No, i'll use custom repartition</option>
                    <?php foreach($repartition_templates as $repartition){ ?>
                            <option value="<?=$repartition->id?>"<?=$repartition->id==$selected_repartition ? "selected" : "" ?>><?=$repartition->title?></option>
                    <?php } ?>
                </select>
                <input class="btn btn-outline-secondary" id="applyTemplateBtn" type="submit" name="ApplyTemplate" value="&#10226;" form="applyTemplateForm">
            </div>
            For whom ? (select at least one)
            <?php for($i = 0; $i<sizeof($participants_and_weights);++$i){ ?>
                <div class="input-group mb-2 mt-2">
                    <span class="form-control" style="background-color: #E9ECEF">
                        <input type="checkbox" 
                        class = "checkboxParticipant"
                            name="checkboxParticipants[]" 
                            id="<?=$participants_and_weights[$i][0]->id?>"
                            value ="<?=$participants_and_weights[$i][0]->id?>" 
                            <?php if($participants_and_weights[$i][2]){ ?>
                                        checked
                            <?php } ?>
                        >
                    </span>
                    <span class="input-group-text w-75" style="background-color: #E9ECEF"><?=$participants_and_weights[$i][0]->full_name?></span>
                    <span id="<?=$participants_and_weights[$i][0]->id?>_amount"> </span>
                    <input class="form-control" type="number" min="0" name="weight[]" id="<?=$participants_and_weights[$i][0]->id?>_weight" value="<?=$participants_and_weights[$i][1]?>" oninput="if(this.value < 0) this.value = 0">
                </div>
            <?php } ?>
            <?php if (count($errorsCheckboxes) != 0): ?>
                        <div class='text-danger'>
                            <ul>
                            <?php foreach ($errorsCheckboxes as $errors): ?>
                                <li><?= $errors ?></li>
                            <?php endforeach; ?>
                            </ul>
                        </div>
            <?php endif; ?>
            Add a new repartition template
            <div class="input-group mb-2 pt-2 pb-2">
                <span class="form-control" style="background-color: #E9ECEF"><input type="checkbox" name="saveTemplateCheck"></span>
                <span class="input-group-text" style="background-color: #E9ECEF">Save this template</span>
                <input class="form-control w-50" id="newTemplateName" name="newTemplateName">
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
                        