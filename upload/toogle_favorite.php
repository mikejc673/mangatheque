<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

$response = ['status' => 'error', 'message' => 'Requête invalide.'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['manga_id'])) {
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        $response['message'] = 'Vous devez être connecté pour gérer vos favoris.';
        echo json_encode($response);
        exit();
    }

    $user_id = $_SESSION["id"];
    $manga_id = filter_var($_POST['manga_id'], FILTER_VALIDATE_INT);

    if ($manga_id === false) {
        $response['message'] = 'ID de manga invalide.';
        echo json_encode($response);
        exit();
    }

    // Vérifier si le manga est déjà un favori [cite: 19]
    $sql_check = "SELECT COUNT(*) FROM favorites WHERE user_id = ? AND manga_id = ?";
    if ($stmt_check = $conn->prepare($sql_check)) {
        $stmt_check->bind_param("ii", $user_id, $manga_id);
        $stmt_check->execute();
        $stmt_check->bind_result($count);
        $stmt_check->fetch();
        $stmt_check->close();

        if ($count > 0) {
            // Le manga est déjà un favori, le retirer [cite: 18]
            $sql_action = "DELETE FROM favorites WHERE user_id = ? AND manga_id = ?";
            $action_message = "Manga retiré de vos favoris.";
            $action_type = "removed";
        } else {
            // Le manga n'est pas un favori, l'ajouter [cite: 17]
            $sql_action = "INSERT INTO favorites (user_id, manga_id) VALUES (?, ?)";
            $action_message = "Manga ajouté à vos favoris.";
            $action_type = "added";
        }

        if ($stmt_action = $conn->prepare($sql_action)) {
            $stmt_action->bind_param("ii", $user_id, $manga_id);
            if ($stmt_action->execute()) {
                $response['status'] = 'success';
                $response['message'] = $action_message;
                $response['action'] = $action_type;
            } else {
                $response['message'] = 'Erreur lors de la mise à jour des favoris: ' . $conn->error;
            }
            $stmt_action->close();
        } else {
            $response['message'] = 'Erreur de préparation de la requête: ' . $conn->error;
        }
    } else {
        $response['message'] = 'Erreur de préparation de la requête de vérification: ' . $conn->error;
    }
}
$conn->close();
echo json_encode($response);
?>