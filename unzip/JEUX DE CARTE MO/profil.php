<?php
// J'inclus le fichier login.php qui initialise la connexion à la base de données
require_once("login.php");

//
// Vérification de la session
//

// Si l'utilisateur n'est pas connecté, je le redirige vers la page de connexion
if(!isset($_SESSION["iduser"])) {
    header("Location: connexion.php"); // Redirection forcée pour sécuriser l'accès à cette page
    exit; // Je stoppe le script pour éviter que le reste ne s’exécute pour un utilisateur non connecté
}

// 
// Gestion de la déconnexion
//

// Si l'utilisateur clique sur un lien de déconnexion (ex: ?action=deconnexion dans l’URL)
if(isset($_GET["action"]) && $_GET["action"] == "deconnexion") {
    session_destroy(); // Je supprime toutes les données de session (l'utilisateur est déconnecté)
    header("Location: index.html"); // Je le redirige vers la page d’accueil
    exit;
}

// 
// Récupération des infos de l'utilisateur connecté
// 

// Je prépare une requête SQL sécurisée pour récupérer les infos du user connecté à partir de son ID en session
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['iduser']]);

// Je stocke les infos récupérées dans une variable $user (prénom, email, etc.)
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Votre profil de collectionneur de cartes d'art">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&family=Inria+Serif:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap">
    <link rel="stylesheet" href="profil.css">
    <link rel="stylesheet" href="dark-mode.css" id="dark-mode-stylesheet">
    <style>
       
        body, body * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
    </style>
</head>
<body>
    <section class="top-page">
        <header class="header">
            <a href="index.html"><img src="image/Logo 2.png" alt="Logo ArtCard"></a>
            <nav class="nav">
                <a href="macollection.php"><li>Ma Collection</li></a>
                <a href="connexion.html"><li class="billeterie">Déconnexion</li></a>
            </nav>
            <button id="dark-mode-toggle" class="theme-toggle">
                <span class="theme-toggle-sun">☀️</span>
                <span class="theme-toggle-moon">🌙</span>
            </button>
        </header>
    </section>

    <div class="profile-container">
        <div class="profile-header">
            <div class="avatar-section">
                <div class="avatar-container">
                    <img src="image/vg.jpg" alt="Avatar" class="avatar" id="userAvatar">
                    <button class="edit-avatar-btn">✏️</button>
                </div>
                <h1 id="username">Jean_Dupont</h1>
                <p class="member-since">Membre depuis: <span id="joinDate">15 mars 2023</span></p>
            </div>
            
            <div class="stats-section">
                <div class="stat-card">
                    <div class="stat-value" id="totalCards">0</div>
                    <div class="stat-label">Cartes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="uniqueCards">0</div>
                    <div class="stat-label">Œuvres uniques</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="completionRate">0%</div>
                    <div class="stat-label">Complétion</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value" id="userRank">#1,245</div>
                    <div class="stat-label">Classement</div>
                </div>
            </div>
        </div>

        <div class="api-integration">
            <h2>Dernières œuvres ajoutées</h2>
            <div class="oeuvre-filters">
                <select id="oeuvreFilter">
                    <option value="all">Toutes les œuvres</option>
                    <option value="painting">Peintures</option>
                    <option value="sculpture">Sculptures</option>
                </select>
                
            </div>
            <div class="oeuvres-grid" id="apioeuvresGrid">
           
            </div>
            <div class="api-status" id="apiStatus"></div>
        </div>
        <div class="profile-content">
            <div class="profile-section">
                <h2>Mes Informations</h2>
                <div class="info-grid">
                    <div class="info-item">
                        <label>Nom d'utilisateur : </label>
                        <span id="displayUsername">Jean_Dupont</span>
                        <button class="edit-btn">Modifier</button>
                    
                    </div>
                    <div class="info-item">
                        <label>Email : </label>
                            <span id="displayEmail">jean.dupont@example.com</span>
                            <button class="edit-btn">Modifier</button>
                    </div>
                    <div class="info-item">
                        <label>Mot de passe : </label>
                      
                            <span>••••••••</span>
                            <button class="edit-btn">Modifier</button>
                        
                    </div>
                    <div class="info-item">
                        <label>Préférences : </label>
                 
                            <span>Art moderne, Impressionnisme</span>
                            <button class="edit-btn">Modifier</button>
                       
                    </div>
                </div>
            </div>

            <div class="profile-section">
                <h2>Mes Récompenses</h2>
                        <p>    Vos récompenses s'afficheront ici  </p>
                    </div>
                </div>
            </div>

    
        <div class="profile-section">
    <h2>Ma Collection</h2>
    <div class="collection-content"> 
        <div class="collection-grid">
            <?php
            $stmt = $pdo->query("SELECT * FROM artwork");
            while ($artwork = $stmt->fetch()) {
                echo '<div class="artwork-card">';
                echo '<h3>' . htmlspecialchars($artwork['title']) . '</h3>';
                echo '<p class="artist-name">' . htmlspecialchars($artwork['artist']) . '</p>';
                echo '<p class="creation-date">' . htmlspecialchars($artwork['creation_date']) . '</p>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>



    <div class="footer">
        <a href="#"><p>Mentions légales</p></a>
        <a href="#"><p>Conditions d'utilisation</p></a>
        <a href="#"><p>Contact</p></a>
    </div>

    <script src="profil.js"></script>
    <script src="dark-mode.js" defer></script>
</body>
</html>