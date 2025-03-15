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
        <div class="Page-Accueil-text">
            <h1>Rechercher un voyage</h1>
            <div class="date-container">
                <div class="date-input">
                    <label for="date-depart">Date de départ :</label>
                    <input  type="date" id="date-depart" name="date-depart" required>
                </div>

                <div class="date-input">
                    <label for="date-retour">Date de retour :</label>
                    <input type="date" id="date-retour" name="date-retour" required>
                </div>
            </div>
           
            
            <div class="filters-container">
                <div class="filter-input">
                    <label for="transport">Moyen d'accès:</label>
                    <select id="transport" name="transport" >
                        <option value="Vaisseau">Vaisseau spatial</option>
                        <option value="Bateau">Bateau</option>
                        <option value="Poudre-cheminette">Poudre de cheminette</option>
                        <option value="Cheval">Cheval</option>
                        <option value="Avion">Avion</option>
                        <option value="Voiture">Voiture</option>
                    </select>
                </div>
            
                <div class="filter-input">
                    <label for="Logement">Logement:</label>
                    <select id="Logement" name="Logement">
                        <option value="chateau">Château</option>
                        <option value="Chez-habitant">Chez l'habitant</option>
                        <option value="camping">Camping</option>
                        <option value="Maison">Maison</option>
                        <option value="Hotel">Hôtel 5 étoiles</option>
                        
                    </select>
                </div>
            
                <div class="filter-input">
                    <label for="Logement">Monde:</label>
                    <select id="Logement" name="Logement">
                        <option value="medieval">Médiéval</option>
                        <option value="magique">Magique</option>
                        <option value="prehistorique">Préhistorique</option>
                        <option value="Futuriste">Futuriste</option>
                        <option value="exotique">&Eacute;xotique</option>
                        <option value="Surnaturel">Surnaturel</option>
                    </select>
                </div>
            </div>


            <div class="ListePhotos">
            
                <div class="gallerie-img">
                    <a href="Destinations/Poudlard.html">
                        <img src="images/Poudlard-scaled.jpg" alt="Chateau Harry Potter" width="200">
                        <div class="Lieux"><p>Poudlard</p></div>
                        <div class="Prix"><p>&Agrave; partir de 3666€</p></div>
                    </a>
                </div>
            
                <div class="gallerie-img">
                    <a href="Destinations/Fondcombe.html">
                        <img src="images/Seigneur des anneaux.jpeg" alt="Chateau des elfes Seigneur des anneaux" width="200">
                        <div class="Lieux"><p>Fondcombe</p></div>
                        <div class="Prix"><p>&Agrave; partir de 2456 €</p></div>
                    </a>    
                </div>
                <div class="gallerie-img">
                    <a href="Destinations/Tatooine.html">
                        <img src="images/Tatooine.jpg" alt="Planète Star Wars" width="200">
                        <div class="Lieux"><p>Tatooine</p></div>
                        <div class="Prix"><p>&Agrave; partir de 3578 €</p></div>
                    </a>    
                </div>
                <div class="gallerie-img">
                    <a href="Destinations/Isla Nublar.html">
                       <img src="images/1186175.png" alt="Jurrasic Park" width="200">
                       <div class="Lieux"><p>Isla Nublar</p></div>
                       <div class="Prix"><p>&Agrave; partir de 1498 €</p></div>
                    </a>
                </div>
                <div class="gallerie-img">
                    <a href="Destinations/Pandora.html">
                        <img src="images/Avatar.jpg" alt="Avatar paysage" width="200">
                        <div class="Lieux"><p>Pandora</p></div>
                        <div class="Prix"><p>&Agrave; partir de 2018 €</p></div>
                    </a>    
                </div>
                <div class="gallerie-img">
                    <a href="Destinations/Westeros.html">
                        <img src="images/GOT.webp" alt="Game of Thrones" width="200">
                        <div class="Lieux"><p>Westeros</p></div>
                        <div class="Prix"><p>&Agrave; partir de 5300 €</p></div>
                    </a>    
                </div>
                <div class="gallerie-img">
                    <a href="Destinations/LosAngeles.html">
                        <img src="images/Blade runner.jpg" alt="Blade Runner" width="200">
                        <div class="Lieux"><p>Los Angeles (2049)</p></div>
                        <div class="Prix"><p>&Agrave; partir de 3141,592653 €</p></div>
                    </a>    
                </div>
                <div class="gallerie-img">
                    <a href="Destinations/Radiatorsprings.html">
                        <img src="images/radiator_springs.jpg" alt="Flash McQueen" width="200">
                        <div class="Lieux"><p>Radiator springs</p></div>
                        <div class="Prix"><p>&Agrave; partir de 2005 €</p></div>
                    </a>    
                </div>
                <div class="gallerie-img">
                    <a href="Destinations/Hawkins.html">
                        <img src="images/Hawkins.jpg" alt="Stranger Things" width="200">
                        <div class="Lieux"><p>Hawkins</p></div>
                        <div class="Prix"><p>&Agrave; partir de 1998€</p></div>
                    </a>
                </div>
                <div class="gallerie-img">
                    <a href="Destinations/Cybertron.html">
                        <img src="images/Optimus.jpg" alt="Transformers" width="200">
                        <div class="Lieux"><p>Cybertron</p></div>
                        <div class="Prix"><p>&Agrave; partir de 4444€</p></div>
                    </a>    
                </div>
                <div class="gallerie-img">
                    <a href="Destinations/OceanAtlantiquenord.html">
                        <img src="images/titanic.jpg" alt="bateau RMS Titanic" width="200">
                        <div class="Lieux"><p>Océan Atlantique Nord</p></div>
                        <div class="Prix"><p>&Agrave; partir de 6450€</p></div>
                    </a>    
                </div>
                <div class="gallerie-img">
                    <a href="Destinations/CairParavel.html">
                        <img src="images/CairParavel.jpg" alt="Chateau des rois et reines de Narnia" width="200">
                        <div class="Lieux"><p>Cair Paravel</p></div>
                        <div class="Prix"><p>&Agrave; partir de 3709€</p></div>
                    </a>    
                </div>
                <div class="gallerie-img">
                    <a href="Destinations/Jamaïque.html">
                        <img src="images/Portroyal.jpg" alt="Port Royal" width="200">
                        <div class="Lieux"><p>Jamaïque</p></div>
                        <div class="Prix"><p>&Agrave; partir de 4540€</p></div>
                    </a>    
                </div>
                <div class="gallerie-img">
                    <a href="Destinations/Farfaraway.html">
                        <img src="images/Shrek-far-far-away.jpg" alt="Royaume dans Shrek" width="200">
                        <div class="Lieux"><p>Far far away</p></div>
                        <div class="Prix"><p>&Agrave; partir de 4784€</p></div>
                    </a>
                </div>
                <div class="gallerie-img">
                    <a href="Destinations/Arrakis.html">
                        <img src="images/Arrakis.jpg" alt="Désert dans Dune" width="200">
                        <div class="Lieux"><p>Arrakis</p></div>
                        <div class="Prix"><p>&Agrave; partir de 3102€</p></div>
                    </a>    
                </div>
                

            </div>
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
