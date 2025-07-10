<?php
class ControllerAuth {
    public function register() {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['pseudo']) || empty($_POST['email']) || empty($_POST['password'])) {
                header('Location: /mangatheque/register');
                exit;
            }
            $pseudo = trim($_POST['pseudo'] );
            $email = filter_var(trim($_POST['email'] ), FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password'] );

            $modelUser = new ModelUser();
            $success = $modelUser->createUser($pseudo, $email, $password);
            if ($success) {
                header('Location: /mangatheque/');
                exit;
            } else {
                header('Location: /mangatheque/register');
                exit;
            }

        }
        require __DIR__.'/../view/auth/register.php';
    }
}