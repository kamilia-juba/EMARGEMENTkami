<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>addtricount</title>
        <base href="<?= $web_root ?>"/>
    </head>
    <body>
        <div class="titlebar">
            <button type="button" style"float: left" name="buttonAdd">Cancel</button>
             Tricount->add
            <button type="button" style"float: right" name="buttonAdd">Add</button>
        </div>
        

        <div class="main">
            <br><br>
            <form id="addTricountForm" action="Tricount/addTricount" method="post">
                <table>
                    <tr>
                        <td>Title:</td>
                        <td><input id="mail" name="mail" type="text" size="25" value="<?= $title ?>"></td>
                    </tr>
                    <tr>
                        <td>Description(optional):</td>
                        <td><input id="full_name" name="full_name" type="text" size="16" value="<?= $description ?>"></td>
                    </tr>
                  
        </div>        
    </body>
</html>