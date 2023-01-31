<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>addtricount</title>
        <base href="<?= $web_root ?>"/>
    </head>
    <body>
        <div class="titlebar">
        <a href = "Tricount/yourTricounts/">  <button type="button" name="buttonCancel">Cancel</button></a>
             Tricount->add
        <input type="submit" name="buttonSave" value="Save" form="addTricount">
        </div>
        <div class="main">
            <br><br>
            <form id="addTricount" action="Tricount/addtricount" method="post">
            <label for="title">Titre :</label><br>
            <input type="text" id="title" name="title"><br>
            <label for="description">Description :</label><br>
            <input type="text" id="description" name="description"><br><br>
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
                  
        </div>        
    </body>
</html>