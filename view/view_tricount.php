<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="lib/jquery-3.6.3.min.js" type="text/javascript"></script>
    <script>
            
        let operations = <?=$operations_json?>;
        let sortColumn ='created_at';
        let sortAscending = false;
        let tblOperations;
        let selectMenu;


        
        
        function sort(){

            // Sélectionne la balise <select> par son ID
            const selectElement = $('#sort');

            let field = selectElement.val();

            switch (field){
                case "amount-asc" : sortByField('amount', true);
                break;
                case "amount-desc" : sortByField('amount', false);
                break;
                case "date-asc" : sortByField('created_at', true);
                break;
                case "date-desc" : sortByField('created_at', false);
                break;
                case "initiator-asc" : sortByField('initiator', true);
                break;
                case "initiator-desc" : sortByField('initiator', false);
                break;
                case "title-asc" : sortByField('title', true);
                break;
                case "title-desc" : sortByField('title', false);
                break;
            }

        }
        

        $(function(){
                tblOperations = $('#operations_ul');
                tblOperations.html("<tr><td>Loading...</td></tr>");
                getOperations();
                selectMenu= $('#selectSort');
                selectMenu.html(`<div class="p-2">
                <label for="sort" class="mb-2">Order expenses by :</label>
                <select onchange="sort()" name="sort" id="sort" class="form-select">
                    <option value="amount-asc">&#9650; Amount</option>
                    <option value="amount-desc">&#9660; Amount</option>
                    <option value="date-asc">&#9650; Date</option>
                    <option value="date-desc" selected>&#9660; Date</option>
                    <option value="initiator-asc">&#9650; Initiator</option>
                    <option value="initiator-desc">&#9660; Initiator</option>
                    <option value="title-asc">&#9650; Title</option>
                    <option value="title-desc">&#9660; Title</option>
                </select>
                </div>`);
            });
        

        async function getOperations() {
                    
            try {
                sortOperations();
                displayOperations();
            } catch(e) {
                alert("Une erreur s'est produite: " + e.message);
                tblOperations.html("<tr><td>Error encountered while retrieving the expanses!</td></tr>");
            }
        }

        function sortOperations() {
            operations.sort(function (a,b) {
                if (a[sortColumn] < b[sortColumn])
                    return sortAscending ? -1 : 1;
                if (a[sortColumn] > b[sortColumn])
                    return sortAscending ? 1 : -1;
                return 0;
            });
        }

        function sortByField(field, ascending) {

            sortAscending = ascending;
            sortColumn = field;
            sortOperations();
            displayOperations();
        }

        function displayOperations(){
            let html='';

            for (let operation of operations) {
                const date = new Date(operation.created_at);
                const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
                const formattedDate = date.toLocaleDateString('fr-FR', options);

                html += '<li class="list-group-item ps-3"><a class="text-decoration-none text-dark" href="Operation/showOperation/' + <?=$tricount->id?> + '/' + operation.id + '">' +
                        '<div class="d-flex justify-content-between">' +
                        '<h1>' + operation.title + '</h1>' +
                        '<h1>' + operation.amount + ' €</h1>' +
                        '</div>' +
                        '<div class="d-flex justify-content-between">' +
                        '<p>Paid by ' + operation.initiator + '</p>' +
                        '<p>' + formattedDate + '</p>' +
                        '</div>' +
                        '</a></li>'
            }
            tblOperations.html(html);
        }    
    </script>
    
    <title><?=$tricount->title?></title>
    <style>
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
        <a href= "" class= "btn btn-outline-danger">Back</a>
        <?=$tricount->title?> &#8594; Expenses
        <a href="Tricount/edit_tricount/<?= $tricount->id?>" class="btn btn-primary">Edit</a>
    </div>
    <?php if($alone && $noExpenses){ ?>
        <div class="container pt-5 ps-2 pe-2 text-center">
            <ul class="list-group p-2">
                <li class="list-group-item list-group-item-secondary ps-3 fs-4">
                    <b>You are alone!</b>
                </li>
                <li class="list-group-item ps-3">
                    <p>Click below to add your friends!</p>
                    <p><a href='Tricount/edit_tricount/<?=$tricount->id?>' class='btn btn-primary'>Add friends</a></p>
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
                    <p><a href='Operation/add_operation/<?=$tricount->id?>' class='btn btn-primary'>Add an expense</a></p>
                </li>
            </ul>
        </div>
    <?php }else{ ?>
        <div class=" d-flex justify-content-between p-2">
            <a href="Tricount/show_balance/<?= $tricount->id?>" class="btn btn-success w-100 mt-2 mb-1">&#8644; View balance</a>
        </div>
        <div id=selectSort></div>
        
        <ul id="operations_ul" class="list-group p-2">
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
                <a href = "Operation/add_operation/<?= $tricount->id?>" class="btn btn-primary btn-circle btn-xl" >+</a>
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