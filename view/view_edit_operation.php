<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Operation</title>
    <base href="<?= $web_root ?>"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>
<body>
    <div class="pt-2 ps-3 pe-3 pb-2 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD">
        <a href="Operation/showOperation/<?=$tricount->id?>/<?=$operation->id?>" class="btn btn-outline-danger" name="cancelButton">Cancel</a>
        <b><?=$tricount->title?> &#8594 Edit Expense</b>
        <input type="submit" class="btn btn-primary" form="editOperationForm" name="saveButton" value="Save">
    </div>
    <form id="applyTemplateForm" action="Operation/editOperation/<?=$tricount->id?>/<?=$operation->id?>" method="post"></form>
    <div class="container min-vh-100 pt-2">
        <form id="editOperationForm" action="Operation/editOperation/<?=$tricount->id?>/<?=$operation->id?>" method="post">
            <input class="form-control mb-2" id="title" name="title" value="<?=$operation->title?>">
            <div class="input-group mb-2">
                <input class="form-control" type="number" step="0.01" id="amount" min="0" name ="amount" value="<?=$operation->amount?>">
                <span class="input-group-text" style="background-color: #E9ECEF">EUR</span>
            </div>
            Date
            <input class="form-control mb-2" id="date" name="date" type="date" value="<?=$operation->operation_date?>">
            Paid by
            <select class="form-select" name="paidBy">
                <?php for($i = 0; $i<sizeof($participants_and_weights);++$i){ ?>
                        <option <?=$participants_and_weights[$i][0]->id==$operation->initiator ? "selected" : "" ?> value="<?=$participants_and_weights[$i][0]->id?>"><?=$participants_and_weights[$i][0]->full_name?></option>
                <?php } ?>
            </select>
            Use repartition template (optional)
            <div class="input-group mb-2">
                <select class="form-select" name="repartitionTemplates" form="applyTemplateForm">
                    <option value="customRepartition">No, i'll use custom repartition</option>
                    <?php foreach($repartition_templates as $repartition){ ?>
                            <option value="<?=$repartition->id?>"<?=$repartition->id==$selected_repartition ? "selected" : "" ?>><?=$repartition->title?></option>;
                    <?php } ?>
                </select>
                <input class="btn btn-outline-secondary" type="submit" name="ApplyTemplate" value="&#10226" form="applyTemplateForm">
            </div>
            For whom ? (select at least one)</td></tr>
            <?php for($i = 0; $i<sizeof($participants_and_weights);++$i){ ?>
                <div class="input-group mb-2 mt-2">
                    <span class="form-control" style="background-color: #E9ECEF"><input type="checkbox" name="checkboxParticipants[]" value ="<?=$participants_and_weights[$i][0]->id?>" <?php if($operation->user_participates($participants_and_weights[$i][0]->id)){echo "checked";}?>></span>
                    <span class="input-group-text w-75" style="background-color: #E9ECEF"><?=$participants_and_weights[$i][0]->full_name?></span>
                    <input class="form-control" type="number" min="0" name="weight[]" value="<?=$participants_and_weights[$i][1]?>">
                </div>
            <?php } ?>
            <?php if(!$disable_CBox_and_SaveTemplate){ ?>
                Add a new repartition template
                <div class="input-group mb-2 pt-2 pb-2">
                    <span class="form-control" style="background-color: #E9ECEF"><input type="checkbox" name="saveTemplateCheck"></span>
                    <span class="input-group-text" style="background-color: #E9ECEF">Save this template</span>
                    <input class="form-control w-50" id="newTemplateName" name="newTemplateName">
                </div>
            <?php } ?>  
        </form>
        <?php if (count($errors) != 0): ?>
                <div class="text-danger">
                    <ul class="list-inline">
                    <?php foreach ($errors as $errors): ?>
                            <li><?= $errors ?></li>
                    <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
    <a href="Operation/confirm_delete_operation/<?=$tricount->id?>/<?=$operation->id?>" class="btn btn-danger w-100" name="deleteOperation">Delete Operation</a>
</body>
</html>