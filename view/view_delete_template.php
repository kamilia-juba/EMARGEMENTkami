<html lang="fr">
<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Template</title>
</head>
<body>
    <form id="deleteTemplateForm" action="Template/deleteTemplate/<?=$tricount->id?>/<?=$template->id?>" method="POST">
        <table>
            <tr>
                <td>Are you sure you want to delete this template ?</td>
            </tr>
            <tr>
                <td><input type="submit" name="yes" value="Yes"></td>
                <td><input type="submit" name="no" value="No"></td>
            </tr>
        </table>
    </form>
</body>
</html>