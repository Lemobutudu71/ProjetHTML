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
    <link id="theme" rel="stylesheet" href="../CSS.css">
    <link 
    rel="stylesheet" 
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    >
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
                <div class="toggle-container">                        
                        <i class="fas fa-moon"></i>
                        <!-- Interrupteur personnalisé -->
                        <label class="switch">
                        <input type="checkbox" id="theme-toggle">
                        <span class="slider"></span>
                        </label>
                        <i class="fas fa-sun"></i>
                        
                </div>
            </ul>
        </header>
      
        <div class="Page-Accueil-text">
            <h2 class="Titre">Isla Nublar</h2>
            <p>
                Bienvenue sur Jurassic Park, où les dinosaures reprennent vie !
                 Explorez cette île mystérieuse peuplée de créatures préhistoriques. 
                 Attention, un T-Rex pourrait rôder non loin de votre hôtel…
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
    <script src="../Javascript/Theme.js"></script>

    
</body>
</html>
