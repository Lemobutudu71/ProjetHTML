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
    <script src="/test/Projet/Javascript/Theme.js" defer></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const savedTheme = getCookie("theme");
            if (savedTheme === "light") {
                applyTheme("light");
            } else {
                applyTheme("default");
            }
            const toggle = document.getElementById("theme-toggle");
            if (toggle) {
                toggle.checked = (savedTheme === "light");
            }
        });
    </script>
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
                        <label class="switch">
                        <input type="checkbox" id="theme-toggle">
                        <span class="slider"></span>
                        </label>
                        <i class="fas fa-sun"></i>
                        
                </div>
            </ul>
        </header>
        <div class="Page-Accueil-text">
            <h2 class="Titre">Fondcombe</h2>
            <p>
                Plongez dans l'univers féerique du Seigneur des Anneaux et visitez Fondcombe,
                 le havre de paix des Elfes dirigé par Elrond. 
                 Un lieu de nature et de sagesse où l'on ressent toute la puissance du monde fantastique de Tolkien.
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
