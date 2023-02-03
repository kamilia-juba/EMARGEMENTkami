<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <base href="<?= $web_root ?>"/>
    <title><?=$tricount->title?></title>
    <style type="text/css">
		.btn-circle.btn-xl {
			width: 70px;
			height: 70px;
			padding: 13px 18px;
			border-radius: 60px;
			font-size: 25px;
			text-align: center;
		}
	</style>
</head>
<body>
    <div class="pt-3 ps-3 pe-3 pb-3 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD ">
        <a href= "" class= "btn btn-outline-danger" name = "buttonBack">Back</a>
        <?=$tricount->title?> &#8594 Expenses

        <a href="Tricount/editTricount/<?= $tricount->id?>" class="btn btn-primary" name = "buttonEdit">Edit</a>
    </div>
    <?php if($alone && $noExpenses){ ?>
        <div class="container pt-5 ps-2 pe-2 text-center">
            <ul class="list-group p-2">
                <li class="list-group-item list-group-item-secondary ps-3 fs-4">
                    <b>You are alone!</b>
                </li>
                <li class="list-group-item ps-3">
                    <p>Click below to add your friends!</p>
                    <p><a href='Tricount/editTricount/<?=$tricount->id?>' class='btn btn-primary' name='addFriendOrExpenseBtn'>Add friends</button></a></p>
                </li>
            </ul>
        </div>
    <?php }elseif(!$alone && $noExpenses) { ?>
        <div class="container pt-5 ps-2 pe-2 text-center">
            <ul class="list-group p-2">
                <li class="list-group-item list-group-item-secondary ps-3 fs-4">
                    <b>Your Tricount is empty!</b>
                </li>
                <li class="list-group-item ps-3">
                    <p>Click below to add your first expense!</p>
                    <p><a href='Operation/add_operation/<?=$tricount->id?>' class='btn btn-primary' name='addFriendOrExpenseBtn'>Add an expense</a></p>
                </li>
            </ul>
        </div>
    <?php }else{ ?>
        <div class="container">
            <a href="Tricount/showBalance/<?= $tricount->id?>" class="btn btn-success w-100 mt-2 mb-2" name = "buttonViewBalance">&#8644 View balance</a>
        </div>
        <ul class="list-group p-2">
       <?php foreach($operations as $operation){ ?>
                <li class="list-group-item ps-3"><a class="text-decoration-none text-dark" href="Operation/showOperation/<?=$tricount->id?>/<?=$operation->id?>">
                    <div class="d-flex justify-content-between">
                        <h1><?=$operation->title?></h1>
                        <h1><?=$operation->amount?> €</h1>
                    </div>
                    <div class="d-flex justify-content-between">
                        <p>Paid by <?=$operation->get_payer()->full_name?></p>
                        <p><?=date('d/m/Y',strtotime($operation->operation_date))?></p>
                    </div>
                </a></li>
       <?php } ?>
        </ul>
    <?php } ?>
    <div class="container w-100 p-5 mb-5 mt-5">
    </div>
    <footer class="footer mt-auto fixed-bottom pt-1 ps-2 pe-2 text-secondary fs-5" style="background-color: #E3F3FD">
        <div class="position-relative">
            <div class="position-absolute top-0 start-50 translate-middle">
                <a href = "Operation/add_operation/<?= $tricount->id?>" name="plusButton" class="btn btn-primary btn-circle btn-xl" >+</a>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <p>MY TOTAL</p>
            <p>TOTAL EXPENSES</p>
        </div>
        <div class="d-flex justify-content-between">
            <p><b><?=round($myBalance,1)?> €</b></p>
            <p><b><?=$tricount->get_total_expenses();?> €</b></p>
        </div>
    </footer>
</body>
</html>