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

        if (isset($_GET["param1"]) && $_GET["param1"] !== "" && $user->isSubscribedToTricount($_GET["param1"]) && $user->isSubscribedToTemplate($_GET["param2"]) && isset($_GET["param2"]) && $_GET["param2"] !== "" ){
            $tricount = Tricount::getTricountById($_GET["param1"]);
            $template = Template::get_template_by_id($_GET["param2"]);
            $participants = $tricount->get_participants();
            $participants_and_weights = [];
            foreach($participants as $participant){
                $participants_and_weights[] = [$participant, Template::get_weight_from_template($participant, $template) == null ? 0 : Template::get_weight_from_template($participant, $template), $participant->user_participates_to_repartition($template->id)];
            }
            if(isset($_POST["title"])){
                $title = trim($_POST["title"]);

                if($template->template_name_exists($title)){
                    $errors[] = "Choose another title, this title already exists";
                }
                $errors = array_merge($errors,$template->validate_title($title));

                if(!isset($_POST["checkboxParticipants"])){
                    if(isset($_POST["weight"])){
                        $errors[] = "You must select at least 1 participant";
                    }
                }
                if(!$this->weightsAreGreaterThanZero($_POST["weight"])){
                    $errors[] = "Weights must be greater than 0";
                }
                if(count($errors)==0){
                    $checkboxes = $_POST["checkboxParticipants"];
                    $weights = $_POST["weight"];

                    $template->update_template($title);
                    $template->remove_items();

                    for($i = 0 ; $i < sizeof($participants_and_weights); ++ $i){
                        for($j = 0; $j<sizeof($checkboxes);++$j){
                            if($participants_and_weights[$i][0]->id==$checkboxes[$j]){
                                $participants_and_weights[$i][1] = $weights[$i];
                                $template->add_items($participants_and_weights[$i][0],$participants_and_weights[$i][1]);
                            }
                        }
                    }
                    $this->redirect("Tricount", "showTemplates", $tricount->id);

                }

            }
            (new View("edit_template"))->show(["participants_and_weights" => $participants_and_weights,
                                                 "errors" => $errors,
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