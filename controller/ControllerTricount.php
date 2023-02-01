<?php 

require_once 'model/User.php';
require_once 'model/Tricount.php';
require_once 'controller/MyController.php';
require_once 'model/Operation.php';


class ControllerTricount extends MyController{

    

    public function yourTricounts(): void {
        $user = $this->get_user_or_redirect();
        $tricounts = $user->get_user_tricounts();
        (new View("listTricounts"))->show(["tricounts" => $tricounts]);
    }
    
    
    public function addtricount () : void {
        $user = $this->get_user_or_redirect();
        $title='';
        $description='';
        
        $created_at='55';
        $creator=$user->id;
        
        $errors= [];
        if(isset($_POST['title']) ){
            $title = trim($_POST['title']);

            $description = trim($_POST['description']);

            $tricount = new Tricount($title,$created_at,$creator,$description);
            $errors = $this->validate_title($title);
            $errors = array_merge($errors,$this->validate_description($description));


            if(Tricount::tricountTitleAlreadyExists($title, $user)){
                $errors[] = "You already have a tricount with this title. Choose another title";
            }
            
            if (count($errors) == 0) { 
                $tricount->persist($creator); //sauve le tricount
                $user->add_subscription();
                $this->redirect("Tricount", "yourTricounts");

            }
        }
        (new View("addtricount"))->show(["title"=>$title,"description"=>$description, "errors" => $errors]);
    }

    public function index() : void {
          }

