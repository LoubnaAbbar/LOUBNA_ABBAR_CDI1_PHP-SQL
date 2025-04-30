<?php
// J'inclus mon fichier de connexion à la base de données
require_once("login.php");

// J'initialise deux variables pour gérer les messages d’erreur ou de succès
$error = null;
$success = null;

// 
// Si le formulaire a été soumis
// 
if($_POST){

    // Je récupère les données du formulaire envoyé en POST
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $birthDate = $_POST["birthDate"];
    $favoriteArtwork = $_POST["flower"]; // (j'avais nommé ce champ "flower" dans le formulaire)

    // 
    // Vérification des champs
    //

    // Si un champ obligatoire est vide, j'affiche une erreur
    if(empty($email) || empty($password) || empty($confirmPassword) || empty($firstName) || empty($lastName) || empty($birthDate)){
        $error = "Tous les champs sont obligatoires";

    // Je vérifie que l’email est dans un bon format
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Format d'email invalide";

    // Le mot de passe doit faire au moins 8 caractères
    } elseif(strlen($password) < 8){
        $error = "Le mot de passe doit contenir au moins 8 caractères";

    // Les deux mots de passe doivent être identiques
    } elseif($password !== $confirmPassword){
        $error = "Les mots de passe ne correspondent pas";

    // Je vérifie que l'utilisateur a bien sélectionné une œuvre préférée
    } elseif(empty($favoriteArtwork)){
        $error = "Veuillez sélectionner votre œuvre préférée";

    //
    // Vérification si l'email existe déjà
    //
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        
        // Si un utilisateur avec cet email existe déjà, je bloque l'inscription
        if($stmt->fetch()){
            $error = "Cet email est déjà utilisé";

        //
        // Si tout est bon, j’insère le nouvel utilisateur dans la BDD
        // 
        } else {
            $sql = "INSERT INTO users (email, password, first_name, last_name, birth_date, favorite_artwork) VALUES (:email, :password, :firstName, :lastName, :birthDate, :favoriteArtwork)";
            
            $stmt = $pdo->prepare($sql);

            // Je hache le mot de passe pour sécuriser les données dans la BDD
            $stmt->execute([
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'firstName' => $firstName,
                'lastName' => $lastName,
                'birthDate' => $birthDate,
                'favoriteArtwork' => $favoriteArtwork
            ]);
            
            // Je confirme à l’utilisateur que tout s’est bien passé
            $success = "Inscription réussie ! Redirection...";

            // Redirection automatique vers la page de connexion après 2 secondes
            header("Refresh: 2; url=connexion.php");
        }
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Musée d'Orsay</title>
    <link rel="stylesheet" href="inscription.css">
    <link rel="stylesheet" href="dark-mode.css" id="dark-mode-stylesheet" disabled>
    <style>
        body, body * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
        .success-message {
            color: green;
            margin: 15px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.html"><img class="headimg" src="image/Logo 2.png" alt=""/></a>
        <button id="dark-mode-toggle" class="theme-toggle">
            <span class="theme-toggle-sun">☀️</span>
            <span class="theme-toggle-moon">🌙</span>
        </button>
    </header>
    
    <div class="bodyInscription">
        <main>
            <form method="POST" action="inscription.php">
                <h2>Inscription</h2>
                
                <?php if ($error): ?>
                    <ul id="errorList"><li style="color: red;"><?= $error ?></li></ul>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="success-message"><?= $success ?></div>
                <?php endif; ?>

                <label for="firstName">Prénom</label>
                <input type="text" name="firstName" id="firstName" required value="<?= htmlspecialchars($_POST['firstName'] ?? '') ?>">

                <label for="lastName">Nom</label>
                <input type="text" name="lastName" id="lastName" required value="<?= htmlspecialchars($_POST['lastName'] ?? '') ?>">

                <label for="birthDate">Date de naissance</label>
                <input type="date" name="birthDate" id="birthDate" required value="<?= htmlspecialchars($_POST['birthDate'] ?? '') ?>">

                <label for="email">Email</label>
                <input type="email" name="email" id="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

                <label for="password">Mot de passe (8 caractères minimum)</label>
                <input type="password" name="password" id="password" required minlength="8">

                <label for="confirmPassword">Confirmez le mot de passe</label>
                <input type="password" name="confirmPassword" id="confirmPassword" required>

                <fieldset>
                    <legend>Quelle est votre œuvre préférée ?</legend>
                    <label><input type="radio" name="flower" value="Intérieur bleu" required> Intérieur bleu</label>
                    <label><input type="radio" name="flower" value="By Lamp Light"> By Lamp Light</label>
                    <label><input type="radio" name="flower" value="Evening, Interior"> Evening, Interior</label>
                </fieldset>

                <input type="submit" value="S'inscrire">
                
                <p style="text-align: center; margin-top: 15px;">
                    Déjà inscrit ? <a href="connexion.php">Connectez-vous</a>
                </p>
            </form>
        </main>
    </div>

    <script src="dark-mode.js"></script>
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (password.length < 8) {
                alert("Le mot de passe doit contenir au moins 8 caractères");
                e.preventDefault();
            } else if (password !== confirmPassword) {
                alert("Les mots de passe ne correspondent pas");
                e.preventDefault();
            }
        });
    </script>
</body>
</html>