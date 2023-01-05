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
            <a href = "Tricount/addtricount/">  <button type="button" name="buttonAdd">Add</button></a>
        </div>
        <div class="tricounts">
            <ul>
                <?php foreach($tricounts as $tricount){ ?>
                        <li><a href="Tricount/showTricount/<?= $tricount->id?>">
                            <h1><?=$tricount->title;?></h1>
                            <div class="nbparticipants">
                                <?php if($tricount->nbParticipantsTricount()==0){
                                    echo "You're alone";
                                } else if ($tricount->nbParticipantsTricount()==1){
                                    echo "With ".$tricount->nbParticipantsTricount()." friend";
                                } else {
                                    echo "With ".$tricount->nbParticipantsTricount()." friends";
                                }
                                ?></div><br>
                                <?php if($tricount->description=="NULL" or $tricount->description==""){
                                        echo "NO DESCRIPTION";
                                    } else {
                                        echo $tricount->description;
                                    }
                                ?>
                        </a></li>
                <?php } ?>
                
                
            </ul>

        </div>
        <a href = "user/settings/"> <img src ="ressources/images/engr.png" style="width:50px;height:50px;"> </a>
    </body>
</html>