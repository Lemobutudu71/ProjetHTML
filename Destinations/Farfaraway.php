<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../PageInscription.php");  
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieTrip</title>
    <link rel="stylesheet" href="../CSS.css">
</head>
<body>
    <section class="Page-Accueil">
        <video autoplay loop muted id="bg-video">
            <source src="../images/Vidéo5.mp4" type="video/mp4">
        </video> 
        <header>
            <div class="ProfilPicture">
                <img src="../images/LOGO.jpg" alt="logo" width="200" class="logo">
            </div>
            <ul class="menu">
                <li><a href="../PageAccueil.php">Accueil</a></li>
                <li><a href="../PageAccueil2.php">Rechercher</a></li>
                <li><a href="../PageInscription.php">Se connecter</a></li>
                <li><a href="../PageProfil.php">Profil</a></li>
            </ul>
        </header>
        
        <div class="Page-Accueil-text">
            <h2 class="Titre">Far far away</h2>
            <p>
                Entrez dans l’univers déjanté de Shrek et visitez Far Far Away,
                un royaume où les contes de fées ne se déroulent jamais comme prévu.
                Croiserez-vous l’Âne ou Fiona ?
            </p>
        
       
        </div>
        
    
       
    </div>
        <footer>
            <ul class="bas-de-page">
                <li><a href="#">Mentions légales</a></li>
                <li><a href="#">Politique de confidentialité</a></li>
                <li><a href="#">&Agrave; propos</a></li>
                <li><a href="../pageAdministrateur.php">Administrateur</a></li>
            </ul>
        </footer>
    </section>

    
</body>
</html>
