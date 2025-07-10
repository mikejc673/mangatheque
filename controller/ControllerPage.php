<?php
class ControllerPage {
    public function homePage(){
        $modelUser = new ModelUser();
        $users = $modelUser->getUsers();

        require __DIR__ . '/../view/page/homepage.php';
    }
}