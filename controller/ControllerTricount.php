<?php 

require_once 'model/User.php';
require_once 'model/Tricount.php';
require_once 'controller/MyController.php';
require_once 'model/Operation.php';


class ControllerTricount extends MyController{

     //si l'utilisateur est connecté, redirige vers la liste de ces tricounts .
    //sinon, produit la vue d'accueil.
    public function yourTricounts(): void {
        $user = $this->get_user_or_redirect();
        $tricounts = $user->get_user_tricounts();
        (new View("listTricounts"))->show(["tricounts" => $tricounts]);
    }
    
    //ajouter un tricount apré avoir verifier que les paramettre sont passer on post 
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


            if(Tricount::tricount_title_already_exists($title, $user)){
                $errors[] = "You already have a tricount with this title. Choose another title";
            }
            // on verifie si y a pas d'erreurs et on sauve le tricount 
            if (count($errors) == 0) { 
                $tricount->persist($creator); 
                $user->add_subscription();
                $this->redirect("Tricount", "yourTricounts");

            }
        }
        (new View("addtricount"))->show(["title"=>$title,"description"=>$description, "errors" => $errors]);
    }

    public function index() : void {
            $this->get_user_or_redirect();

            $this->redirect("Tricount", "yourTricounts");
          }
        //afficher la liste des tricount d'un utilisateur 

    public function showTricount(): void{
        $user = $this->get_user_or_redirect();
        if ($this->validate_url()) {
            $tricount = Tricount::get_tricount_by_id($_GET["param1"], $user->mail);
            $operations =  $tricount->get_operations();
            $alone = false;
            $noExpenses = false;
            $participants = $tricount->get_participants();
            $operations_json = $tricount->get_operations_as_json();
            if(count($participants)==1){
                $alone = true;
            }
            if(empty($operations)){
                $noExpenses = true;
            }
            $myBalance=$tricount->get_my_total($user);
            (new View("tricount"))->show([  "tricount" => $tricount, 
                                            "operations" => $operations,
                                            "user"=>$user, 
                                            "alone" => $alone, 
                                            "noExpenses" => $noExpenses,
                                            "myBalance"=>$myBalance,
                                            "operations_json"=>$operations_json]);
        } else{
            $this->redirect("Main");
        }
    }
    
    public function show_balance(): void{
        $user=$this->get_user_or_redirect();
        if ($this->validate_url()) {    //validation url
            $tricount = Tricount::get_tricount_by_id($_GET["param1"], $user->mail);// recup tricount depuis l'id
            $participants = $tricount->get_balances(); //recuperation de la balance de chacun
            $maxUser=$participants[0];
            $sum=0;
            for($i=0;$i<sizeof($participants);++$i){
                if($participants[$i]>$maxUser){     // recuperation du user ayant payé le plus
                    $maxUser=$participants[$i];
                }
                if($participants[$i]->account>0){   //recup du total 
                    $sum+=$participants[$i]->account;
                }

            }
            (new View("balance"))->show(["participants"=>$participants,"tricount"=>$tricount,"maxUser"=>$maxUser,"sum"=>$sum/100,"total"=>$sum,"user"=>$user]);
            $participants=[];
        }else{
            $this->redirect("Main");
        }
    }

    //on fais des modification sur tricount 
    public function edit_tricount(): void{
        
        $user = $this->get_user_or_redirect();
        $errors = [];
        $success = "";
        $participants = [];
        $errorsTitle=[];
        $errorsDescription=[];
        
        
        
       if (isset($_GET["param1"]) && $_GET["param1"] !== "" && is_numeric($_GET["param1"]) && $user->is_subscribed_to_tricount($_GET["param1"])) {
        $tricount=Tricount::get_tricount_by_id($_GET["param1"]);
        $participants= $tricount->get_participants();
        $creator=$user->get_creator_of_tricount($tricount);
        $notSubParticipants=$tricount->get_users_not_sub_to_a_tricount();
        $title=$tricount->title;
        $description=$tricount->description;
        $subs_json=$tricount->get_subs_as_json();
        $not_subs_json=$tricount->get_not_subs_as_json();
            if(isset($_POST['title'])){
                $title = trim($_POST['title']);
                $description= trim($_POST['description']);
                
                $errorsTitle = $this->validate_title($title);
                $errorsDescription = $this->validate_description($description);

                if(Tricount::tricount_title_already_exists($title, $user)){
                    $errors[] = "You already have a tricount with this title. Choose another title";
                }
              
                if (count($errorsTitle) == 0 && count($errorsDescription) == 0) { 
                    $tricount->title = $title;
                    $tricount->description = $description;
                    $tricount->persist_update();
                    $this->redirect("Tricount", "showTricount",$tricount->id) ;
                    
               }   
            }           
            (new View("edit_tricount"))->show(["user" => $creator,
                                            "tricount"=>$tricount,
                                            "participants"=>$participants, 
                                            "errors" => $errors,"success"=>$success,
                                            "notSubParticipants"=>$notSubParticipants,
                                            "title"=>$title,
                                            "description"=>$description,
                                            "errorsDescription"=>$errorsDescription,
                                            "errorsTitle"=>$errorsTitle,
                                            "subs_json"=>$subs_json,
                                            "not_subs_json"=>$not_subs_json,
                                        ]);
        }
        else{
         $this->redirect("main");
       }
    }
    // sa parmet d'ajount un utilisateur dans un tricount
    public function add_participant(){
        $user = $this->get_user_or_redirect();
        if(isset($_GET["param1"]) && $_GET["param1"] !== "" && is_numeric($_GET["param1"]) && $user->is_subscribed_to_tricount($_GET["param1"])){
            $tricount=Tricount::get_tricount_by_id($_GET["param1"]);
            if(isset($_POST['participant'])){
                $participantId= $_POST['participant'];
                $participant=User::get_user_by_id($participantId);  
                $tricount->add_subscriber($participant);
                $this->redirect("Tricount","edit_tricount",$tricount->id);
            }
        }
        else{
            $this->redirect("Tricount","yourTricounts");
        }
        
    }

   
    //sa permet de delete un tricount avec toutes ses operations et ses templates
    public function delete_tricount(){
        $user = $this->get_user_or_redirect();
        if ($this->validate_url()) {
            
            $tricount = Tricount::get_tricount_by_id(( $_GET["param1"]));
        

            if(isset($_POST["yes"] )){
                $tricount->delete_tricount($user);
                $this->redirect("Tricount","yourTricounts");
            }
            if(isset($_POST["no"])){
                $this->redirect("Tricount","edit_tricount",$tricount->id);
            }
            (new View("delete_tricount_confirmation"))->show(["tricount"=>$tricount]);
            
        }
        else{
            $this->redirect();
        }
    }
    //supprimer un participant d'un tricount si il n'a fait aucune transaction
    public function delete_participant():void{
        $user = $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && $_GET["param1"] !== "" && is_numeric($_GET["param1"])  && isset($_GET["param2"]) && $_GET["param2"] !== "" && is_numeric($_GET["param2"])) {
            $participant=User::get_user_by_id($_GET["param2"]);
            if($participant->is_subscribed_to_tricount($_GET["param1"])){

                $tricount=Tricount::get_tricount_by_id($_GET["param1"]);
                $creatorOfTricount=$participant->get_creator_of_tricount($tricount);

                if(!$participant->has_already_paid($tricount)&&!$tricount->has_already_paid($participant)&&$participant->id!=$creatorOfTricount->id){ // a renommer pour le premier en has already paid et l'autre en has already participated
                    $tricount->delete_participation($participant->id);
                    $templates=$tricount->get_repartition_templates();
                    foreach($templates as $template){
                        $template->remove_user_participation_on_template($participant);
                    }
                    $this->redirect("Tricount", "edit_tricount",$tricount->id) ;
                }
                $this->redirect("Tricount", "edit_tricount",$tricount->id);
            }
            else{
                $this->redirect();
            }
        }
        $this->redirect();
    }
    //sa parmet dafficher la liste des template d'un tricount 
    public function show_templates(): void{
        $user=$this->get_user_or_redirect();
        if(isset($_GET["param1"]) && $_GET["param1"] !== "" && is_numeric($_GET["param1"]) && $user->is_subscribed_to_tricount($_GET["param1"])){
            $tricount = Tricount::get_tricount_by_id($_GET["param1"]);
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
    // ajouter un template pour un tricount 
    public function add_template(): void{
        $user = $this->get_user_or_redirect(); //si l'utilisateur n'est pas connecter redirection vers la page d'acceuille
        $title = "";
        if(isset($_GET["param1"]) && $_GET["param1"] !== "" && is_numeric($_GET["param1"]) && $user->is_subscribed_to_tricount($_GET["param1"])){
            $errors = [];
            $errorsTitle = [];
            $errorsCheckBoxes = [];
            $tricount = Tricount::get_tricount_by_id($_GET["param1"]);
            $participants = $tricount->get_participants();
            if(isset($_POST["title"]) && $_POST["title"] != ""){
                $title = trim($_POST["title"]);
                $errorsTitle = $this->validate_title($title);
                if(!$this->weights_are_numeric($_POST["weight"])){
                    $errorsCheckBoxes[] = "Weights must be numeric";
                }

                if(!isset($_POST["checkboxParticipants"])){
                    if(isset($_POST["weight"])){
                        $errorsCheckBoxes[] = "You must select at least 1 participant";
                    }
                }
                // verifie si l'URL est correct si non rederige vers index
                if($tricount->template_name_exists($_POST["title"])){
                    $errorsTitle[] = "You already have a template with this title. Choose another title";
                }
                $errors = array_merge($errors, $errorsTitle);
                $errors = array_merge($errors, $errorsCheckBoxes);
                // verifie si y a pas d'erreurs rajoute le template et rederige vers la page suivante
                if(count($errors)==0){
                    $checkboxes = $_POST["checkboxParticipants"];
                    $template = Template::add_repartition_template($title,$tricount);
                    $weight = $_POST["weight"];
                    var_dump($checkboxes);
                    var_dump($weight);
                    for($i=0; $i<sizeof($participants); ++$i){
                        for($j = 0; $j<sizeof($checkboxes);++$j){
                            if($participants[$i]->id==$checkboxes[$j]){
                                if($weight[$i]>0){
                                    $template->add_items($participants[$i], $weight[$i]);
                                }
                            }
                        }
                        
                    }
                    $this->redirect("Tricount","show_templates",$tricount->id);
                }
            }
            (new View("add_template"))->show(["title" => $title,
                                            "tricount" => $tricount,
                                            "participants" => $participants,
                                            "errorsTitle" => $errorsTitle,
                                            "errorsCheckboxes" => $errorsCheckBoxes]
            );
        }else{
            $this->redirect("Main");
        }
    }

    public function tricount_exists_service(){
        $res = "false";
        $user = $this->get_user_or_redirect();

        if(isset($_POST["newTitle"]) && $_POST["newTitle"] !== ""){
            $tricount = Tricount::tricount_title_already_exists($_POST["newTitle"],$user);
            if($tricount){
             $res = "true";
             }
         }
        echo $res;
    }

    public function add_subscriber_service(){
        $user = $this->get_user_or_redirect();
        
        if(isset($_GET["param1"]) && isset($_POST["userId"])){

            $tricount = Tricount::get_tricount_by_id($_GET["param1"]);
            $targetUser = User::get_user_by_id($_POST["userId"]);

            if(isset($_GET["param1"]) && $_GET["param1"] !== "" && is_numeric($_GET["param1"]) && !$targetUser->is_subscribed_to_tricount($_GET["param1"])){
                $tricount->add_subscriber($targetUser);
            }
            else{
                $this->redirect();
            }
        }
    }

    public function remove_subscriber_service(){
        $user = $this->get_user_or_redirect();
        if(isset($_GET["param1"]) && isset($_POST["userId"])){

        
            $tricount = Tricount::get_tricount_by_id($_GET["param1"]);
            $targetUser = User::get_user_by_id($_POST["userId"]);
            $creatorOfTricount=$user->get_creator_of_tricount($tricount);
            if(isset($_GET["param1"]) && $_GET["param1"] !== "" && is_numeric($_GET["param1"]) && $targetUser->is_subscribed_to_tricount($_GET["param1"])){
                if(!$targetUser->has_already_paid($tricount)&&$targetUser->id!=$creatorOfTricount->id){
                    $tricount->delete_participation($targetUser->id);
                }            
            }
            else{
                $this->redirect();
            }
        } else {
            $this->redirect();
        }
    }



}
?>