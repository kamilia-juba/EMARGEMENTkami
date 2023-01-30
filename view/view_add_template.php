<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?= $web_root ?>"/>
    <title>Add Template</title>
</head>
<body>
    <a href="Tricount/showTemplates/<?=$tricount->id?>"><button type="button" name="buttonCancel">Cancel</button></a>
    <?=$tricount->title?> &#8594 New template
    <input type="submit" name = "buttonSave" value="Save" form="addtemplateForm"></a><br>
    <form id="addtemplateForm" action="Tricount/addTemplate/<?=$tricount->id?>" method="post">
        Title:<br>    
        <input type="text" name="title">
        Template items:<br>
        <table>
            <?php foreach($participants as $participant) { ?>
                    <tr>
                        <td><input type="checkbox" name="checkboxParticipants[]" value="<?=$participant->id?>" checked></td>
                        <td><?=$participant->full_name?></td>
                        <td><input type="number" name="weight[]" value="1" min="0"></td>
                    </tr>
            <?php } ?>
        </table>
    </form>
    <?php if (count($errors) != 0): ?>
                <div class='errors'>
                    <p>Please correct the following error(s) :</p>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
    <?php endif; ?> 
</body>
</html>