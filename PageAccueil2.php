<?php

session_start();

// Vérifie si l'utilisateur est déjà connecté, sinon redirige vers la page d'inscription
if (!isset($_SESSION['user'])) {
    header("Location: PageInscription.php");
    exit;
}

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
            <ul class="menu">
                <li><a href="PageAccueil.php">Accueil</a></li>
                <li><a href="PageAccueil2.php">Rechercher</a></li>
                <li><a href="PageInscription.php">Se connecter</a></li>
                <li><a href="PageProfil.php">Profil</a></li>
            </ul>
        </header>
        <div class="Page-Accueil2-text">
            <h2>Nos Fondateurs</h2>
            <div class="fondateurs">
                <div class="Cadre">
                    <img src="images/Antoine.jpeg" alt="Marmelat Antoine">
                    <p>Marmelat Antoine</p>
                </div>
                <div class="Cadre">
                    <img src="images/Lina.JPEG" alt="Pereira-Alaoui Lina">
                    <p>Pereira-Alaoui Lina</p>
                </div>
                <div class="Cadre">
                    <img src="images/Dimittry.jpg" alt="Choudhury Dimittri">
                    <p>Choudhury Dimittri</p>
                </div>
            </div>
            <p class="Page-Accueil-text">"Tout a commencé par une discussion animée autour d’un popcorn renversé :</br>
                — « Et si on pouvait VRAIMENT visiter Poudlard ? »</br>
                — « Ou partir sur Tatooine sans finir grillé façon brochette ? »</br>
                — « Et si on créait Movietrip ? »</br>
                
                C’est ainsi que nos trois fondateurs, animés par une passion démesurée pour le cinéma (et une légère obsession pour les cartes d’embarquement), ont décidé de transformer les rêves en voyages.
                
                Aujourd’hui, grâce à eux, vous pouvez marcher sur les traces de vos héros, explorer des mondes iconiques et, soyons honnêtes… prendre des photos épiques pour rendre jaloux vos amis.
                
                Alors, prêts à embarquer ? Movietrip vous attend, baguette (ou sabre laser) en main !"</p>
        
        <div class="recherche">
            <input type="search" placeholder="Destinations (Films)">
            <button>Rechercher</button>
        </div>

        <h2 class="Titre">NOS S&Eacute;JOURS TENDANCES</h2>
    
        <div class="ListePhotos">
                <div class="gallerie-img">
                    <a href="Destinations/Poudlard.html">
                        <img src="images/Poudlard-scaled.jpg" alt="Chateau Harry Potter" width="200">
                        <div class="Lieux"><p>Poudlard</p></div>
                    </a>
                </div>
            
                <div class="gallerie-img">
                    <img src="images/Seigneur des anneaux.jpeg" alt="Chateau des elfes" width="200">
                    <div class="Lieux"><p>Fondcombe</p></div>
                </div>
            
        
                <div class="gallerie-img">
                    <img src="images/Tatooine.jpg" alt="Planète Star Wars" width="200">
                    <div class="Lieux"><p>Tatooine</p></div>
                </div>
            
            
            <div class="gallerie-img">
                <img src="images/1186175.png" alt="Jurrasic Park" width="200">
                <div class="Lieux"><p>Isla Nublar</p></div>
            </div>
            
          
            <div class="gallerie-img">
                <img src="images/Avatar.jpg" alt="Avatar paysage" width="200">
                <div class="Lieux"><p>Pandora</p></div>
            </div>
           
            
            <div class="gallerie-img">
                <img src="images/GOT.webp" alt="Avatar paysage" width="200">
                <div class="Lieux"><p>Westeros</p></div>
            </div>
        </div>
    
        <a class="Page-Accueil-button" href="PageFiltres.php">cliquer ici pour plus de choix</a>
    </div>
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