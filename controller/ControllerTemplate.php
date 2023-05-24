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
                $this->redirect("Tricount", "show_templates", $tricount->id);
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
        $justvalidate = $this->get_justvalidate_conf();
        $sweetalert = $this->get_sweetalert_conf();

        if ($this->validate_url()){
            $tricount = Tricount::get_tricount_by_id($_GET["param1"]); // recuper le tricount 
            $template = Template::get_template_by_id($_GET["param2"]);// recupere le template 
            $participants = $tricount->get_participants(); // recupere les participant du tricount 
            $participants_and_weights = [];
            $checkbox_checked = [];
            $title = $template->title;
            for($i = 0; $i < sizeof($participants); ++$i){
                $participant = $participants[$i];
                // renseigne le poid de chaque participant dans le tricount 
                $participants_and_weights[] = [$participant, $template->get_weight_from_template($participant) == null ? 0 : $template->get_weight_from_template($participant)];
                $checkbox_checked[$i] = $participant->user_participates_to_repartition($template) ? "checked" : "";
            }
            if(isset($_POST["weight"])){
                for($i=0; $i < sizeof($participants); ++$i){
                    $participants_and_weights[$i][1] = $_POST["weight"][$i];
                    if(isset($_POST["checkboxParticipants"])){
                        for($j = 0; $j < sizeof($_POST["checkboxParticipants"]); ++$j){
                            if($_POST["checkboxParticipants"][$j] == $participants_and_weights[$i][0]->id){
                                $checkbox_checked[$i] = "checked";
                                break;
                            }else{
                                $checkbox_checked[$i] = "";
                            }
                        }
                    }else{
                        $checkbox_checked[$i] = "";
                    }
                }
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

                if(!$this->weights_are_numeric($_POST["weight"])){
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
                    $this->redirect("Tricount", "show_templates", $tricount->id);

                }

            }
            (new View("edit_template"))->show(["participants_and_weights" => $participants_and_weights,
                                                 "errorsTitle" => $errorsTitle,
                                                 "errorsCheckboxes" => $errorsCheckboxes,
                                                 "template"=>$template,
                                                 "tricount"=>$tricount,
                                                 "tricountId"=>$tricount->id,
                                                 "user"=>$user,
                                                 "title"=>$title,
                                                 "checkbox_checked" => $checkbox_checked,
                                                 "justvalidate" => $justvalidate,
                                                 "sweetalert" => $sweetalert]
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

    public function user_participates_service(){
        $res = "false";
        $template = Template::get_template_by_id($_POST["templateId"]);
        $user = User::get_user_by_id($_POST["userId"]);

        if($template->user_participates($user)){
            $res = "true";
        }
        echo $res;
    }

    public function get_user_weight_service(){
        $res = 0;
        $template = Template::get_template_by_id($_POST["templateId"]);
        $user = User::get_user_by_id($_POST["userId"]);

        $res += $template->get_weight_from_template($user);
        
        echo $res;
    }

    public function delete_template_service(){
        if($this->validate_url()){
            $template = Template::get_template_by_id($_GET["param1"]);
            $template->remove_template();
        }else {
            $this->redirect();
        }
    }
}

?>