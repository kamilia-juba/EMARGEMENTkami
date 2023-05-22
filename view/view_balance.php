<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Balance</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="lib/jquery-3.6.3.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script>

    $(function(){

        $("#php").hide();
    });
    </script>

<style>
    canvas {
      padding-left: 10px;
      padding-right: 10px;
    }

  </style>
</head>
<body>

    


    <div class="pt-3 ps-3 pe-3 pb-3 text-secondary d-flex justify-content-between" style="background-color: #E3F3FD"> 
        <a href= "Tricount/showTricount/<?=$tricount->id?>" class= "btn btn-outline-danger" id = "buttonBack">Back</a> 
        <?=$tricount->title?> &#8594; Balance
    </div>

    <body>
    <canvas id="monTableau"></canvas>

    <script>
        let participants = <?=$participantsJson?>;
        let user= <?=$user->id?>;
        let max = <?=$total?>+ (<?=$total?>*10/100); // je rajoute +10% du total pour avoir un peu d'espace... ((provisoire)
        let min = -max;
        

        function getNames(){
            let s = [];

            participants.forEach(function(item) {
                if(item.id==user){
                    s.push(item.full_name + " (me)");

                } else {
                    s.push(item.full_name);
                }
            
            });
            return s;
        }

        function getAmounts(){
            let s = [];

            participants.forEach(function(item) {
                s.push(item.account.toFixed(2));
            });
            return s;
        }



        let names = getNames();
        let amounts = getAmounts();

        var ctx = document.getElementById('monTableau').getContext('2d');
        var data = {
            labels: names,
            datasets: [{
                label: 'Balance',
                data: amounts,
                backgroundColor: function(context) {
                    var value = context.dataset.data[context.dataIndex];
                    return value >= 0 ? 'rgb(60,179,113)' : 'rgb(255,99,71)';
                },
                
                borderWidth: 1,
                barPercentage: 0.8,
                borderRadius: 10,
            }]
        };


        
        var options = {
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    max: max,
                    min: min,
                    ticks: {
                        display: false
                        },
                    grid: {
                        display: false
                    },
                    categorySpacing: 0.4,
                },
            },
            plugins: {
            legend: {
                display: false
            },
            datalabels: {
                anchor: 'center',
                align: function(context) {
                            var value = parseInt(context.dataset.data[context.dataIndex]);
                            if(value>=0){
                                return "left";
                            } else{
                                return "right";
                            };
                },
                offset: function(context) {
                            var value = context.dataset.data[context.dataIndex];
                            if(value>=0){
                                return max*value/1000;
                            } else{
                                return -max*value/1000-25;
                            };
                },
                formatter: function(value, context) {
                    return value + ' €';
                },
                font: {
                    weight: 'bold'
                },                
            },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var value = context.dataset.data[context.dataIndex];
                            return 'Balance : '+ value +" €";
                        },
                        
                        
                    }
                },
            }
        };

        new Chart(ctx, {
            type: 'bar',
            data: data,
            options: options,
            plugins: [ChartDataLabels],
        });
    </script>



<!------------------------------------------------------------------------------------------------------------------------------------>


    <div id="php" class="container pt-2 ">
        <div  style="font-size:15px;">
            <?php foreach($participants as $participant){?>

            <div class="row g-0 p-1">
            <?php if( $participant->account > 0) : ?>
                <?php if($participant->id==$user->id): ?>
                    <div class="col text-end ">
                        <span class="align-middle" style="font-weight:bolder"><?=$participant->full_name?>&nbsp;</span>
                    </div>
                    <div class="col">
                        <div class="progress" style=" height:28px; border-radius:0px; background-color: #FFFFFF">
                            <div class="progress-bar bg-success text-start" role="progressbar" style="width: <?=$participant->account/$sum?>%; border-radius: 0px 6px 6px 0px;" aria-valuenow= "0" aria-valuemin="0" aria-valuemax="100">
                                <span class="align-middle"  style= "font-weight:bolder; position: absolute; color: black; text-align: right ;overflow: visible;color:black;font-size:15px">&nbsp;<?=abs(round($participant->account,2))?>&nbsp;€</span>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="col text-end">
                        <span class="align-middle"><?=$participant->full_name?>&nbsp;</span>
                    </div>
                    <div class="col">
                        <div class="progress" style=" height:28px; border-radius:0px; background-color: #FFFFFF">
                            <div class="progress-bar bg-success text-start" role="progressbar" style="width: <?=$participant->account/$sum?>%; border-radius: 0px 6px 6px 0px;" aria-valuenow= "0" aria-valuemin="0" aria-valuemax="100">
                                <span class="align-middle"  style= "position: absolute; color: black; text-align: right ;overflow: visible;color:black;font-size:15px">&nbsp;<?=abs(round($participant->account,2))?>&nbsp;€</span>
                            </div>
                        </div>
                    </div>
                <?php endif;  ?>
            </div>
             
        <?php elseif($participant->account<0) : ?>
            <div class="row g-0 p-1">
            <?php if($participant->id==$user->id) : ?>
                <div class="col justify-content-end ">
                    <div class="progress" style="direction: rtl; height:28px; border-radius:0px ; background-color: #FFFFFF;">
                        <div class="progress-bar bg-danger text-end" role="progressbar" style="width: <?=abs($participant->account)/$sum?>%; border-radius: 6px 0px 0px 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            <span class="align-middle" style=  "font-weight:bolder ;position: absolute; color: black; text-align: right ;overflow: visible; color:black;font-size:15px">&nbsp;€&nbsp;<?=abs(round($participant->account,2))?>-</span>
                        </div>
                    </div>
                </div>
                <div class="col">
                        <span class="align-middle" style="font-weight:bolder ;">&nbsp;<?=$participant->full_name?>&nbsp;(me)</span>
                </div>
            </div>
            <?php else: ?>
                <div class="col justify-content-end ">
                    <div class="progress" style="direction: rtl; height:28px; border-radius:0px ; background-color: #FFFFFF;">
                        <div class="progress-bar bg-danger text-end" role="progressbar" style="width: <?=abs($participant->account)/$sum?>%; border-radius: 6px 0px 0px 6px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                            <span class="align-middle" style=  "position: absolute; color: black; text-align: right ;overflow: visible; color:black;font-size:15px">&nbsp;€&nbsp;<?=abs(round($participant->account,2))?>-</span>
                        </div>
                    </div>
                </div>
                <div class="col">
                        <span class="align-middle" >&nbsp;<?=$participant->full_name?>&nbsp;</span>
                </div>
            <?php endif;  ?>
            </div>
        <?php endif; } ?>
        </div>
    </div>
    
    
    <div class="text-center">
    <?php foreach($participants as $participant){?>           
        <?php if($participant->account==0) : ?>
            <?php if($participant->id==$user->id) : ?>
                <p class="text-center align-middle" style="font-weight:bolder ;">&nbsp;<?=$participant->full_name?>&nbsp;(me) 0€</p>
            <?php else: ?>
                <p class="text-center align-middle" >&nbsp;<?=$participant->full_name?>&nbsp; 0€</p>
            </div>
            <?php endif;?>
            
        <?php endif; }?>
    </div>
</div>
</body>
</html>