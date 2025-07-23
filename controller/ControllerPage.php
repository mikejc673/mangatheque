<?php
class ControllerPage {
    public function homePage() {
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        
        if (!isset($_SESSION['id'])) {
            
            header('Location: /mangatheque/login');
            exit();
        }

        
        $modelUser = new ModelUser();
        $users = $modelUser->getUsers();

        require __DIR__ . '/../view/page/homepage.php';
    }
}