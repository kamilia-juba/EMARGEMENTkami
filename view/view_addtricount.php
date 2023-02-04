<!DOCTYPE html>
<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
        <div class="pt-3 ps-3 pe-3 pb-3 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD">   
            <a href = "Tricount/yourTricounts/"  class="btn btn-outline-danger" name="buttonCancel">Cancel</a>
            Tricount &#8594 add    
            <form id="addTricount" action="Tricount/addtricount" method="post">
            <button from="addTricount" class="btn btn-primary" type="submit">Save</button>
        </div>    
        <meta charset="UTF-8">
        <title>addtricount</title>
        <base href="<?= $web_root ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
    <form id="addTricount" action="Tricount/addtricount" method="post">
        <div class="form-group pt-3 ps-3 pe-3 pb-3">
             <label class="pb-3">Title :</label>
             <input class="form-control"id="title" name="title" type="text" size="16" value="<?= $title ?>" placeholder="entrez le titre">
        </div>
        <div class="form-group pt-3 ps-3 pe-3 pb-3">
             <label class="pb-3">Description(Optionel):</label>
             <input class="form-control"id="description" name="description" type="text" size="32" value="<?= $description ?>" placeholder="entrez la discription">
        </div>     
       
    </form> 
         <?php if (count($errors) != 0): ?>
             <div class='text-danger ps-3 pt-3 pe-3 pb-3'>
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