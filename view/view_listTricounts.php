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
                            <div class="nbparticipants">
                                <?php if($tricount->nbParticipantsTricount()==0){
                                    echo "You're alone";
                                } else if ($tricount->nbParticipantsTricount()==1){
                                    echo "With ".$tricount->nbParticipantsTricount()." friend";
                                } else {
                                    echo "With ".$tricount->nbParticipantsTricount()." friends";
                                }
                                ?></div>
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
    </body>
</html>