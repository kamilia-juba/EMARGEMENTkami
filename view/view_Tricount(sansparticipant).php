<html>
<head>
<title><?=$tricount->title?></title>
  <base href="<?= $web_root?>"/>
</head>
<body>
<a href = "Tricount/addtricount/">  <button type="button" name="buttonAdd">Back</button></a>
<?= $tricount-> title ?> &#8594 Expenses
<a href = "Tricount/EditTricount/<?=$tricount->id?>">  <button type="button" name="EditeButton"> Edit </button></a>
  <div class="message-box">
    You are alone
  <a href = "Tricount/EditTricount/<?=$tricount->id?>">  <button class="add-friends-button"> Add Friends </button></a>
  </div>

  <section id="bottomBar">
        <section id="myTotal">
            My total<br>
            0 €
        </section>
        <button name="plusButton">+</button>
        <section id="totalExpenses">
            Total expenses<br>
            0 €
        </section>
    </section>
</body>
</html>

