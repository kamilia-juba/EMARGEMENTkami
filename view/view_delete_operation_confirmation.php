<!DOCTYPE html>
<html>
  <head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Confirmation</title>
    <base href="<?= $web_root ?>"/>
  </head>
  <body>
  <h1>Are you sure?</h1>
    <br></br>
    <p>Do you really want to delete operation "<?=$operation->title?>" and all of it's dependencies</p>
    <br></br>
    <p>This process cannot be undone</p>
    <a href="Operation/editOperation/<?=$tricount->id?>/<?=$operation->id?>"><button type="button" name="No">Cancel</button></a></body>
    <a href="Operation/delete_operation/<?=$tricount->id?>/<?=$operation->id?>"><button type="button" name="Yes">Delete</button></a></body>
    </form>
  </body>
</html>
