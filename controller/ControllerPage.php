<?php
class ControllerPage {
    public function homePage() {
        // Vérifie si la session est démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifie si l'utilisateur est connecté
        if (!isset($_SESSION['id'])) {
            // Redirige vers la page de connexion si non connecté
            header('Location: /mangatheque/login');
            exit();
        }

        // Si connecté, charge les données et affiche la page
        $modelUser = new ModelUser();
        $users = $modelUser->getUsers();

        require __DIR__ . '/../view/page/homepage.php';
    }
}