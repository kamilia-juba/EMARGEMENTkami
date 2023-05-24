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
            $items = $template->get_items();
            $participants = $tricount->get_participants(); // recupere les participant du tricount 
            $weights = $this->initialize_weights($participants, $items);
            $checkbox_checked = $this->initialize_checkboxes($participants, $items);
            $title = $template->title;
            if(isset($_POST["weight"])){
                $checkbox_checked = $this->update_checkbox_checked($participants);
                $weights = $this->update_weights($participants);
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

                    for($i = 0 ; $i < sizeof($participants); ++ $i){
                        for($j = 0; $j<sizeof($checkboxes);++$j){
                            if($participants[$i]->id==$checkboxes[$j]){
                                if($weights[$i]>0){
                                    $template->add_items($participants[$i],$weights[$i]);
                                }
                            }
                        }
                    }
                    $this->redirect("Tricount", "show_templates", $tricount->id);

                }

            }
            (new View("edit_template"))->show(["participants" => $participants,
                                                 "errorsTitle" => $errorsTitle,
                                                 "errorsCheckboxes" => $errorsCheckboxes,
                                                 "template"=>$template,
                                                 "tricount"=>$tricount,
                                                 "tricountId"=>$tricount->id,
                                                 "user"=>$user,
                                                 "title"=>$title,
                                                 "checkbox_checked" => $checkbox_checked,
                                                 "weights" => $weights,
                                                 "justvalidate" => $justvalidate,
                                                 "sweetalert" => $sweetalert]
            );

        }else{
            $this->redirect("Main");
        }
    }

    private function initialize_weights(array $participants, array $items){
        $res = [];

        foreach($participants as $participant){
            $found = false;

            foreach($items as $item){
                if($participant->id == $item->user->id){
                    $res[] = $item->weight;
                    $found = true;
                    break;
                }
            }

            if(!$found){
                $res[] = 0;
            }
        }
        
        return $res;
    }

    private function update_weights(array $participants){
        $weights = [];
        for($i = 0; $i < sizeof($participants); ++$i){
            $weights[$i] = $_POST["weight"][$i];
        }
        return $weights;
    }

    private function initialize_checkboxes(array $participants, array $items): array{
        $checkboxes_checked = [];

        for($i=0; $i < sizeof($participants); ++$i){
            for($j = 0; $j < sizeof($items); ++$j){
                if($participants[$i]->id === $items[$j]->user->id){
                    $checkboxes_checked[$i] = "checked";
                    break;
                }else{
                    $checkboxes_checked[$i] = "";
                }
            }
        }
        return $checkboxes_checked;
    }

    private function update_checkbox_checked(array $participants): array{
        $checkbox_checked = [];

        for($i = 0; $i < sizeof($participants); ++$i){
            if(isset($_POST["checkboxParticipants"])){
                for($j = 0; $j < sizeof($_POST["checkboxParticipants"]); ++$j){
                    if($_POST["checkboxParticipants"][$j] == $participants[$i]->id){
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

        return $checkbox_checked;
    }

    public function template_exists_service(){
        if($this->validate_url()){
            $res = "false";
            $template = Template::get_template_by_name_and_tricountId($_POST["newTitle"], $_POST["tricountId"]);
            if($template){
                $res = "true";
            }
            echo $res;
        }else {
            $this->redirect();
        }
        
    }

    public function template_title_other_exists_service(){
        if($this->validate_url()){
            $res = "false";
            $template = Template::get_template_by_id($_POST["templateId"]);
    
            if($template->template_name_exists($_POST["newTitle"])){
                $res = "true";
            }
            echo $res;
        }else{
            $this->redirect();
        }
        
    }

    public function user_participates_service(){
        if($this->validate_url()){
            $res = "false";
            $template = Template::get_template_by_id($_POST["templateId"]);
            $user = User::get_user_by_id($_POST["userId"]);
    
            if($template->user_participates($user)){
                $res = "true";
            }
            echo $res;
        }else{
            $this->redirect();
        }
        
    }

    public function get_user_weight_service(){
        if($this->validate_url()){
            $res = 0;
            $template = Template::get_template_by_id($_POST["templateId"]);
            $user = User::get_user_by_id($_POST["userId"]);
    
            $res += $template->get_weight_from_template($user);
            
            echo $res;
        }else {
            $this->redirect();
        }
    }

    public function delete_template_service(){
        if($this->validate_url()){
            $template = Template::get_template_by_id($_GET["param2"]);
            $template->remove_template();
        }else {
            $this->redirect();
        }
    }
}

?>