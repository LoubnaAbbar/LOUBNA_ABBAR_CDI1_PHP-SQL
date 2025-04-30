<?php
// J'inclus mon fichier de connexion à la base de données 
require_once "login.php";

// Gère la déconnexion : si un paramètre GET 'action' est défini à "deconnexion"
if(isset($_GET["action"]) && $_GET["action"] == "deconnexion") {
    session_destroy(); // Je détruis la session en cours
    header("Location: index.html"); // Je redirige l’utilisateur vers la page d’accueil
    exit;
}

// Je prépare une variable pour afficher les erreurs éventuelles
$error = null;

// Je vérifie si le formulaire a été soumis avec la méthode POST
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Je récupère et sécurise les données saisies par l’utilisateur
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Je vérifie que les deux champs sont bien remplis
    if(!empty($email) && !empty($password)) {
        
        // Je prépare une requête pour chercher l’utilisateur correspondant à l’email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Si l’utilisateur existe et que le mot de passe correspond
        if($user && password_verify($password, $user["password"])) {
            
            // Je crée une session pour l’utilisateur connecté
            $_SESSION["iduser"] = $user["id"];
            $_SESSION["email"] = $user["email"];

            // Je le redirige vers sa page profil
            header("Location: profil.php");
            exit;
        } else {
            // Cas où l’email ou le mot de passe est incorrect
            $error = "Email ou mot de passe incorrect";
        }
    } else {
        // Cas où l’utilisateur n’a pas rempli tous les champs
        $error = "Tous les champs sont obligatoires";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Musée d'Orsay</title>
    <link rel="stylesheet" href="connexion.css">
    <link rel="stylesheet" href="dark-mode.css" id="dark-mode-stylesheet" disabled>
    <style>
        body, body * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
        .error-message {
            color: red;
            margin-bottom: 15px;
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
            <form method="POST" action="connexion.php">
                <h2>Connexion</h2>
                
                <?php if($error): ?>
                    <div class="error-message"><?= $error ?></div>
                <?php endif; ?>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" required>

                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" required>

                <input type="submit" value="Se connecter">
            </form>
        </main>
    </div>

    <script src="dark-mode.js"></script>
</body>
</html>