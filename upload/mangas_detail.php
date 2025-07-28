<?php
session_start();
require_once 'db_connect.php';

$manga = null;
$is_favorite = false;
$user_id = $_SESSION["id"] ?? null;

if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = trim($_GET["id"]);

    // Récupérer les détails du manga
    $sql_manga = "SELECT id, title, author, genre, description, cover_image FROM mangas WHERE id = ?";
    if ($stmt_manga = $conn->prepare($sql_manga)) {
        $stmt_manga->bind_param("i", $param_id);
        $param_id = $id;
        if ($stmt_manga->execute()) {
            $result_manga = $stmt_manga->get_result();
            if ($result_manga->num_rows == 1) {
                $manga = $result_manga->fetch_assoc();
            }
        }
        $stmt_manga->close();
    }

    // Vérifier si le manga est dans les favoris de l'utilisateur actuel [cite: 19]
    if ($manga && $user_id) {
        $sql_favorite = "SELECT COUNT(*) FROM favorites WHERE user_id = ? AND manga_id = ?";
        if ($stmt_favorite = $conn->prepare($sql_favorite)) {
            $stmt_favorite->bind_param("ii", $param_user_id, $param_manga_id);
            $param_user_id = $user_id;
            $param_manga_id = $manga['id'];
            if ($stmt_favorite->execute()) {
                $stmt_favorite->bind_result($count);
                $stmt_favorite->fetch();
                if ($count > 0) {
                    $is_favorite = true;
                }
            }
            $stmt_favorite->close();
        }
    }

} else {
    // Rediriger si l'ID n'est pas fourni
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
    <title>Détails du Manga - Ma Mangathèque</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); display: flex; flex-wrap: wrap; }
        .manga-cover { flex: 1; min-width: 250px; margin-right: 30px; }
        .manga-cover img { width: 100%; height: auto; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .manga-info { flex: 2; min-width: 300px; }
        h2 { color: #007bff; margin-top: 0; margin-bottom: 15px; }
        p { margin-bottom: 10px; line-height: 1.6; color: #555; }
        p strong { color: #333; }
        .actions { margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap; }
        .btn { display: inline-block; padding: 10px 15px; border-radius: 4px; text-decoration: none; text-align: center; font-size: 0.95em; }
        .btn-edit { background-color: #ffc107; color: #333; }
        .btn-edit:hover { background-color: #e0a800; }
        .btn-delete { background-color: #dc3545; color: white; }
        .btn-delete:hover { background-color: #c82333; }
        .btn-back { background-color: #6c757d; color: white; }
        .btn-back:hover { background-color: #5a6268; }
        .btn-favorite {
            background-color: <?php echo $is_favorite ? '#6f42c1' : '#28a745'; ?>;
            color: white;
            cursor: pointer;
        }
        .btn-favorite:hover {
            background-color: <?php echo $is_favorite ? '#5a3d93' : '#218838'; ?>;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($manga): ?>
            <div class="manga-cover">
                <img src="<?php echo htmlspecialchars($manga['cover_image'] ?: 'placeholder.png'); ?>" alt="Couverture de <?php echo htmlspecialchars($manga['title']); ?>">
            </div>
            <div class="manga-info">
                <h2><?php echo htmlspecialchars($manga['title']); ?></h2>
                <p><strong>Auteur:</strong> <?php echo htmlspecialchars($manga['author']); ?></p>
                <p><strong>Genre:</strong> <?php echo htmlspecialchars($manga['genre']); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($manga['description']); ?></p>

                <div class="actions">
                    <?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
                        <a href="javascript:void(0);" onclick="toggleFavorite(<?php echo $manga['id']; ?>)" class="btn btn-favorite" id="favorite-btn">
                            <?php echo $is_favorite ? 'Retirer des favoris' : 'Ajouter aux favoris'; 
                            ?> [cite: 11, 12]
                        </a>
                    <?php endif; ?>
                    <a href="mangas_edit.php?id=<?php echo $manga['id']; ?>" class="btn btn-edit">Modifier</a>
                    <a href="mangas_delete.php?id=<?php echo $manga['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce manga ?');">Supprimer</a>
                    <a href="mangas_list.php" class="btn btn-back">Retour à la liste</a>
                </div>
            </div>
        <?php else: ?>
            <p>Manga non trouvé.</p>
            <a href="mangas_list.php" class="btn btn-back">Retour à la liste</a>
        <?php endif; ?>
    </div>

    <script>
        function toggleFavorite(mangaId) {
            fetch('toggle_favorite.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'manga_id=' + mangaId
            })
            .then(response => response.json())
            .then(data => {
                const favoriteBtn = document.getElementById('favorite-btn');
                if (data.status === 'success') {
                    if (data.action === 'added') {
                        favoriteBtn.textContent = 'Retirer des favoris';
                        favoriteBtn.style.backgroundColor = '#6f42c1'; // Couleur pour "Retirer"
                    } else {
                        favoriteBtn.textContent = 'Ajouter aux favoris';
                        favoriteBtn.style.backgroundColor = '#28a745'; // Couleur pour "Ajouter"
                    }
                    alert(data.message);
                } else {
                    alert('Erreur: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue lors de la mise à jour des favoris.');
            });
        }
    </script>
</body>
</html>