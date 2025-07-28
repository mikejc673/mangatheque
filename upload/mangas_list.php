<?php
session_start();
require_once 'db_connect.php';

$mangas = [];
$sql = "SELECT id, title, author, genre, cover_image FROM mangas ORDER BY title ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $mangas[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Mangas - Ma Mangathèque</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 960px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; margin-bottom: 20px; }
        .btn-add { display: inline-block; background-color: #28a745; color: white; padding: 10px 15px; border-radius: 4px; text-decoration: none; margin-bottom: 20px; }
        .btn-add:hover { background-color: #218838; }
        .manga-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; }
        .manga-item { border: 1px solid #ddd; border-radius: 8px; overflow: hidden; background-color: #fff; box-shadow: 0 1px 5px rgba(0,0,0,0.05); }
        .manga-item img { width: 100%; height: 250px; object-fit: cover; display: block; }
        .manga-info { padding: 15px; }
        .manga-info h3 { margin-top: 0; margin-bottom: 10px; font-size: 1.2em; color: #007bff; }
        .manga-info p { margin: 5px 0; font-size: 0.9em; color: #666; }
        .manga-actions { display: flex; justify-content: space-around; padding: 10px; border-top: 1px solid #eee; }
        .manga-actions a { text-decoration: none; padding: 5px 10px; border-radius: 4px; font-size: 0.9em; }
        .btn-detail { background-color: #007bff; color: white; }
        .btn-detail:hover { background-color: #0056b3; }
        .no-mangas { text-align: center; color: #666; margin-top: 50px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Liste de tous les Mangas</h2>
        <a href="mangas_create.php" class="btn-add">Ajouter un nouveau Manga</a>
        <?php if (!empty($mangas)): ?>
            <div class="manga-grid">
                <?php foreach ($mangas as $manga): ?>
                    <div class="manga-item">
                        <img src="<?php echo htmlspecialchars($manga['cover_image'] ?: 'placeholder.png'); ?>" alt="Couverture de <?php echo htmlspecialchars($manga['title']); ?>">
                        <div class="manga-info">
                            <h3><?php echo htmlspecialchars($manga['title']); ?></h3>
                            <p><strong>Auteur:</strong> <?php echo htmlspecialchars($manga['author']); ?></p>
                            <p><strong>Genre:</strong> <?php echo htmlspecialchars($manga['genre']); ?></p>
                        </div>
                        <div class="manga-actions">
                            <a href="mangas_detail.php?id=<?php echo $manga['id']; ?>" class="btn-detail">Voir Détails</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="no-mangas">Aucun manga trouvé dans votre collection. Ajoutez-en un !</p>
        <?php endif; ?>
        <p style="text-align: center; margin-top: 30px;"><a href="dashboard.php">Retour au Tableau de Bord</a></p>
    </div>
</body>
</html>