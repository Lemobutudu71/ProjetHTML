<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: PageSeconnecter.php"); // Rediriger si l'utilisateur n'est pas connecté
    exit();
}

// Récupérer l'ID de l'utilisateur connecté
$user_id = $_SESSION['user']['id'];

// Fichier JSON pour stocker les options
$file_path = '../options.json';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire avec isset() pour éviter les erreurs
    $hebergement_poudlard = isset($_POST['hebergement-poudlard']) ? $_POST['hebergement-poudlard'] : null;
    $hebergement_preau_lard = isset($_POST['hebergement-preaulard']) ? $_POST['hebergement-preaulard'] : null;
    $activites_poudlard = isset($_POST['activites_poudlard']) ? $_POST['activites_poudlard'] : []; // Activités Poudlard
    $activites_preau_lard = isset($_POST['activites_preaulard']) ? $_POST['activites_preaulard'] : []; // Activités Pré-au-Lard
    $transport_preaulard = isset($_POST['transport_preaulard']) ? $_POST['transport_preaulard'] : null; // Transport



// Récupérer les valeurs des nombres de personnes pour chaque activité
$nb_personnes_sort = isset($_POST['nb_personnes_sort']) ? $_POST['nb_personnes_sort'] : null;
$nb_personnes_quid = isset($_POST['nb_personnes_quid']) ? $_POST['nb_personnes_quid'] : null;
$nb_personnes_hy = isset($_POST['nb_personnes_hy']) ? $_POST['nb_personnes_hy'] : null;
$nb_personnes_visite = isset($_POST['nb_personnes_visite']) ? $_POST['nb_personnes_visite'] : null;
$nb_personnes_degustation = isset($_POST['nb_personnes_degustation']) ? $_POST['nb_personnes_degustation'] : null;
$nb_personnes_honeydukes = isset($_POST['nb_personnes_honeydukes']) ? $_POST['nb_personnes_honeydukes'] : null;

    $nb_personnes = [];

