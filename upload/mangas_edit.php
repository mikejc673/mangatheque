<?php
session_start();
require_once 'db_connect.php';

$id = $title = $author = $genre = $description = $cover_image = "";
$title_err = $success_message = "";
$manga = null;

if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = trim($_GET["id"]);

    // Récupérer les détails du manga pour pré-remplir le formulaire
    $sql = "SELECT id, title, author, genre, description, cover_image FROM mangas WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $param_id);
        $param_id = $id;
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($result->num_rows == 1) {
                $manga = $result->fetch_assoc();
                $title = $manga['title'];
                $author = $manga['author'];
                $genre = $manga['genre'];
                $description = $manga['description'];
                $cover_image = $manga['cover_image'];
            } else {
                echo "Manga non trouvé.";
                exit();
            }
        } else {
            echo "Oops! Quelque chose a mal tourné. Veuillez réessayer plus tard.";
            exit();
        }
        $stmt->close();
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Traitement de la soumission du formulaire
    $id = $_POST["id"];

    // Valider le titre
    if (empty(trim($_POST["title"]))) {
        $title_err = "Veuillez entrer le titre du manga.";
    } else {
        $title = trim($_POST["title"]);
    }

    $author = trim($_POST["author"]);
    $genre = trim($_POST["genre"]);
    $description = trim($_POST["description"]);
    $current_cover_image = $_POST["current_cover_image"]; // Récupérer le chemin de l'image actuelle

    $new_cover_image_path = $current_cover_image; // Par défaut, conserver l'ancienne image

    // Gestion de l'upload d'image (mise à jour simplifiée)
    if (isset($_FILES["cover_image"]) && $_FILES["cover_image"]["error"] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["cover_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $uploadOk = 1;

        $check = getimagesize($_FILES["cover_image"]["tmp_name"]);
        if ($check === false) { $uploadOk = 0; }
        if ($_FILES["cover_image"]["size"] > 5000000) { $uploadOk = 0; }
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) { $uploadOk = 0; }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_file)) {
                $new_cover_image_path = $target_file;
            } else {
                // Erreur d'upload
            }
        }
    }

    // Vérifier les erreurs avant de mettre à jour la base de données
    if (empty($title_err)) {
        $sql = "UPDATE mangas SET title = ?, author = ?, genre = ?, description = ?, cover_image = ? WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssssi", $param_title, $param_author, $param_genre, $param_description, $param_cover_image, $param_id);

            $param_title = $title;
            $param_author = $author;
            $param_genre = $genre;
            $param_description = $description;
            $param_cover_image = $new_cover_image_path;
            $param_id = $id;

            if ($stmt->execute()) {
                $success_message = "Manga mis à jour avec succès !";
                // Récupérer les données mises à jour pour les afficher
                $manga['title'] = $title;
                $manga['author'] = $author;
                $manga['genre'] = $genre;
                $manga['description'] = $description;
                $manga['cover_image'] = $new_cover_image_path;
            } else {
                echo "Oops! Quelque chose a mal tourné. Veuillez réessayer plus tard.";
            }
            $stmt->close();
        }
    }
} else {
    // Rediriger si l'ID n'est pas fourni dans la requête GET et que ce n'est pas un POST
    header("location: mangas_list.php");
    exit();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Manga - Ma Mangathèque</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .wrapper { background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 450px; }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; color: #555; }
        input[type="text"], textarea, input[type="file"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 5px;
        }
        textarea { resize: vertical; min-height: 80px; }
        .help-block { color: red; font-size: 0.9em; margin-top: 5px; display: block; }
        .success-message { color: green; text-align: center; margin-bottom: 15px; }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 1em;
            margin-top: 10px;
        }
        .btn:hover { background-color: #0056b3; }
        .link-back { display: block; text-align: center; margin-top: 20px; color: #007bff; text-decoration: none; }
        .link-back:hover { text-decoration: underline; }
        .current-cover { text-align: center; margin-bottom: 15px; }
        .current-cover img { max-width: 150px; height: auto; border: 1px solid #ddd; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Modifier le Manga</h2>
        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if ($manga): ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $manga['id']; ?>">
            <input type="hidden" name="current_cover_image" value="<?php echo htmlspecialchars($manga['cover_image']); ?>">

            <div class="form-group">
                <label>Titre</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>">
                <span class="help-block"><?php echo $title_err; ?></span>
            </div>
            <div class="form-group">
                <label>Auteur</label>
                <input type="text" name="author" value="<?php echo htmlspecialchars($author); ?>">
            </div>
            <div class="form-group">
                <label>Genre</label>
                <input type="text" name="genre" value="<?php echo htmlspecialchars($genre); ?>">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description"><?php echo htmlspecialchars($description); ?></textarea>
            </div>
            <div class="form-group">
                <label>Image de couverture actuelle</label>
                <?php if ($cover_image): ?>
                    <div class="current-cover">
                        <img src="<?php echo htmlspecialchars($cover_image); ?>" alt="Couverture actuelle">
                    </div>
                <?php else: ?>
                    <p>Pas d'image de couverture actuelle.</p>
                <?php endif; ?>
                <label>Nouvelle image de couverture (laissée vide pour conserver l'actuelle)</label>
                <input type="file" name="cover_image">
            </div>
            <div class="form-group">
                <input type="submit" class="btn" value="Mettre à jour le Manga">
            </div>
            <a href="mangas_detail.php?id=<?php echo $manga['id']; ?>" class="link-back">Annuler et revenir aux détails</a>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>