<?php

require_once("login.php");

if ($_POST) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Validation de l'email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "L'email n'est pas valide.";
        exit; // Arrêter le script si l'email n'est pas valide
    }

    // Vérification que le mot de passe n'est pas vide
    if (empty($password)) {
        echo "Le mot de passe ne peut pas être vide.";
        exit;
    }

    try {
        // Préparer la requête SQL pour insérer les données
        $sql = "INSERT INTO user (email, password) VALUES(:email, :password)";
        $stmt = $pdo->prepare($sql);

        // Exécuter la requête avec les valeurs
        $stmt->execute([
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        echo "Votre user a été correctement inséré en BDD.";

        // Redirection vers la page de connexion après un délai
        header("Location: connexion.php");
        exit; // Il est important d'utiliser exit après header pour arrêter le script

    } catch (PDOException $e) {
        // Gestion des erreurs en cas de problème d'exécution de la requête
        echo "Erreur : " . $e->getMessage();
    }
}
?>


?>


<form method="POST">

    <label for="email">Email:</label>
    <input type="text" name="email" id="email" placeholder="Email">


    <label for="password">Mot de passe:</label>
    <input type="text" name="password" id="password" placeholder="Mot de passe">

    <input type="submit" value="Inscription">

</form>

