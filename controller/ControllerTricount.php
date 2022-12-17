<?php 

require_once 'model/User.php';
require_once 'model/Tricount.php';
require_once 'controller/MyController.php';



class ControllerTricount extends MyController{

    public function index() : void {
    }

    public function yourTricounts(): void {
        if ($this->user_logged()) {
            $user = $this->get_user_or_redirect();
            $tricounts = $user->get_user_tricounts();
            (new View("listTricounts"))->show(["tricounts" => $tricounts]);
        } else {
            $this->redirect("Main");
        }
    }
    public function addtricount () : void {

        $title='';
        $description='';
        
        $created_at='';
        $creator= '';
        
        $errors= [];
        if ($this->user_logged()) {
            $user = $this->get_user_or_redirect();
            $tricounts = $user->get_user_tricounts();
            (new View("listTricounts"))->show(["tricounts" => $tricounts]);

        var_dump($_POST);
        if(isset($_POST['title']) && isset($_POST['description'])){
            $title = trim($_POST['title']);

            $description = trim($_POST['description']);
           

            $tricount = new Tricount($title,$created_at,$creator,$description);
            $errors = $tricount->valide_title($title);
            if (count($errors) == 0) { 
                $tricount->persist($user->id); //sauve le tricount
               
            }

        }
        
        (new View("addtricount"))->show(["title"=>$title,"description"=>$description, "errors" => $errors]);
        }
        else {
            $this->redirect("Main");
        }
    }

}



?>