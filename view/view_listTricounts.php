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
                <?php foreach($tricounts as $tricount){ ?>
                        <li>
                            <h1><?=$tricount->title;?></h1>
                            <li><?php if($tricount->description=="NULL" or $tricount->description==""){
                                        echo "NO DESCRIPTION";
                                    } else {
                                        echo $tricount->description;
                                    }
                                ?>
                            </li>
                        </li>
                <?php } ?>
                
                
            </ul>

        </div>
        <a href = "main/settings/"> <img src ="ressources/images/engr.png" style="width:50px;height:50px;"> </a>
    </body>
</html>