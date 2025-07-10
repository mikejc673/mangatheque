<?php
class ControllerAuth {
    public function register() {
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['pseudo']) || empty($_POST['email']) || empty($_POST['password'])) {
                $_SESSION['error'] = "Tous les champs doivent être remplis.";
                header('Location: /mangatheque/register');
                exit;
            }
            $pseudo = trim($_POST['pseudo'] );
            $email = filter_var(trim($_POST['email'] ), FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password'] );

            $modelUser = new ModelUser();
            $success = $modelUser->createUser($pseudo, $email, $password);
            
            if ($success) {
                $_SESSION['success'] = "Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.";
                header('Location: /mangatheque/');
                exit;
            } else {
                $_SESSION['error'] = "Une erreur est survenue lors de l'enregistrement. Veuillez réessayer.";
                header('Location: /mangatheque/register');
                exit;
            }

        }
        require __DIR__.'/../view/auth/register.php';
    }
}