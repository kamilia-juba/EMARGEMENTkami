<?php
require_once 'controller/MyController.php';


class ControllerTest extends MyController {
    public function index() : void {
        echo "<h1>Hey !</h1>";
    }
}