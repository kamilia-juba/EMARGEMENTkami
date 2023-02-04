<!DOCTYPE html>
<html>
  <head>
  <script src="https://kit.fontawesome.com/fd46891f37.js" crossorigin="anonymous"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link rel=”stylesheet” href=”https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css” />
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Confirmation</title>
    <base href="<?= $web_root ?>"/>
  </head>
  <body>
  <div class="container d-flex align-items-center min-vh-100 text-danger">
      <div class="container border rounded ">
        <div class="container border-bottom text-center pt-4 pb-4">
          <h1><p><i class="fa-regular fa-trash-can fa-xl"></i></p>
              Are you sure?
          </h1>
        </div>
           <div class="container border-bottom text-center pt-4 pb-4">
            <form  id="deleteTemplateForm" action="Tricount/delete_tricount/<?=$tricount->id?>" method="post">
                     <p>Do you really want to delete Tricount "<span style="font-weight:bold;"><?=$tricount->title?></span>" and all of its dependencies ?<p>
                     <p>This process cannot be undone</p>
                  
                    
                     <input   class="btn btn-secondary" type="submit" name="no" value="Cancel">
                    <input  class="btn btn-danger" type="submit" name="yes" value="Delete">
                
            </form>
        </div>
    </div>
    </div>
  </body>
</html>