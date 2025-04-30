<?php

// Définition des paramètres de connexion à la base de données

$host = 'localhost';          // J'utilise le serveur local
$dbname = 'musee_orsay';      // C’est le nom de ma base de données
$username = 'root';           // Nom d’utilisateur par défaut pour WAMP
$password = '';               // Mot de passe vide par défaut
$port = 3306;                 // Port MySQL par défaut


try {
    // Je crée une instance PDO pour me connecter à la BDD
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Je configure PDO pour qu’il affiche les erreurs sous forme d'exceptions 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // var_dump utilisé temporairement pour vérifier que la connexion fonctionne 
    var_dump($pdo);

} catch (PDOException $e) {
    // Si la connexion échoue, j’affiche un message d’erreur 
    die("Erreur de connexion : " . $e->getMessage());
}


// Démarrage de la session pour suivre l’utilisateur connecté
session_start();