// Puis vérifiez chaque activité et ajoutez le nombre de personnes uniquement si l'activité est sélectionnée
if (in_array('sorts', $activites_poudlard)) {
    $nb_personnes['sorts'] = $nb_personnes_sort;
}
if (in_array('quidditch', $activites_poudlard)) {
    $nb_personnes['quidditch'] =  $nb_personnes_quid;
}
if (in_array('hyppogriffe', $activites_poudlard)) {
    $nb_personnes['hyppogriffe'] = $nb_personnes_hy;
}
if (in_array('zonko', $activites_preau_lard)) {
    $nb_personnes['zonko'] = $nb_personnes_visite;
}
if (in_array('degustation', $activites_preau_lard)) {
    $nb_personnes['degustation'] = $nb_personnes_degustation;
}
if (in_array('honeydukes', $activites_preau_lard)) {
    $nb_personnes['honeydukes'] = $nb_personnes_honeydukes;
}

    // Structure des données à enregistrer
    $user_choices = [
        'user_id' => $user_id,
        'hebergement_poudlard' => $hebergement_poudlard,
        'hebergement_preau_lard' => $hebergement_preau_lard,
        'activites_poudlard' => $activites_poudlard,
        'activites_preau_lard' => $activites_preau_lard,
        'transport_preaulard' => $transport_preaulard,
        'nb_personnes' => $nb_personnes,
    ];

    // Vérifier si le fichier existe et si des données existent déjà
    if (file_exists($file_path)) {
        // Lire les données existantes
        $existing_data = json_decode(file_get_contents($file_path), true);

        // Rechercher l'utilisateur avec l'ID et mettre à jour ses données
        foreach ($existing_data as &$user_data) {
            if ($user_data['user_id'] == $user_id) {
                // Remplacer les anciennes données avec les nouvelles données
                $user_data = $user_choices;
                break;
            }
        }

        // Si l'utilisateur n'existe pas, ajouter un nouveau
        if (!isset($user_data)) {
            $existing_data[] = $user_choices;
        }

    } else {
        // Créer un tableau vide si le fichier n'existe pas encore
        $existing_data = [$user_choices];
    }

    // Enregistrer les données mises à jour dans le fichier JSON
    file_put_contents($file_path, json_encode($existing_data, JSON_PRETTY_PRINT));

    // Rediriger vers la page du panier
    header('Location: ../PagePanier.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en"> <!---->
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
                <li><a href="../PagePanier.php"></a></li>
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
        
        <form action="Poudlard.php" method="POST">
            <div id="poudlard" class="section">
                <h2>JOUR 1-4 : Poudlard</h2>
                 
                <p class="description">Participez à des activités magiques,
                    visitez le célèbre château de Poudlard ainsi que la maison de Hagrid et
                    assistez à des cours</p>
                    
                

                <div class="options-group">
                <label for="hebergement-poudlard">Hébergement:</label>
                <select id="hebergement-poudlard" name="hebergement-poudlard">
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
                            <input type="checkbox" id="sorts" name="activites_poudlard[]" value="sorts">
                            <label for="sorts">Cours de sortilèges</label>
                            
                            <select id="nb_personnes_sort" name="nb_personnes_sort">
                                <option value="1">1 personne</option>
                                <option value="2">2 personnes</option>
                                <option value="3">3 personnes</option>
                                <option value="4">4 personnes</option>
                            </select>
                            
                        </div>
                        <div>
                            <input type="checkbox" id="quidditch" name="activites_poudlard[]" value="quidditch">
                            <label for="quidditch">Match de quidditch</label>
                            
                                
                                <select id="nb_personnes_quid" name="nb_personnes_quid">
                                    <option value="1">1 personne</option>
                                    <option value="2">2 personnes</option>
                                    <option value="3">3 personnes</option>
                                    <option value="4">4 personnes</option>
                                </select>
                            
                        </div>
                        <div>
                            <input type="checkbox" id="hyppogriffe" name="activites_poudlard[]" value="hyppogriffe">
                            <label for="hyppogriffe">Dressage d'hyppogriffe</label>
                                <select id="nb_personnes_hy" name="nb_personnes_hy" >
                                    <option value="1">1 personne</option>
                                    <option value="2">2 personnes</option>
                                    <option value="3">3 personnes</option>
                                    <option value="4">4 personnes</option>
                                </select>
                        </div>
                        
                    </div>
                </div>
                <div class="options-group">
                     <label for="transport_preaulard">Transport pour la prochaine étape:</label>
                    <select id="transport_preaulard" name="transport_preaulard">
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
                <label for="hebergement-preaulard">Hébergement:</label>
                <select id="hebergement-preaulard" name="hebergement-preaulard">
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
                            <input type="checkbox" id="zonko" name="activites_preaulard[]" value="zonko">
                            <label for="zonko">Visite de la boutique de Zonko</label>
                            
                            <select id="nb_personnes_visite" name="nb_personnes_visite">
                                <option value="1">1 personne</option>
                                <option value="2">2 personnes</option>
                                <option value="3">3 personnes</option>
                                <option value="4">4 personnes</option>
                            </select>
                            
                        </div>
                        <div>
                            <input type="checkbox" id="degustation" name="activites_preaulard[]" value="degustation">
                            <label for="degustation">Dégustation de Bièraubeurre</label>
                            
                                
                                <select id="nb_personnes_degustation" name="nb_personnes_degustation">
                                    <option value="1">1 personne</option>
                                    <option value="2">2 personnes</option>
                                    <option value="3">3 personnes</option>
                                    <option value="4">4 personnes</option>
                                </select>
                            
                        </div>
                        <div>
                            <input type="checkbox" id="honeydukes" name="activites_preaulard[]" value="honeydukes">
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
            <div class="recherche">
                <button type="submit">Ajouter au panier</button>   
            </div>
        </form>    
            

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