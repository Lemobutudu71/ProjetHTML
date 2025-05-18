<?php
require_once('../session.php');

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

 $prix =3666;
  if (isset($_SESSION['user']['Vip']) && $_SESSION['user']['Vip'] === "Oui") {
        $prix = $prix * 0.9;
         foreach ($activite_prix as $key => $price) {
        $activite_prix[$key] = $price * 0.9;
        }
    }


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

<?php require_once('../header.php'); ?>  
        
        <div class="Page-Accueil2-text" data-vip="<?php echo (isset($_SESSION['user']['Vip']) && $_SESSION['user']['Vip'] === "Oui") ? 'true' : 'false'; ?>">
    
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
                <input type="date" id="departure-date" name="departure_date" required min="<?php echo date('Y-m-d'); ?>">
                <label for="nb_voyage"><br>Nombre de personnes pour le voyage</label>
                <input type="number" id="nb_personnes_voyage" name="nb_personnes_voyage" min="1" value="1">
                        
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
                            <input type="number" id="nb_personnes_sort" name="nb_personnes_sort" min="1" value="1">
                            
                        </div>
                        <div>
                            <input type="checkbox" id="quidditch" name="activites_poudlard[]" value="quidditch">
                            <label for="quidditch">Match de quidditch (<?php echo $activite_prix['quidditch']; ?>€/personne)</label>
                            <input type="number" id="nb_personnes_quid" name="nb_personnes_quid" min="1" value="1">
                            
                        </div>
                        <div>
                            <input type="checkbox" id="hyppogriffe" name="activites_poudlard[]" value="hyppogriffe">
                            <label for="hyppogriffe">Dressage d'hyppogriffe (<?php echo $activite_prix['hyppogriffe']; ?>€/personne)</label>
                            <input type="number" id="nb_personnes_hy" name="nb_personnes_hy" min="1" value="1">
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
                Visitez la célèbre boutique de Zonko et laissez-vous emporter par l'univers des farces magiques.
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
                            <input type="number" id="nb_personnes_visite" name="nb_personnes_visite" min="1" value="1">
                         
                        </div>
                        <div>
                            <input type="checkbox" id="degustation" name="activites_preaulard[]" value="degustation">
                            <label for="degustation">Dégustation de Bièraubeurre (<?php echo $activite_prix['degustation']; ?>€/personne)</label>
                            <input type="number" id="nb_personnes_degustation" name="nb_personnes_degustation" min="1" value="1">
                                
                        </div>
                        <div>
                            <input type="checkbox" id="honeydukes" name="activites_preaulard[]" value="honeydukes">
                            <label for="honeydukes">Boutique de Glacés de Honeydukes (<?php echo $activite_prix['honeydukes']; ?>€/personne)</label>
                            <input type="number" id="nb_personnes_honeydukes" name="nb_personnes_honeydukes" min="1" value="1">
                                
                        </div>
                        
                    </div>
                </div>
            </div>
            <div id="prix-total-dynamique" ></div>
            <?php if (isset($_SESSION['user']['Vip']) && $_SESSION['user']['Vip'] === "Oui"): ?>
                <div class="vip-reduction">
                    <p>🎉 Réduction VIP de 10% appliquée !</p>
                </div>
            <?php endif; ?>
            <div class="recherche">
                <button type="submit">Ajouter au panier</button>   
            </div>
        </form>    
            

        </div>
       
<?php 
$scripts = '
   <script src="../Javascript/Poudlard.js"></script>
   
';
require_once('../footer.php'); 
?>