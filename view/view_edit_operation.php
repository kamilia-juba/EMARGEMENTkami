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
        <button type="button" name="saveButton">Save</button>
    </section>
</body>
</html>