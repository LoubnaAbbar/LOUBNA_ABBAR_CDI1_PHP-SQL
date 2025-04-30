<?php

require_once("login.php");


if(!isset($_SESSION["iduser"])) {
    header("location:login.php");
}



if(isset($_GET["action"]) && $_GET["action"] == "deconnexion") {
    // je vide ma session
    unset($_SESSION["iduser"]);
    unset($_SESSION["email"]);
    header("location:connexion.php"); // redirection sans paramètre
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <?php

        echo "Vous êtes connecté avec l'adresse email " . $_SESSION["email"];
    
    ?>
    
    <a href="?action=deconnexion">Se déconnecter</a>



</body>
</html>