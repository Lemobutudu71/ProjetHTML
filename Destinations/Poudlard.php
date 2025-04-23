<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../PageInscription.php"); 
    exit();
}

$user_id = $_SESSION['user']['id'];

$file_path = '../json/options.json';

$activite_prix = [
    
    'sorts' => 25, 
    'quidditch' => 35, 
    'hyppogriffe' => 40, 

    'zonko' => 15, 
    'degustation' => 20,
    'honeydukes' => 18  
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure all form fields are captured
    $hebergement_poudlard = isset($_POST['hebergement_poudlard']) ? $_POST['hebergement_poudlard'] : null;
    $hebergement_preaulard = isset($_POST['hebergement_preaulard']) ? $_POST['hebergement_preaulard'] : null;
    $activites_poudlard = isset($_POST['activites_poudlard']) ? $_POST['activites_poudlard'] : []; 
    $activites_preaulard = isset($_POST['activites_preaulard']) ? $_POST['activites_preaulard'] : []; 
    $transport_poudlard = isset($_POST['transport_poudlard']) ? $_POST['transport_poudlard'] : null; 
    $departure_date = isset($_POST['departure_date']) ? $_POST['departure_date'] : null; 

    // Capture number of people for each activity
    $nb_personnes = [];
    $activite_total_prix = 0;

    // Populate number of people for Poudlard activities
    if (in_array('sorts', $activites_poudlard)) {
        $nb_personnes['sorts'] = isset($_POST['nb_personnes_sort']) ? $_POST['nb_personnes_sort'] : null;
        $personnes = $nb_personnes['sorts'];
        $activite_total_prix += $personnes * $activite_prix['sorts'];
    }
    if (in_array('quidditch', $activites_poudlard)) {
        $nb_personnes['quidditch'] = isset($_POST['nb_personnes_quid']) ? $_POST['nb_personnes_quid'] : null;
        $personnes = $nb_personnes['quidditch'];
        $activite_total_prix += $personnes * $activite_prix['quidditch'];
    }
    if (in_array('hyppogriffe', $activites_poudlard)) {
        $nb_personnes['hyppogriffe'] = isset($_POST['nb_personnes_hy']) ? $_POST['nb_personnes_hy'] : null;
        $personnes = $nb_personnes['hyppogriffe'];
        $activite_total_prix += $personnes * $activite_prix['hyppogriffe'];
    }

    // Populate number of people for Pré-au-Lard activities
    if (in_array('zonko', $activites_preaulard)) {
        $nb_personnes['zonko'] = isset($_POST['nb_personnes_visite']) ? $_POST['nb_personnes_visite'] : null;
        $personnes = $nb_personnes['zonko'];
        $activite_total_prix += $personnes * $activite_prix['zonko'];
    }
    if (in_array('degustation', $activites_preaulard)) {
        $nb_personnes['degustation'] = isset($_POST['nb_personnes_degustation']) ? $_POST['nb_personnes_degustation'] : null;
        $personnes = $nb_personnes['degustation'];
        $activite_total_prix += $personnes * $activite_prix['degustation'];
    }
    if (in_array('honeydukes', $activites_preaulard)) {
        $nb_personnes['honeydukes'] = isset($_POST['nb_personnes_honeydukes']) ? $_POST['nb_personnes_honeydukes'] : null;
        $personnes = $nb_personnes['honeydukes'];
        $activite_total_prix += $personnes * $activite_prix['honeydukes'];
    }

    $prix = 3666;
    $nb_personnes_voyage = isset($_POST['nb_personnes_voyage']) ? $_POST['nb_personnes_voyage'] : 1;
    $prix_total = $prix * $nb_personnes_voyage + $activite_total_prix; 
    $etapes = ['Poudlard', 'PreauLard'];
    $return_date = null;
    if ($departure_date) {
        $departure_timestamp = strtotime($departure_date);
        
        $return_timestamp = strtotime("+7 days", $departure_timestamp);
        
        $return_date = date("Y-m-d", $return_timestamp);
    }
    $user_choices = [
        'user_id' => $user_id,
        'hebergement_poudlard' => $hebergement_poudlard,
        'hebergement_preaulard' => $hebergement_preaulard,
        'activites_poudlard' => $activites_poudlard,
        'activites_preaulard' => $activites_preaulard,
        'transport_poudlard' => $transport_poudlard,
        'nb_personnes' => $nb_personnes,
        'nb_personnes_voyage' => $nb_personnes_voyage,
        'destination' => 'Poudlard',
        'return_date' => $return_date,
        'departure_date' => $departure_date,
        'prix_total' => $prix_total,
        'nb_etapes' => 2,
        'etapes' => $etapes,
        'activite_prix' => $activite_prix 
    ];
   
    $user_choices['transaction_id'] = uniqid();

    if (file_exists($file_path)) {
        // Lire les données existantes
        $existing_data = json_decode(file_get_contents($file_path), true);
        $existing_data[] = $user_choices;

    } 

    
    file_put_contents($file_path, json_encode($existing_data, JSON_PRETTY_PRINT));


    header('Location: ../PagePanier.php');
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
                <li><a href="../PagePanier.php">Mon panier</a></li>
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
        
        <div class="Page-Accueil2-text">
    
            <div class="gallerie-imgDestinations">
                
                <div class="image-overlay"></div>
                <img src="../images/Poudlard-scaled.jpg" alt="Poudlard" >
                <div class="Lieuxdestinations">Poudlard</div>
                
            </div>
                   
                      
           
            <p class="description">Voyagez dans le monde magique de Harry Potter, où vous pourrez explorer le célèbre château de Poudlard, apprendre des sorts, et assister à des cours de sorcellerie.
                Traversez le Chemin de Traverse, goûtez aux chocogrenouilles et vivez une aventure digne des plus grands sorciers !</p>
            
            <div class="buttons-container">
                <span><a href="#poudlard" class="Page-Accueil-button">JOUR 1-4 : Poudlard</a></span>
                <span><a href="#chemin-traverse" class="Page-Accueil-button">JOUR 5-7 : Pré-au-lard</a></span>
            </div>
        
        <form action="Poudlard.php" method="POST">

            <div class="options-group">
                <label for="departure-date">Date de départ:</label>
                <input type="date" id="departure-date" name="departure_date" required>
                <label for="nb_voyage"><br>Nombre de personnes pour le voyage</label>
                        <select id="nb_personnes_voyage" name="nb_personnes_voyage">
                            <option value="1">1 personne</option>
                            <option value="2">2 personnes</option>
                            <option value="3">3 personnes</option>
                            <option value="4">4 personnes</option>
                        </select>
            </div>
            <div id="poudlard" class="section">
                <h2>JOUR 1-4 : Poudlard</h2>
                 
                <p class="description">Participez à des activités magiques,
                    visitez le célèbre château de Poudlard ainsi que la maison de Hagrid et
                    assistez à des cours</p>
                    
                

                <div class="options-group">
                <label for="hebergement_poudlard">Hébergement:</label>
                <select id="hebergement_poudlard" name="hebergement_poudlard">
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
                            <label for="sorts">Cours de sortilèges (<?php echo $activite_prix['sorts']; ?>€/personne)</label>
                            
                            <select id="nb_personnes_sort" name="nb_personnes_sort">
                                <option value="1">1 personne</option>
                                <option value="2">2 personnes</option>
                                <option value="3">3 personnes</option>
                                <option value="4">4 personnes</option>
                            </select>
                            
                        </div>
                        <div>
                            <input type="checkbox" id="quidditch" name="activites_poudlard[]" value="quidditch">
                            <label for="quidditch">Match de quidditch (<?php echo $activite_prix['quidditch']; ?>€/personne)</label>
                            
                                
                                <select id="nb_personnes_quid" name="nb_personnes_quid">
                                    <option value="1">1 personne</option>
                                    <option value="2">2 personnes</option>
                                    <option value="3">3 personnes</option>
                                    <option value="4">4 personnes</option>
                                </select>
                            
                        </div>
                        <div>
                            <input type="checkbox" id="hyppogriffe" name="activites_poudlard[]" value="hyppogriffe">
                            <label for="hyppogriffe">Dressage d'hyppogriffe (<?php echo $activite_prix['hyppogriffe']; ?>€/personne)</label>
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
                     <label for="transport_poudlard">Transport pour la prochaine étape:</label>
                    <select id="transport_poudlard" name="transport_poudlard">
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
                <label for="hebergement_preaulard">Hébergement:</label>
                <select id="hebergement_preaulard" name="hebergement_preaulard">
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
                            <label for="zonko">Visite de la boutique de Zonko (<?php echo $activite_prix['zonko']; ?>€/personne)</label>
                            
                            <select id="nb_personnes_visite" name="nb_personnes_visite">
                                <option value="1">1 personne</option>
                                <option value="2">2 personnes</option>
                                <option value="3">3 personnes</option>
                                <option value="4">4 personnes</option>
                            </select>
                            
                        </div>
                        <div>
                            <input type="checkbox" id="degustation" name="activites_preaulard[]" value="degustation">
                            <label for="degustation">Dégustation de Bièraubeurre (<?php echo $activite_prix['degustation']; ?>€/personne)</label>
                            
                                
                                <select id="nb_personnes_degustation" name="nb_personnes_degustation">
                                    <option value="1">1 personne</option>
                                    <option value="2">2 personnes</option>
                                    <option value="3">3 personnes</option>
                                    <option value="4">4 personnes</option>
                                </select>
                            
                        </div>
                        <div>
                            <input type="checkbox" id="honeydukes" name="activites_preaulard[]" value="honeydukes">
                            <label for="honeydukes">Boutique de Glacés de Honeydukes (<?php echo $activite_prix['honeydukes']; ?>€/personne)</label>
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
            <div id="prix-total-dynamique" ></div>
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
    <script src="../Javascript/Poudlard.js"></script>
     
</body>
</html>