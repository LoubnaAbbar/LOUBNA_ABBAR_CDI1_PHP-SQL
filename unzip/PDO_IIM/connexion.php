<?php
// J'inclus le fichier login.php qui me connecte à ma base de données
require_once("login.php");

// Si l'utilisateur est déjà connecté, je le redirige directement vers sa page profil
if(isset($_SESSION["iduser"])) {
    header("location:profil.php");
}

// Je vérifie si le formulaire a été envoyé
if ($_POST) {

    // Je récupère les champs email et mot de passe du formulaire en les nettoyant (trim pour éviter les espaces)
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Je vérifie que les deux champs sont bien remplis
    if ($email && $password) {

        // Je fais une requête pour récupérer l'utilisateur correspondant à l'email saisi
        // Ici j’utilise query au lieu de prepare pour améliorer pour la sécurité 
        $stmt = $pdo->query("SELECT * FROM user WHERE email = '$email' ");
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Je récupère les données de l'utilisateur en tableau associatif

        // Je vérifie que l'utilisateur existe et que le mot de passe saisi correspond à celui stocké (haché) en base
        if ($user && password_verify($password, $user["password"])) {

            // Si tout est bon, je crée une session avec son id et son email
            $_SESSION["iduser"] = $user["iduser"];
            $_SESSION["email"] = $user["email"];

            // Puis je le redirige vers la page profil
            header("location:profil.php");

        } else {
            // Sinon, je lui affiche un message d’erreur
            echo "La connexion a échoué !";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>

    <h1>Connexion</h1>

    <?php if (!isset($_SESSION["iduser"])) { ?>
       
        <form method="POST">

            <label for="email">Email :</label>
            <input type="text" name="email" id="email" placeholder="Email">

            <label for="password">Mot de passe :</label>
            <input type="password" name="password" id="password" placeholder="Mot de passe">

            <input type="submit" value="Connexion">

        </form>
    <?php } ?>

</body>
</html>
