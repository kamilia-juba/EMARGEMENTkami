<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Operation</title>
    <base href="<?= $web_root ?>"/>
</head>
<body>
    <section id="titlebar">
        <a href="Operation/showOperation/<?=$operation->id?>"><button type="button" name="cancelButton">Cancel</button></a>
        <?=$tricount->title?> &#8594 Edit Expense
        <button type="button" name="saveButton">Save</button>
    </section>
    <form id="editOperationForm" action="" method="post">
        <table>
            <tr><td><input id="title" name="title" value="<?=$operation->title?>"></td></tr>
            <tr>
                <td><input id="amount" name ="amount" value="<?=$operation->amount?>"></td>
                <td>EUR</td>
            </tr>
            <tr><td>Date</td></tr>
            <tr><td><input id="date" name="date" type="date" value="<?=$operation->operation_date?>"></td></tr>
            <tr><td>Paid by</td></tr>
            <tr>
                <td>
                    <select name="paidBy">
                        <?php
                            foreach($participants as $participant){
                                echo "<option>".$participant->full_name."</option>";
                            }
                        ?>
                    </select>
                </td>
            </tr>
        </table>
    </form>
</body>
</html>