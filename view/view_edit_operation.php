<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Operation</title>
    <base href="<?= $web_root ?>"/>
</head>
<body>
    <section id="titlebar">
        <a href="Operation/showOperation/<?=$operation->id?>"><button type="button" name="cancelButton">Cancel</button></a>
        <?=$tricount->title?> &#8594 Edit Expense
        <input type="submit" form="editOperationForm" name="saveButton" value="Save">
    </section>
    <form id="editOperationForm" action="Operation/editOperation/<?=$operation->id?>" method="post">
        <table>
            <tr><td><input id="title" name="title" value="<?=$operation->title?>"></td></tr>
            <tr>
                <td><input type="number" id="amount" name ="amount" value="<?=$operation->amount?>"></td>
                <td>EUR</td>
            </tr>
            <tr><td>Date</td></tr>
            <tr><td><input id="date" name="date" type="date" value="<?=$operation->operation_date?>"></td></tr>
            <tr><td>Paid by</td></tr>
            <tr>
                <td>
                    <select name="paidBy">
                        <?php
                            for($i = 0; $i<sizeof($participants_and_weights);++$i){
                                echo "<option value='".$participants_and_weights[$i][0]->id."'>".$participants_and_weights[$i][0]->full_name."</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
            <tr><td>Use repartition template (optional)</td></tr>
            <tr>
                <td>
                    <select name="repartitionTemplates">
                        <option value="customRepartition">No, i'll use custom repartition</option>
                        <?php
                            foreach($repartition_templates as $repartition){
                                echo "<option value='".$repartition->id."'>".$repartition->title."</option>";
                            }
                        ?>
                    </select>
                </td>
                <td><button type="button" name="refreshTemplates">&#10226</button></td>
            </tr>
            <tr><td>For whom ? (select at least one)</td></tr>
            <?php
                for($i = 0; $i<sizeof($participants_and_weights);++$i){ ?>
                    <table>
                        <tr>
                            <td><input type='checkbox' name='checkboxParticipants[]' value ='<?=$participants_and_weights[$i][0]->id?>' <?php if($operation->user_participates($participants_and_weights[$i][0]->id)){echo "checked";}?>></td>
                            <td><?=$participants_and_weights[$i][0]->full_name?></td>
                            <td>
                                <table>
                                    <tr><td>Weight</td></tr>
                                    <tr><td><input type="number" name="weight[]" value="<?=$participants_and_weights[$i][1]?>"></td></tr>
                                </table>
                            </td>
                        </tr>
                    </table>
            <?php } ?>
            <tr><td>Add a new repartition template</td></tr>
            <table>
                <tr>
                    <td><input type="checkbox"></td>
                    <td>Save this template</td>
                    <td>
                        <table>
                            <tr><td>Name</td></tr>
                            <tr><td><input id="templateName" ></td></tr>
                        </table>
                    </td>
                </tr>
            </table>

        </table>
    </form>
    <?php if (count($errors) != 0): ?>
                <div class='errors'>
                    <br><br><p>Please correct the following error(s) :</p>
                    <ul>
                        <?php foreach ($errors as $errors): ?>
                            <li><?= $errors ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php elseif (strlen($success) != 0): ?>
                <p><span class='success'><?= $success ?></span></p>
            <?php endif; ?>
    <button type="button" name="deleteOperation" id="deleteOperation">Delete this operation</button>
</body>
</html>