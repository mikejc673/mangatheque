<?php

class ControllerAuth {



    public function register() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (empty($_POST['pseudo']) || empty($_POST['email']) || empty($_POST['password'])) {

                $_SESSION['error'] = "Tous les champs doivent être remplis.";

                header('Location: /mangatheque/register');

                exit;

            }

            $pseudo = trim($_POST['pseudo']);

            $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

            $password = trim($_POST['password']);



            $modelUser = new ModelUser();

            $success = $modelUser->createUser($pseudo, $email, $password);

            

            if ($success) {

                $_SESSION['success'] = "Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.";

                header('Location: /mangatheque/login');

                exit;

            } else {

                $_SESSION['error'] = "Une erreur est survenue lors de l'enregistrement. Veuillez réessayer.";

                header('Location: /mangatheque/register');

                exit;

            }

        }

    }



    public function login() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!empty($_POST['email']) && !empty($_POST['password'])) {

                $modelUser = new ModelUser();

                $userSuccess = $modelUser->getUserByEmail($_POST['email']);



                // Vérifiez si l'utilisateur existe avant de vérifier le mot de passe

                if ($userSuccess && password_verify($_POST['password'], $userSuccess->getPassword())) {

                    $_SESSION['success'] = 'Connexion réussie!';

                    $_SESSION['id'] = $userSuccess->getId();

                    $_SESSION['pseudo'] = $userSuccess->getPseudo();



                    header('Location: /mangatheque/');

                    exit;

                } else {

                    $_SESSION['error'] = 'Identifiants incorrects';

                    require __DIR__ . '/../view/auth/login.php';

                    exit;

                }

            }

        }

    }



    public function logout() {

        session_unset();

        session_destroy();

        header('Location: /mangatheque/login');

        exit; // Ajout d'un exit après la redirection

    }

}