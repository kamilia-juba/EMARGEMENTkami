<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Your Tricounts</title>
        <base href="<?= $web_root ?>"/>
    </head>
    <body>
        <div class="titlebar">
            Your Tricounts
            <button type="button" name="buttonAdd">Add</button>
        </div>
        <div class="tricounts">
            <ul>
                <?php 
                    foreach($tricounts as $tricount){
                        echo "<li>".$tricount->$title."</li>";
                    }
                ?>
                
            </ul>

        </div>
    </body>
</html>