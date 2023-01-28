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
            $errors = $tricount->valide_title($title);
            
            if (count($errors) == 0) { 
                $tricount->persist($creator); //sauve le tricount
                (new View("addFreinds"))->show(["title"=>$title,"description"=>$description, "errors" => $errors]);

               
            }
            else{
            (new View("addtricount"))->show(["title"=>$title,"description"=>$description, "errors" => $errors]);

            }
        }
        else {

        (new View("addtricount"))->show(["title"=>$title,"description"=>$description, "errors" => $errors]);
        }
        
        
    }
    public function index() : void {
          }

    public function showTricount(): void{
        $user = $this->get_user_or_redirect();
        if (isset($_GET["param1"]) && $_GET["param1"] !== "" && $user->isSubscribedToTricount($_GET["param1"])) {
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
            (new View("tricount"))->show(["tricount" => $tricount, "operations" => $operations,"user"=>$user, "alone" => $alone, "noExpenses" => $noExpenses]);
        } else{
            $this->redirect("Main");
        }
    }
    
    public function showBalance(): void{
        $user=$this->get_user_or_redirect();
        if (isset($_GET["param1"]) && $_GET["param1"] !== "" && $user->isSubscribedToTricount($_GET["param1"])) {
            $tricount = Tricount::getTricountById($_GET["param1"], $user->mail);
            $participants = $tricount->get_balances($_GET["param1"]);
            (new View("balance"))->show(["participants"=>$participants,"tricount"=>$tricount]);
        }else{
            $this->redirect("Main");
        }
    }

    public function showTemplates(): void{
        $user=$this->get_user_or_redirect();
        if(isset($_GET["param1"]) && $_GET["param1"] !== "" && $user->isSubscribedToTricount($_GET["param1"])){
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
}
?>