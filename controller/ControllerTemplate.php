<?php 
require_once 'model/Operation.php';
require_once 'controller/MyController.php';
require_once 'model/User.php';
require_once 'model/Tricount.php';

class ControllerTemplate extends Mycontroller{

    public function index() : void {
    }

    public function deleteTemplate(){
        $user = $this->get_user_or_redirect();
        if(isset($_GET["param1"]) && $_GET["param1"] != "" && isset($_GET["param2"]) && $_GET["param2"] != "" && $user->isSubscribedToTricount($_GET["param1"])){
            $tricount = Tricount::getTricountById($_GET["param1"],$user->mail);
            $template = Template::get_template_by_id($_GET["param2"]);
            if(isset($_POST["yes"])){
                $template->remove_template();
                //a ajouter redirection vers edit_template
            }else if(isset($_POST["no"])){
                //a ajouter redirection vers edit_template
            }

            (new View("delete_template"))->show(
                                                ["tricount" => $tricount,
                                                "template" => $template]
                                            );

        }else{
            $this->redirect("Main");
        }
    }

    public function edit_template(){
        $user=$this->get_user_or_redirect();
        $errors = [];
        $disable_CBox_and_SaveTemplate = false;
        $selected_repartition = 0;



        if (isset($_GET["param1"]) && $_GET["param1"] !== "" && $user->isSubscribedToTricount($_GET["param1"]) && $user->isSubscribedToTemplate($_GET["param2"]) && isset($_GET["param2"]) && $_GET["param2"] !== "" ){
            $tricount = Tricount::getTricountById($_GET["param1"]);
            $participants_and_weights = [];
            $template = Template::get_template_by_id($_GET["param2"]);
            $participants = $tricount->get_participants();
            
                
                $selected_repartition = $template->id;
                $participants_and_weights = [];
                
                $disable_CBox_and_SaveTemplate = true;
                foreach($participants as $participant){
                    $participants_and_weights[] = [$participant, Operation::get_weight_from_template_static($participant,$template) == null ? 0 : Operation::get_weight_from_template_static($participant,$template)]; //méthode à rendre utilisable depuis le controller template
                }
                
            
            if(isset($_POST["title"])){
                $title = trim($_POST["title"]);
                //$errors = array_merge($errors,$template->validate_title($title));

                if(!isset($_POST["checkboxParticipants"])){
                    if(isset($_POST["weight"])){
                        $errors[] = "You must select at least 1 participant";
                    }
                }
       
                        for($i = 0 ; $i < sizeof($participants_and_weights); ++ $i){
                            for($j = 0; $j<sizeof($_POST["checkboxParticipants"]);++$j){
                                
                                if($participants_and_weights[$i][0]->id==$_POST["checkboxParticipants"][$j]){
                                    $template->update_item($participants_and_weights[$i][0], $participants_and_weights[$i][1]);
                                }
                            }
                        }
            

                if(count($errors)==0){
                    //change les poids dans la liste globale des participants du tricount si ils ont été checkés dans la view 
                    $checkboxes = $_POST["checkboxParticipants"];
                    $weights = $_POST["weight"];

                    for($i = 0 ; $i < sizeof($participants_and_weights); ++ $i){
                        for($j = 0; $j<sizeof($checkboxes);++$j){
                            if($participants_and_weights[$i][0]->id==$checkboxes[$j]){
                                $participants_and_weights[$i][1] = $weights[$i];
                            }
                        }
                    }
                    //$this->redirect();

                }

            }
            (new View("edit_template"))->show(["selected_repartition" => $selected_repartition,
                                                 "participants_and_weights" => $participants_and_weights,
                                                 "errors" => $errors,
                                                 "disable_CBox_and_SaveTemplate" => $disable_CBox_and_SaveTemplate,
                                                 "template"=>$template,
                                                 "tricount"=>$tricount,
                                                 "user"=>$user]
            );

        }else{
            $this->redirect("Main");
        }
    }

}

?>