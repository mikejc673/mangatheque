<?php
class ControllerAuth
{
    public function register()
    {
        // Si c'est la méthode POST qu'on utilise (à vérifier avec var_dump($_SERVER))
        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            // Si c'est vide je renvoi sur le register
            if (empty($_POST['pseudo']) || empty($_POST['email']) || empty($_POST['password'])) {
                $_SESSION['error'] = "Tous les champs doivent être remplis !";
                header('Location: /mangatheque/register');
                exit;
            }

            $pseudo = trim($_POST['pseudo']);
            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password']);

            $modelUser = new ModelUser();
            $successUser = $modelUser->createUser($pseudo, $email, $password);

            if ($successUser) {
                $_SESSION['success'] = "Vous êtes bien enregistré ! Vous pouvez vous connecter !";
                header('Location: /mangatheque/login');
                exit;
            } else {
                $_SESSION['error'] = "Erreur lors de l'insertion.";
                header('Location: /mangatheque/register');
                exit;
            }
        }
        require __DIR__ . '/../view/auth/register.php';
    }

    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] === 'POST') {
            if (empty($_POST['email']) || empty($_POST['password'])) {
                $_SESSION['error'] = "Tous les champs doivent être remplis.";
                header('Location: /mangatheque/login');
                exit;
            }

            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            $modelUser = new ModelUser();
            $user = $modelUser->getUserByEmail($email);

            if ($user && password_verify($password, $user->getPassword())) {
                $_SESSION['user'] = $user;
                $_SESSION['success'] = "Connexion réussie !";
                header('Location: /mangatheque/');
                exit;
            } else {
                $_SESSION['error'] = "Identifiants invalides.";
                header('Location: /mangatheque/login');
                exit;
            }
        }

        require __DIR__ . '/../view/auth/login.php';
    }
}