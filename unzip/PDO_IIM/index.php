<?php

require_once("login.php"); // Me permet de récupérer la connexion à la base de données

// Partie de test, j'affiche tous les enregistrements dans la table
//echo '<pre>'; 
//var_dump($stmt->fetchAll(PDO::FETCH_ASSOC)); 
// Cette méthode me renvoie tous les enregistrements sous forme de tableau associatif
// echo'</pre>';

///// 
///// EXÉCUTION DIRECTE (INSERTION) AVEC EXEC
/////

// Exemple d'insertion directe dans la base avec exec()
// $sql = "INSERT INTO book (title, author, date_publication, category_idcategory, disponible) 
// VALUES( 'Le petit prince', 'Sacha Lacombe', '1997-03-28', 1, TRUE )";
// $pdo->exec($sql); // exécution de la requête SQL

///// 
///// PREPARE ET EXECUTE (INSERTION)
/////

// Ici, j'utilise prepare + execute pour insérer plusieurs livres de manière sécurisée
// try{
//     $stmt = $pdo->prepare("INSERT INTO book (title, author, date_publication, category_idcategory, disponible) 
//     VALUES( :title, :author, :date_publication, :category, :disponible )");

//     // J'insère un premier livre
//     $stmt->execute([
//         "title" => "Le rouge et le noir",
//         "author" => "Standall",
//         "date_publication" => "1945-01-01",
//         "category" => 1,
//         "disponible" => TRUE,
//     ]);

//     // J'insère un deuxième livre
//     $stmt->execute([
//         "title" => "One piece",
//         "author" => "Oda",
//         "date_publication" => "1975-01-01",
//         "category" => 1,
//         "disponible" => TRUE,
//     ]);
// } catch(PDOException $e) {
//     echo $e->getMessage(); // Si erreur, je l'affiche
// }

///// 
///// TRAITEMENT DU FORMULAIRE D'INSERTION (ajout de livre)
/////

if ($_POST) {
    $title = $_POST["title"]; // Récupération du titre du livre
    $author = $_POST["author"]; // Récupération de l'auteur
    $date_publication = $_POST["date_publication"]; // Récupération de la date de publication
    // Si la case "disponible" est cochée, on met 1, sinon 0
    $disponible = isset($_POST["disponible"]) ? 1 : 0;

    try {
        // Préparation de la requête pour insérer un nouveau livre
        $stmt = $pdo->prepare("INSERT INTO book (title, author, date_publication, category_idcategory, disponible) 
        VALUES( :title, :author, :date_publication, :category, :disponible )");

        // Exécution de la requête avec les paramètres
        $stmt->execute([
            "title" => $title,
            "author" => $author,
            "date_publication" => $date_publication,
            "category" => 1, // Catégorie par défaut
            "disponible" => $disponible,
        ]);

    } catch (PDOException $e) {
        echo $e->getMessage(); // Si erreur, je l'affiche
    }
}

///// 
///// SUPPRESSION D'UN LIVRE (action delete)
/////
if(isset($_GET['action']) && $_GET['action'] == 'delete') {
    $idbook = $_GET['id_book']; // Récupération de l'ID du livre à supprimer

    try {
        // Préparation de la requête pour supprimer le livre
        $stmt = $pdo->prepare("DELETE FROM book WHERE idbook = :idbook");
        $stmt->execute([
            "idbook" => $idbook,
        ]);
        echo "Le livre a bien été supprimé !"; // Message de confirmation
    } catch (PDOException $e) {
        echo $e->getMessage(); // Si erreur, je l'affiche
    }
}

///// 
///// MODIFICATION D'UN LIVRE (action modify)
/////

if(isset($_GET['action']) && $_GET['action'] == 'modify') {
    $idbook = $_GET['id_book']; // Récupération de l'ID du livre à modifier

    try {
        // Il faut préparer une requête UPDATE ici, pas DELETE
        $stmt = $pdo->prepare("UPDATE book SET title = :title, author = :author, date_publication = :date_publication, disponible = :disponible WHERE idbook = :idbook");
        
        // Je suppose que les nouvelles valeurs viennent du formulaire ou des paramètres
        $stmt->execute([
            "title" => "Nouveau Titre",
            "author" => "Nouvel Auteur",
            "date_publication" => "2025-01-01", // Exemple de date
            "disponible" => 1, // Le livre est disponible
            "idbook" => $idbook,
        ]);
        echo "Le livre a bien été modifié !"; // Message de confirmation
    } catch (PDOException $e) {
        echo $e->getMessage(); // Si erreur, je l'affiche
    }
}

///// 
///// AFFICHAGE DES LIVRES (SELECT)
/////
$stmt = $pdo->query("SELECT * FROM book"); // Sélection de tous les livres dans la base
$books = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupération sous forme de tableau associatif

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <h1>Mes livres en BDD</h1>

    <table border="1">
        <thead>
            <th>Titre</th>
            <th>Auteur</th>
            <th>Date</th>
            <th>Catégorie</th>
            <th>Disponible</th>
            <th>Supprimer</th>
            <th>Modifier</th>
        </thead>

        <tbody>
            <?php
            foreach ($books as $key => $book) {
                echo "<tr>";
                echo "<td>" . $book["title"] . "</td>";
                echo "<td>" . $book["author"] . "</td>";
                echo "<td>" . $book["date_publication"] . "</td>";
                echo "<td>" . $book["category_idcategory"] . "</td>";
                echo "<td>" . ($book["disponible"] ? 'Oui' : 'Non') . "</td>";
                echo "<td> <a href='?id_book=". $book["idbook"] . "&action=delete'> Supprimer </a> </td>";
                echo "<td> <a href='?id_book=". $book["idbook"] . "&action=modify'> Modifier </a> </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

    <br>
    <br>
    <form method="POST">
        <label for="title">Titre:</label>
        <input type="text" name="title" id="title" placeholder="Titre">

        <label for="author">Auteur:</label>
        <input type="text" name="author" id="author" placeholder="Auteur">

        <label for="date_publication">Date de publication:</label>
        <input type="date" name="date_publication" id="date_publication">

        <label for="disponible">Disponible:</label>
        <input type="checkbox" name="disponible" id="disponible" checked>

        <input type="submit" value="Créer livre">
    </form>

</body>
</html>