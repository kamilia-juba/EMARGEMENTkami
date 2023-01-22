<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
</head>
<body>
    <div class="titlebar">
        <a href= "Tricount/showTricount/"><button type="button" name = "buttonBack">Back</button></a>
        <?=$tricount->title?> &#8594 Edit
         <input type="submit" name = "buttonSave" value="Save" form="addtricountForm"></a>
    </div>
    <div class="main">
            <br><br>
            <form action="Tricount/EditTricount/<?= $tricount->id?>" id="addtricountForm" method="post">
            <label for="title">Titre :</label><br>
            <input type="text" id="title" name="title" value="<?= $tricount ->title?>"> <br>
            <label for="description">Description :</label><br>
            <input type="text" id="description" name="description"><br><br>
            

            </form> 
            Subscription
            <table>
            <?php
                foreach($participants as $participant){
                    if($participant->id==$user->id){
                    echo "<tr><td>".$participant->full_name." (creator)</td></tr>";
                    }else{
                        echo "<tr><td>".$participant->full_name."</td></tr>";
                    }
                }
            ?>
        </table>
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