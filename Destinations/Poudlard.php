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
                <a href="#poudlard" class="Page-Accueil-button">JOUR 1-4 : Poudlard</a>
                <a href="#chemin-traverse" class="Page-Accueil-button">JOUR 5-7 : Pré-au-lard</a>
            </div>

            <div id="poudlard" class="section">
                <h2>JOUR 1-4 : Poudlard</h2>
                 
                <p class="description">Participez à des activités magiques,
                    visitez le célèbre château de Poudlard ainsi que la maison de Hagrid et
                    assistez à des cours</p>
                    
                

                <div class="options-group">
                <label for="hebergement">Hébergement:</label>
                <select id="hebergement" name="hebergement">
                    <option value="serpentard">Chambre Serpentard</option>
                    <option value="griffondor">Chambre Griffondor</option>
                    <option value="serdaigle">Chambre Serdaigle</option>
                    <option value="poufsoufle">Chambre Poufsoufle</option>
                </select>
                </div>

                <div class="options-group">
                    <label for="activites-toggle" class="activites-label">Activités: 
                        <span class="arrow">&#9660;</span>  
                    </label>
                    <input type="checkbox" id="activites-toggle" class="activites-toggle">
                    
                    <div class="activites-options">
                        <div>
                            <input type="checkbox" id="sorts" name="activites" value="sorts">
                            <label for="sorts">Cours de sortilèges</label>
                            
                            <select id="nb_personnes" name="nb_personnes">
                                <option value="1">1 personne</option>
                                <option value="2">2 personnes</option>
                                <option value="3">3 personnes</option>
                                <option value="4">4 personnes</option>
                            </select>
                            
                        </div>
                        <div>
                            <input type="checkbox" id="quidditch" name="activites" value="quidditch">
                            <label for="quidditch">Match de quidditch</label>
                            
                                
                                <select id="nb_personnes" name="nb_personnes">
                                    <option value="1">1 personne</option>
                                    <option value="2">2 personnes</option>
                                    <option value="3">3 personnes</option>
                                    <option value="4">4 personnes</option>
                                </select>
                            
                        </div>
                        <div>
                            <input type="checkbox" id="hyppogriffe" name="activites" value="hyppogriffe">
                            <label for="hyppogriffe">Dressage d'hyppogriffe</label>
                                <select id="nb_personnes" name="nb_personnes" >
                                    <option value="1">1 personne</option>
                                    <option value="2">2 personnes</option>
                                    <option value="3">3 personnes</option>
                                    <option value="4">4 personnes</option>
                                </select>
                        </div>
                        
                    </div>
                </div>
                <div class="options-group">
                     <label for="transport">Transport pour la prochaine étape:</label>
                    <select id="transport" name="transport">
                        <option value="balais">Balais</option>
                        <option value="poudre">Poudre de cheminette</option>
                        <option value="portoloin">Portoloin</option>
                        <option value="sombral">Sombral</option>
                    </select>
                </div>

            </div>
        
            <div id="chemin-traverse" class="section">
                <h2>JOUR 5-7 : Pré-au-lard</h2>
                <p class="description">Plongez dans l'univers magique de Pré-au-Lard, un village charmant et plein de surprises !
                Au programme :<br>
                Visitez la célèbre boutique de Zonko et laissez-vous emporter par l’univers des farces magiques.
                Détendez-vous au Troisième Chaudron avec une bièraubeurre bien méritée ou profitez d'une douceur sucrée chez Honeydukes.</p>

                <div class="options-group">
                <label for="hebergement-préaulard">Hébergement:</label>
                <select id="hebergement-préaulard" name="hebergement-préaulard">
                    <option value="sanglier">La Tête de Sanglier</option>
                    <option value="cottage-sorcier">Le Cottage des Sorciers</option>
                    <option value="cabane-hurlante">La cabane hurlante</option>
                    <option value="pudifoot">Les Chambres de Madame Puddifoot</option>
                </select>
                </div>

                <div class="options-group">
                    <label for="activites-toggle-préaulard" class="activites-label">Activités: 
                        <span class="arrow">&#9660;</span>  
                    </label>
                    <input type="checkbox" id="activites-toggle-préaulard" class="activites-toggle">
                    
                    <div class="activites-options">
                        <div>
                            <input type="checkbox" id="zonko" name="activites" value="zonko">
                            <label for="zonko">Visite de la boutique de Zonko</label>
                            
                            <select id="nb_personnes_visite" name="nb_personnesvisite">
                                <option value="1">1 personne</option>
                                <option value="2">2 personnes</option>
                                <option value="3">3 personnes</option>
                                <option value="4">4 personnes</option>
                            </select>
                            
                        </div>
                        <div>
                            <input type="checkbox" id="dégustation" name="activites" value="dégustation">
                            <label for="dégustation">Dégustation de Bièraubeurre</label>
                            
                                
                                <select id="nb_personnes_dégustation" name="nb_personnes_dégustation">
                                    <option value="1">1 personne</option>
                                    <option value="2">2 personnes</option>
                                    <option value="3">3 personnes</option>
                                    <option value="4">4 personnes</option>
                                </select>
                            
                        </div>
                        <div>
                            <input type="checkbox" id="honeydukes" name="activites" value="honeydukes">
                            <label for="honeydukes">Boutique de Glacés de Honeydukes</label>
                                <select id="nb_personnes_honeydukes" name="nb_personnes_honeydukes" >
                                    <option value="1">1 personne</option>
                                    <option value="2">2 personnes</option>
                                    <option value="3">3 personnes</option>
                                    <option value="4">4 personnes</option>
                                </select>
                        </div>
                        
                    </div>
                </div>
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