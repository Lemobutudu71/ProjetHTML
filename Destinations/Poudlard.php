<?php
session_start();
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
        
        <div class="Page-Accueil2-text">
    
            <div class="gallerie-imgDestinations">
                
                <div class="image-overlay"></div>
                <img src="../images/Poudlard-scaled.jpg" alt="Poudlard" >
                <div class="Lieuxdestinations">Poudlard</div>
                
            </div>
                   
                      
           
            <p class="description">Voyagez dans le monde magique de Harry Potter, où vous pourrez explorer le célèbre château de Poudlard, apprendre des sorts, et assister à des cours de sorcellerie.
                Traversez le Chemin de Traverse, goûtez aux chocogrenouilles et vivez une aventure digne des plus grands sorciers !</p>
            
            <div class="buttons-container">
                <a href="#poudlard" class="Page-Accueil-button">JOUR 1-3 : Poudlard</a>
                <a href="#chemin-traverse" class="Page-Accueil-button">JOUR 4-6 : Préaulard</a>
            </div>

            <div id="poudlard" class="section">
                <h2>JOUR 1-3 : Poudlard</h2>
                 <ul>
                    <li>Visitez le célèbre château de Poudlard ainsi que la maison de Hagrid</li>
                    <li>Assistez à des cours</li>
                    <li>Participer à un match de Quiditch</li>
                </ul>

                <div class="options-group">
                <label for="hebergement">Choisissez votre hébergement:</label>
                <select id="hebergement" name="hebergement">
                    <option value="serpentard">Chambre Serpentard</option>
                    <option value="griffondor">Chambre Griffondor</option>
                    <option value="serdaigle">Chambre Serdaigle</option>
                    <option value="poufsoufle">Chambre Poufsoufle</option>
                </select>
                </div>

                <div class="options-group">
                    <label for="activites-toggle" class="activites-label">Choisissez vos activités: 
                        <span class="arrow">&#9660;</span>  
                    </label>
                    <input type="checkbox" id="activites-toggle" class="activites-toggle">
                    
                    <div class="activites-options">
                        <div>
                            <input type="checkbox" id="sorts" name="activites" value="sorts">
                            <label for="sorts">Cours de sortilèges</label>
                        </div>
                        <div>
                            <input type="checkbox" id="chemin_traverse" name="activites" value="chemin_traverse">
                            <label for="chemin_traverse">Chemin de Traverse</label>
                        </div>
                        <div>
                            <input type="checkbox" id="repas_magiques" name="activites" value="repas_magiques">
                            <label for="repas_magiques">Repas Magiques</label>
                        </div>
                    </div>
                </div>

                <div class="options-group">
                    <label for="nb_personnes">Nombre de personnes:</label>
                    <select id="nb_personnes" name="nb_personnes">
                        <option value="1">1 personne</option>
                        <option value="2">2 personnes</option>
                        <option value="3">3 personnes</option>
                        <option value="4">4 personnes</option>
                    </select>
                </div>

            </div>
        
            <div id="chemin-traverse" class="section">
                <h2>Le Chemin de Traverse</h2>
                <p>Traversez le Chemin de Traverse et découvrez des merveilles magiques...</p>
            </div>

            <div class="options-group">
                <label for="hebergement">Choisissez votre hébergement:</label>
                <select id="hebergement" name="hebergement">
                    <option value="hotel">Hôtel</option>
                    <option value="auberge">Auberge</option>
                    <option value="residence">Résidence privée</option>
                </select>
            </div>

            <div class="options-group">
                <label for="activites">Choisissez vos activités:</label>
                <select id="activites" name="activites">
                    <option value="sorts">Cours de sortilèges</option>
                    <option value="chemin_traverse">Chemin de Traverse</option>
                    <option value="repas_magiques">Repas Magiques</option>
                </select>
            </div>

            <div class="options-group">
                <label for="nb_personnes">Nombre de personnes:</label>
                <select id="nb_personnes" name="nb_personnes">
                    <option value="1">1 personne</option>
                    <option value="2">2 personnes</option>
                    <option value="3">3 personnes</option>
                    <option value="4">4 personnes</option>
                </select>
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