<?php
session_start();
require_once 'db_connect.php';

if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = trim($_GET["id"]);

    // Préparer une instruction DELETE
    $sql = "DELETE FROM mangas WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $param_id);
        $param_id = $id;

        if ($stmt->execute()) {
            // Rediriger vers la liste des mangas après suppression
            header("location: mangas_list.php");
            exit();
        } else {
            echo "Oops! Quelque chose a mal tourné. Veuillez réessayer plus tard.";
        }
        $stmt->close();
    }
} else {
    // Rediriger si l'ID n'est pas fourni
    header("location: mangas_list.php");
    exit();
}
$conn->close();
?>