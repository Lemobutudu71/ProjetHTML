<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieTrip</title>
    <link rel="stylesheet" href="CSS.css">
</head>
<body>
    <section class="Page-Accueil">
        <video autoplay loop muted id="bg-video">
            <source src="images/Vidéo5.mp4" type="video/mp4">
        </video> 
        <header>
            <div class="ProfilPicture">
                <img src="images/LOGO.jpg" alt="logo" width="200" class="logo">
            </div>
            <nav>
                <ul class="menu">
                    <li><a href="PageAccueil.php">Accueil</a></li>
                    <li><a href="PageAccueil2.php">Rechercher</a></li>
                    <li><a href="PagePanier.php">Mon panier</a></li>
                    <li><a href="PageProfil.php">Profil</a></li>
                </ul>
            </nav>
        </header>
    
        
        <footer>
            <ul class="bas-de-page">
                <li><a href="#">Mentions légales</a></li>
                <li><a href="#">Politique de confidentialité</a></li>
                <li><a href="#">&Agrave; propos</a></li>
                <li><a href="pageAdministrateur.php">Administrateur</a></li>

            </ul>
        </footer> 
    </section>
</body>
</html>