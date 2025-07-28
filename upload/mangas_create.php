<?php
session_start();
require_once 'db_connect.php';

$title = $author = $genre = $description = $cover_image = "";
$title_err = $success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Valider le titre
    if (empty(trim($_POST["title"]))) {
        $title_err = "Veuillez entrer le titre du manga.";
    } else {
        $title = trim($_POST["title"]);
    }

    $author = trim($_POST["author"]);
    $genre = trim($_POST["genre"]);
    $description = trim($_POST["description"]);

    // Gestion de l'upload d'image (simplifié)
    if (isset($_FILES["cover_image"]) && $_FILES["cover_image"]["error"] == 0) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($_FILES["cover_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $uploadOk = 1;

        // Vérifier si le fichier est une image réelle
        $check = getimagesize($_FILES["cover_image"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            // echo "Le fichier n'est pas une image.";
            $uploadOk = 0;
        }

        // Vérifier la taille du fichier (ex: 5MB maximum)
        if ($_FILES["cover_image"]["size"] > 5000000) {
            // echo "Désolé, votre fichier est trop volumineux.";
            $uploadOk = 0;
        }

        // Autoriser certains formats de fichiers
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            // echo "Désolé, seuls les fichiers JPG, JPEG, PNG & GIF sont autorisés.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            // echo "Désolé, votre fichier n'a pas été uploadé.";
        } else {
            if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_file)) {
                $cover_image = $target_file;
            } else {
                // echo "Désolé, une erreur s'est produite lors de l'upload de votre fichier.";
            }
        }
    }

    // Vérifier les erreurs avant d'insérer dans la base de données
    if (empty($title_err)) {
        $sql = "INSERT INTO mangas (title, author, genre, description, cover_image) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssss", $param_title, $param_author, $param_genre, $param_description, $param_cover_image);

            $param_title = $title;
            $param_author = $author;
            $param_genre = $genre;
            $param_description = $description;
            $param_cover_image = $cover_image;

            if ($stmt->execute()) {
                $success_message = "Manga ajouté avec succès !";
                // Réinitialiser les champs du formulaire après succès
                $title = $author = $genre = $description = $cover_image = "";
            } else {
                echo "Oops! Quelque chose a mal tourné. Veuillez réessayer plus tard.";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Manga - Ma Mangathèque</title>
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
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Ajouter un Nouveau Manga</h2>
        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
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
                <label>Image de couverture</label>
                <input type="file" name="cover_image">
            </div>
            <div class="form-group">
                <input type="submit" class="btn" value="Ajouter le Manga">
            </div>
            <a href="mangas_list.php" class="link-back">Retour à la liste des Mangas</a>
        </form>
    </div>
</body>
</html>