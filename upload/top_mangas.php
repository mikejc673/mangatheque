<?php
session_start();
require_once 'db_connect.php';

$top_mangas = [];
$limit = 5; // Limite par défaut [cite: 20]

// Requête pour obtenir les mangas les plus ajoutés aux favoris [cite: 20]
$sql = "SELECT m.id, m.title, m.author, m.cover_image, COUNT(f.manga_id) AS favorite_count
        FROM mangas m
        JOIN favorites f ON m.id = f.manga_id
        GROUP BY m.id, m.title, m.author, m.cover_image
        ORDER BY favorite_count DESC
        LIMIT ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $top_mangas[] = $row;
        }
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top Mangas Favoris - Ma Mangathèque</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; margin-bottom: 30px; }
        .manga-item { display: flex; align-items: center; margin-bottom: 20px; padding: 15px; border: 1px solid #eee; border-radius: 8px; background-color: #fdfdfd; }
        .manga-item img { width: 80px; height: 120px; object-fit: cover; border-radius: 4px; margin-right: 20px; }
        .manga-info { flex-grow: 1; }
        .manga-info h3 { margin-top: 0; margin-bottom: 5px; color: #007bff; }
        .manga-info p { margin: 0; color: #666; font-size: 0.9em; }
        .favorite-count { font-weight: bold; color: #6f42c1; margin-left: 20px; font-size: 1.1em; }
        .no-top-mangas { text-align: center; color: #666; margin-top: 50px; }
        .link-back { display: block; text-align: center; margin-top: 30px; color: #007bff; text-decoration: none; }
        .link-back:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Top <?php echo $limit; ?> Mangas Favoris</h2>
        <?php if (!empty($top_mangas)): ?>
            <?php foreach ($top_mangas as $index => $manga): ?>
                <div class="manga-item">
                    <img src="<?php echo htmlspecialchars($manga['cover_image'] ?: 'placeholder.png'); ?>" alt="Couverture de <?php echo htmlspecialchars($manga['title']); ?>">
                    <div class="manga-info">
                        <h3><?php echo ($index + 1) . '. ' . htmlspecialchars($manga['title']); ?></h3>
                        <p>Auteur: <?php echo htmlspecialchars($manga['author']); ?></p>
                        <p>Nombre de fois ajouté aux favoris:</p>
                    </div>
                    <span class="favorite-count"><?php echo $manga['favorite_count']; ?></span>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-top-mangas">Aucun manga n'a été ajouté aux favoris pour le moment.</p>
        <?php endif; ?>
        <a href="dashboard.php" class="link-back">Retour au Tableau de Bord</a>
    </div>
</body>
</html>