<?php 
require_once 'model/Operation.php';
require_once 'controller/MyController.php';
require_once 'model/User.php';
require_once 'model/Tricount.php';

class ControllerTemplate extends Mycontroller{

    public function index() : void {
        $this->get_user_or_redirect();

        $this->redirect("Tricount", "yourTricounts");
    }
    //verifie si l'utilisateur est connecter il suprime le template si non rediriger vers index 
    public function deleteTemplate(){
        $user = $this->get_user_or_redirect();
        if($this->validate_url()){
            $tricount = Tricount::get_tricount_by_id($_GET["param1"],$user->mail);// recupere le tricount 
            $template = Template::get_template_by_id($_GET["param2"]);// recupere le template 
            if(isset($_POST["yes"])){
                $template->remove_template();
                $this->redirect("Tricount", "showTemplates", $tricount->id);
            }else if(isset($_POST["no"])){
                $this->redirect("Template", "edit_template", $tricount->id, $template->id);
            }

            (new View("delete_template"))->show(
                                                ["tricount" => $tricount,
                                                "template" => $template]
                                            );

        }else{
            $this->redirect("Main");
        }
    }
    // permet de faire des modification dans le template 
    public function edit_template(){
        $user=$this->get_user_or_redirect();
        $errors = [];
        $errorsTitle = [];
        $errorsCheckboxes = [];

        if ($this->validate_url()){
            $tricount = Tricount::get_tricount_by_id($_GET["param1"]); // recuper le tricount 
            $template = Template::get_template_by_id($_GET["param2"]);// recupere le template 
            $participants = $tricount->get_participants(); // recupere les participant du tricount 
            $participants_and_weights = [];
            $title = $template->title;
            foreach($participants as $participant){
                // renseigne le poid de chaque participant dans le tricount 
                $participants_and_weights[] = [$participant, $template->get_weight_from_template($participant) == null ? 0 : $template->get_weight_from_template($participant), $participant->user_participates_to_repartition($template)];
            }
            if(isset($_POST["title"])){
                $title = trim($_POST["title"]);

                if($template->template_name_exists($title)){
                    $errorsTitle[] = "Choose another title, this title already exists";
                }
                $errorsTitle = array_merge($errorsTitle,$this->validate_title($title));

                if(!isset($_POST["checkboxParticipants"])){
                    if(isset($_POST["weight"])){
                        $errorsCheckboxes[] = "You must select at least 1 participant";
                    }
                }

                if(!$this->weightsAreNumeric($_POST["weight"])){
                    $errorsCheckboxes[] = "Weights must be numeric";
                }
                $errors = array_merge($errors,$errorsTitle);
                $errors = array_merge($errors, $errorsCheckboxes);

                // verifie si ya pas d'erreurs des save les difications apporter
                if(count($errors)==0){
                    $checkboxes = $_POST["checkboxParticipants"];
                    $weights = $_POST["weight"];

                    $template->update_template($title);
                    $template->remove_items();

                    for($i = 0 ; $i < sizeof($participants_and_weights); ++ $i){
                        for($j = 0; $j<sizeof($checkboxes);++$j){
                            if($participants_and_weights[$i][0]->id==$checkboxes[$j]){
                                if($weights[$i]>0){
                                    $participants_and_weights[$i][1] = $weights[$i];
                                    $template->add_items($participants_and_weights[$i][0],$participants_and_weights[$i][1]);
                                }
                            }
                        }
                    }
                    $this->redirect("Tricount", "showTemplates", $tricount->id);

                }

            }
            (new View("edit_template"))->show(["participants_and_weights" => $participants_and_weights,
                                                 "errorsTitle" => $errorsTitle,
                                                 "errorsCheckboxes" => $errorsCheckboxes,
                                                 "template"=>$template,
                                                 "tricount"=>$tricount,
                                                 "user"=>$user,
                                                 "title"=>$title]
            );

        }else{
            $this->redirect("Main");
        }
    }

    public function template_exists_service(){
        $res = "false";
        $template = Template::get_template_by_name_and_tricountId($_POST["newTitle"], $_POST["tricountId"]);
        if($template){
            $res = "true";
        }
        echo $res;
    }

    public function template_title_other_exists_service(){
        $res = "false";
        $template = Template::get_template_by_id($_POST["templateId"]);

        if($template->template_name_exists($_POST["newTitle"])){
            $res = "true";
        }
        echo $res;
    }
}

?>