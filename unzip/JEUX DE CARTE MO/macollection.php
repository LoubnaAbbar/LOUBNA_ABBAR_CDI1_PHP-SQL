<?php
require_once("login.php"); // me permet de récupérer ma connexion

/// Create Read Update Delete

if ($_POST) {
    $title = $_POST["title"];
    $artist = $_POST["artist"];
    $creation_date = $_POST["creation_date"];
    $movement = $_POST["movement"];
    $on_display = isset($_POST["on_display"]) ? 1 : 0;

    try {
        if (isset($_POST['id_artwork'])) {
            // Mise à jour
            $stmt = $pdo->prepare("UPDATE artwork SET 
                title = :title,
                artist = :artist,
                creation_date = :creation_date,
                movement = :movement,
                on_display = :on_display
                WHERE id_artwork = :id_artwork");

            $stmt->execute([
                "title" => $title,
                "artist" => $artist,
                "creation_date" => $creation_date,
                "movement" => $movement,
                "on_display" => $on_display,
                "id_artwork" => $_POST['id_artwork']
            ]);
        } else {
            // Insertion
            $stmt = $pdo->prepare("INSERT INTO artwork (title, artist, creation_date, movement, on_display) 
                VALUES(:title, :artist, :creation_date, :movement, :on_display)");

            $stmt->execute([
                "title" => $title,
                "artist" => $artist,
                "creation_date" => $creation_date,
                "movement" => $movement,
                "on_display" => $on_display,
            ]);
        }

        header("Location: ".$_SERVER['PHP_SELF']);
        exit();

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if(isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id_artwork = $_GET['id_artwork'];

    try {
        $stmt = $pdo->prepare("DELETE FROM artwork WHERE id_artwork = :id_artwork");
        $stmt->execute([
            "id_artwork" => $id_artwork,
        ]);
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

if(isset($_GET['action']) && $_GET['action'] == 'modify') {
    $id_artwork = $_GET['id_artwork'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM artwork WHERE id_artwork = :id_artwork");
        $stmt->execute([
            "id_artwork" => $id_artwork,
        ]);
        $artwork = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

$stmt = $pdo->query("SELECT * FROM artwork");
$artworks = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des œuvres d'art</title>
    <link rel="stylesheet" href="macollectionphp.css">
</head>
<body>

    <header class="header">
        <a href="index.html"><img src="image/Logo 2.png" alt="Logo"></a>
        <button id="dark-mode-toggle" class="theme-toggle">
            <span class="theme-toggle-sun">☀️</span>
            <span class="theme-toggle-moon">🌙</span>
        </button>
    </header>

    <h1>Gestion des œuvres d'art</h1>

    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Artiste</th>
                <th>Date de création</th>
                <th>Mouvement</th>
                <th>Exposé</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($artworks as $art): ?>
                <tr>
                    <td><?= htmlspecialchars($art['title']) ?></td>
                    <td><?= htmlspecialchars($art['artist']) ?></td>
                    <td><?= htmlspecialchars($art['creation_date']) ?></td>
                    <td><?= htmlspecialchars($art['movement']) ?></td>
                    <td><?= $art['on_display'] ? 'Oui' : 'Non' ?></td>
                    <td>
                        <a href="?action=modify&id_artwork=<?= $art['id_artwork'] ?>">Modifier</a> | 
                        <a href="?action=delete&id_artwork=<?= $art['id_artwork'] ?>" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2><?= isset($artwork) ? 'Modifier une œuvre' : 'Ajouter une œuvre' ?></h2>
    <form method="POST">
        <?php if (isset($artwork)): ?>
            <input type="hidden" name="id_artwork" value="<?= $artwork['id_artwork'] ?>">
        <?php endif; ?>
        
        <label for="title">Titre:</label>
        <input type="text" name="title" id="title" required value="<?= htmlspecialchars($artwork['title'] ?? '') ?>">
        
        <label for="artist">Artiste:</label>
        <input type="text" name="artist" id="artist" required value="<?= htmlspecialchars($artwork['artist'] ?? '') ?>">
        
        <label for="creation_date">Date de création:</label>
        <input type="date" name="creation_date" id="creation_date" required value="<?= htmlspecialchars($artwork['creation_date'] ?? '') ?>">
        
        <label for="movement">Mouvement artistique:</label>
        <select name="movement" id="movement" required>
            <option value="Renaissance" <?= (isset($artwork) && $artwork['movement'] == 'Renaissance') ? 'selected' : '' ?>>Renaissance</option>
            <option value="Baroque" <?= (isset($artwork) && $artwork['movement'] == 'Baroque') ? 'selected' : '' ?>>Baroque</option>
            <option value="Impressionnisme" <?= (isset($artwork) && $artwork['movement'] == 'Impressionnisme') ? 'selected' : '' ?>>Impressionnisme</option>
            <option value="Cubisme" <?= (isset($artwork) && $artwork['movement'] == 'Cubisme') ? 'selected' : '' ?>>Cubisme</option>
            <option value="Surréalisme" <?= (isset($artwork) && $artwork['movement'] == 'Surréalisme') ? 'selected' : '' ?>>Surréalisme</option>
        </select>
        
        <label for="on_display">
            <input type="checkbox" name="on_display" id="on_display" value="1" <?= (isset($artwork) && $artwork['on_display']) ? 'checked' : '' ?>>
            Actuellement exposé
        </label>
        
        <input type="submit" value="<?= isset($artwork) ? 'Mettre à jour' : 'Ajouter' ?>">
    </form>

</body>
</html>