    public function showTricount(): void{
        $user = $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && $_GET["param1"] !== "" && is_numeric($_GET["param1"]) && $user->isSubscribedToTricount($_GET["param1"])) {
            $tricount = Tricount::getTricountById($_GET["param1"], $user->mail);
            $operations = Operation::get_operations_by_tricountid($tricount->id);
            $alone = false;
            $noExpenses = false;
            $participants = $tricount->get_participants();
            if(count($participants)==1){
                $alone = true;
            }
            if(empty($operations)){
                $noExpenses = true;
            }
            $myBalance=$tricount->get_my_total($user->id);
            (new View("tricount"))->show(["tricount" => $tricount, "operations" => $operations,"user"=>$user, "alone" => $alone, "noExpenses" => $noExpenses,"myBalance"=>$myBalance]);
        } else{
            $this->redirect("Main");
        }
    }
    
    public function showBalance(): void{
        $user=$this->get_user_or_redirect();
        if (isset($_GET["param1"]) && $_GET["param1"] !== "" && is_numeric($_GET["param1"]) && $user->isSubscribedToTricount($_GET["param1"])) {
            $tricount = Tricount::getTricountById($_GET["param1"], $user->mail);
            $participants = $tricount->get_balances();
            $maxUser=$participants[0];
            $sum=0;
            for($i=0;$i<sizeof($participants);++$i){
                if($participants[$i]>$maxUser){
                    $maxUser=$participants[$i];
                }
                if($participants[$i]->account>0){
                    $sum+=$participants[$i]->account;
                }

            }
            (new View("balance"))->show(["participants"=>$participants,"tricount"=>$tricount,"maxUser"=>$maxUser,"sum"=>$sum/100,"total"=>$sum,"user"=>$user]);
            $participants=[];
        }else{
            $this->redirect("Main");
        }
    }


    public function editTricount(): void{
        
        $user = $this->get_user_or_redirect();
        $errors = [];
        $success = "";
        $participants = [];
        
        
       if (isset($_GET["param1"]) && $_GET["param1"] !== "" && is_numeric($_GET["param1"]) && $user->isSubscribedToTricount($_GET["param1"])) {
        $tricount=Tricount::getTricountById($_GET["param1"]);
        $participants= $tricount->get_participants();
        $creator=$user->get_creator_of_tricount($tricount->id);
        $notSubParticipants=User::get_users_not_sub_to_a_tricount($tricount->id);
       
            if(isset($_POST['title'])){
                $title = trim($_POST['title']);
                $description= trim($_POST['description']);
                $errors = $this->validate_title($title);
                if (count($errors) == 0) { 
                    $tricount->title = $title;
                    $tricount->description = $description;
                    $tricount->persistUpdate();
                    $this->redirect("Tricount", "showTricount",$tricount->id) ;
               }   
            }           
            (new View("EditTricount"))->show(["user" => $creator,"tricount"=>$tricount,"participants"=>$participants, "errors" => $errors,"success"=>$success,"notSubParticipants"=>$notSubParticipants]);

        }
        else{
         $this->redirect("main");
       }
    }

    public function add_participant(){
        $user = $this->get_user_or_redirect();
        if(isset($_GET["param1"]) && $_GET["param1"] !== "" && is_numeric($_GET["param1"]) && $user->isSubscribedToTricount($_GET["param1"])){
            $tricount=Tricount::getTricountById($_GET["param1"]);
            if(isset($_POST['participant'])){
                $participantId= $_POST['participant'];
                $participant=User::get_user_by_id($participantId);  
                $tricount->add_subscriber($participant->id);
            }
        }
        $this->redirect("Tricount","editTricount",$tricount->id);
    }

    public function confirm_delete_tricount(): void{
        $user = $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && $_GET["param1"] !== "" && is_numeric($_GET["param1"]) && $user->isSubscribedToTricount($_GET["param1"])) {
            $tricount=Tricount::getTricountById(($_GET["param1"]));
            (new View("delete_tricount_confirmation"))->show(["tricount"=>$tricount]);
        }
        else{
            $this->redirect();
        }
    }

    public function delete_tricount(): void{
        $user = $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && $_GET["param1"] !== "" && is_numeric($_GET["param1"]) && $user->isSubscribedToTricount($_GET["param1"])) {
            $tricount = Tricount::getTricountById(( $_GET["param1"]));
            $tricount->delete_tricount($user->id);
            $this->redirect();
        }
    }

    public function deleteParticipant():void{
        $user = $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && $_GET["param1"] !== "" && is_numeric($_GET["param1"])  && isset($_GET["param2"]) && $_GET["param2"] !== "" && is_numeric($_GET["param2"])) {
            $participant=User::get_user_by_id($_GET["param2"]);
            if($participant->isSubscribedToTricount($_GET["param1"])){

                $tricount=Tricount::getTricountById($_GET["param1"]);
                $creatorOfTricount=$participant->get_creator_of_tricount($tricount->id);

                if(!$participant->has_already_paid($tricount->id)&&!$tricount->has_already_paid($participant->id)&&$participant->id!=$creatorOfTricount->id){ // a renommer pour le premier en has already paid et l'autre en has already participated
                    $tricount->delete_participation($participant->id);
                    $this->redirect("Tricount", "editTricount",$tricount->id) ;
                }
                $this->redirect("Tricount", "editTricount",$tricount->id);
            }
            else{
                $this->redirect();
            }
        }
        $this->redirect();
    }

    public function showTemplates(): void{
        $user=$this->get_user_or_redirect();
        if(isset($_GET["param1"]) && $_GET["param1"] !== "" && is_numeric($_GET["param1"]) && $user->isSubscribedToTricount($_GET["param1"])){
            $tricount = Tricount::getTricountById($_GET["param1"]);
            $templates = $tricount->get_repartition_templates();
            $templates_items = [];
            foreach($templates as $template){
                $templates_items[] = [$template->get_repartition_template_users(),$template];
            }
            (new View("templates"))->show(["tricount" => $tricount, "templates_items" => $templates_items]);
        }else{
            $this->redirect("Main");
        }
    }

    public function addTemplate(): void{
        $user = $this->get_user_or_redirect();
        if(isset($_GET["param1"]) && $_GET["param1"] !== "" && is_numeric($_GET["param1"]) && $user->isSubscribedToTricount($_GET["param1"])){
            $errors = [];
            $tricount = Tricount::getTricountById($_GET["param1"]);
            $participants = $tricount->get_participants();
            if(isset($_POST["title"]) && $_POST["title"] != 0){
                $title = trim($_POST["title"]);
                $errors = array_merge($errors, $this->validate_title($title));

                if(!$this->weightsAreNumeric($_POST["weight"])){
                    $errors[] = "Weights must be numeric";
                }

                if(!isset($_POST["checkboxParticipants"])){
                    if(isset($_POST["weight"])){
                        $errors[] = "You must select at least 1 participant";
                    }
                }
                if(count($errors)==0){
                    $checkboxes = $_POST["checkboxParticipants"];
                    $template = Template::add_repartition_template($title,$tricount->id);
                    $weight = $_POST["weight"];
                    var_dump($checkboxes);
                    var_dump($weight);
                    for($i=0; $i<sizeof($participants); ++$i){
                        for($j = 0; $j<sizeof($checkboxes);++$j){
                            if($participants[$i]->id==$checkboxes[$j]){
                                $template->add_items($participants[$i], $weight[$i]);
                            }
                        }
                        
                    }
                    $this->redirect("Tricount","showTemplates",$tricount->id);
                }
            }
            (new View("add_template"))->show(["tricount" => $tricount,
                                            "participants" => $participants,
                                            "errors" => $errors]
            );
        }else{
            $this->redirect("Main");
        }
    }
}
?>