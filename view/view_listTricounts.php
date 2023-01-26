<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Your Tricounts</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    </head>
    <body>
        <div class="pt-3 ps-3 pe-3 pb-3 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD ">
            Your Tricounts
            <a href = "Tricount/addtricount/" class="btn btn-primary" name="buttonAdd">Add</a>
        </div>
        <ul class="list-group p-2">
            <?php foreach($tricounts as $tricount){ ?>
                    <li class="list-group-item ps-3"><a href="Tricount/showTricount/<?= $tricount->id?>" class="text-decoration-none text-dark">
                        <div class="d-flex justify-content-between">
                            <h1><?=$tricount->title;?></h1>
                            <?php if($tricount->nbParticipantsTricount()==0){
                                echo "You're alone";
                            } else if ($tricount->nbParticipantsTricount()==1){
                                echo "With ".$tricount->nbParticipantsTricount()." friend";
                            } else {
                                echo "With ".$tricount->nbParticipantsTricount()." friends";
                            }
                            ?>
                        </div>
                            <?php if($tricount->description=="NULL" or $tricount->description==""){
                                    echo "NO DESCRIPTION";
                                } else {
                                    echo $tricount->description;
                                }
                            ?>
                    </a></li>
            <?php } ?>
        </ul>
        <footer class="footer mt-auto fixed-bottom">
            <div class="container w-100" style="margin-bottom: 42px">
                <a href = "user/settings/" class="float-end"><img src ="ressources/images/engr.png" style="width:50px;height:50px;"/></a>
            </div>
        </footer>
    </body>
</html>