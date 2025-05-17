<?php
require_once('../session.php');

$user_id = $_SESSION['user']['id'];

$file_path = '../json/options.json';

$activite_prix = [
    
    'jedi' => 45, 
    'speeder' => 55, 
    'palais_jabba' => 20, 

    'tie_fighter' => 70, 
    'tir' => 20,
    'sith' => 18  
];

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $hebergement_tatooine = isset($_POST['hebergement_tatooine']) ? $_POST['hebergement_tatooine'] : null;
    $activites_tatooine = isset($_POST['activites_tatooine']) ? $_POST['activites_tatooine'] : []; 
    $activites_etoile = isset($_POST['activites_etoile']) ? $_POST['activites_etoile'] : []; 
    $hebergement_etoile = isset($_POST['hebergement_etoile']) ? $_POST['hebergement_etoile'] : []; 
    $transport_tatooine = isset($_POST['transport_tatooine']) ? $_POST['transport_tatooine'] : null; 
    $departure_date = isset($_POST['departure_date']) ? $_POST['departure_date'] : null; 


    $nb_personnes_jedi = isset($_POST['nb_personnes_jedi']) ? $_POST['nb_personnes_jedi'] : null;
    $nb_personnes_speeder = isset($_POST['nb_personnes_speeder']) ? $_POST['nb_personnes_speeder'] : null;
    $nb_personnes_palaisjabba = isset($_POST['nb_personnes_palaisjabba']) ? $_POST['nb_personnes_palaisjabba'] : null;
    $nb_personnes_tie = isset($_POST['nb_personnes_tie']) ? $_POST['nb_personnes_tie'] : null;
    $nb_personnes_tir = isset($_POST['nb_personnes_tir']) ? $_POST['nb_personnes_tir'] : null;
    $nb_personnes_sith = isset($_POST['nb_personnes_sith']) ? $_POST['nb_personnes_sith'] : null;
    $nb_personnes_voyage = isset($_POST['nb_personnes_voyage']) ? $_POST['nb_personnes_voyage'] : null;
    $nb_personnes = [];

    $activite_total_prix = 0;

    // Vérifiez chaque activité et ajoutez le nombre de personnes uniquement si l'activité est sélectionnée
    if (in_array('jedi', $activites_tatooine)) {
        $nb_personnes['jedi'] = $nb_personnes_jedi;
        $personnes = $nb_personnes['jedi'];
        $activite_total_prix += $personnes * $activite_prix['jedi'];
    }
    if (in_array('speeder', $activites_tatooine)) {
        $nb_personnes['speeder'] =  $nb_personnes_speeder;
        $personnes = $nb_personnes['speeder'];
        $activite_total_prix += $personnes * $activite_prix['speeder'];
    }
    if (in_array('palais_jabba', $activites_tatooine)) {
        $nb_personnes['palais_jabba'] = $nb_personnes_palaisjabba;
        $personnes = $nb_personnes['palais_jabba'];
        $activite_total_prix += $personnes * $activite_prix['palais_jabba'];
    }
    if (in_array('tie_fighter', $activites_etoile)) {
        $nb_personnes['tie_fighter'] = $nb_personnes_tie;
        $personnes = $nb_personnes['tie_fighter'];
        $activite_total_prix += $personnes * $activite_prix['tie_fighter'];
    }
    if (in_array('tir', $activites_etoile)) {
        $nb_personnes['tir'] =  $nb_personnes_tir;
        $personnes = $nb_personnes['tir'];
        $activite_total_prix += $personnes * $activite_prix['tir'];
    }
    if (in_array('sith', $activites_etoile)) {
        $nb_personnes['sith'] = $nb_personnes_sith;
        $personnes = $nb_personnes['sith'];
        $activite_total_prix += $personnes * $activite_prix['sith'];
    }

$prix =3578;
$prix_total = $prix * $nb_personnes_voyage + $activite_total_prix;

// Vérifier si l'utilisateur est VIP et appliquer la réduction
if (isset($_SESSION['user']['Vip']) && $_SESSION['user']['Vip'] === "Oui") {
    $prix_total = $prix_total * 0.9; // Réduction de 10%
}

$etapes = ['Tatooine', 'Etoile'];
$return_date = null;
    if ($departure_date) {
        $departure_timestamp = strtotime($departure_date);
        
        $return_timestamp = strtotime("+7 days", $departure_timestamp);
        
        $return_date = date("Y-m-d", $return_timestamp);
    }
    // Structure des données à enregistrer
    $user_choices = [
        'user_id' => $user_id,
        'hebergement_tatooine' => $hebergement_tatooine,
        'activites_tatooine' => $activites_tatooine,
        'hebergement_etoile' => $hebergement_etoile,
        'activites_etoile' => $activites_etoile,
        'transport_tatooine' => $transport_tatooine,
        'nb_personnes' => $nb_personnes,
        'prix_total' => $prix_total,
        'nb_personnes_voyage' => $nb_personnes_voyage,
        'departure_date' => $departure_date,
        'return_date' => $return_date,
        'destination' => 'Tatooine',
        "nb_etapes"=> 2,
        "etapes"=> $etapes,
        'activite_prix' => $activite_prix 
    ];

    $user_choices['transaction_id'] = uniqid();
     if (file_exists($file_path)) {
        
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
                <img src="../images/Tatooine.jpg" alt="Tatooine">
                <div class="Lieuxdestinations">Tatooine</div>
            </div>
                       
            <p class="description">Bienvenue dans une galaxie lointaine, très lointaine...
            Oubliez les petits voyages à la plage, car cette fois, vous allez voyager à travers des systèmes stellaires, rencontrer des créatures intergalactiques et, peut-être, croiser quelques Sith en chemin. Que vous soyez un Jedi en herbe, un fanatique des droids ou un simple passionné des sabres laser, cette aventure Star Wars va vous faire vivre des moments mémorables !</p>
            
            <div class="buttons-container">
                <a href="#tatooine" class="Page-Accueil-button">JOUR 1-4 : Tatooine</a>
                <a href="#etoile" class="Page-Accueil-button">JOUR 5-7 : L'étoile de la mort</a>
            </div>

            <form action="Tatooine.php" method="POST">
                <div class="options-group">
                    <label for="departure-date">Date de départ:</label>
                    <input type="date" id="departure-date" name="departure_date" required min="<?php echo date('Y-m-d'); ?>">
                    <label for="nb_voyage"><br>Nombre de personnes pour le voyage</label>
                    <input type="number" id="nb_personnes_voyage" name="nb_personnes_voyage" min="1" value="1">
                            
                </div>
                <div id="tatooine" class="section">
                    <h2>JOUR 1-4 : Tatooine</h2>
                    <p class="description">Bienvenue sur Tatooine, le désert intergalactique où tout peut arriver... et souvent, ça arrive !
                    Si vous pensiez que la plage était le seul endroit avec du sable, détrompez-vous ! Sur Tatooine, le sable est partout... et il cache bien des secrets. Entre les attaques de Tusken Raiders et les courses de podracers endiablées, il n'y a jamais de moment de répit sous les deux soleils brûlants de la planète.</p>

                    <div class="options-group">
                        <label for="hebergement_tatooine">Hébergement:</label>
                        <select id="hebergement_tatooine" name="hebergement_tatooine">
                            <option value="palais_jabba">Palais de Jabba</option>
                            <option value="cottage_sith">Cottage des Sith</option>
                            <option value="maison_luke">Maison de Luke Skywalker</option>
                        </select>
                    </div>

                    <div class="options-group">
                        <label for="activites-toggle" class="activites-label">Activités: 
                            <span class="arrow">&#9660;</span>  
                        </label>
                        <input type="checkbox" id="activites-toggle" class="activites-toggle">
                        
                        <div class="activites-options">
                            <div>
                                <input type="checkbox" id="jedi" name="activites_tatooine[]" value="jedi">
                                <label for="jedi">Entraînement Jedi (<?php echo $activite_prix['jedi']; ?>€/personne)</label>
                                <input type="number" id="nb_personnes_jedi" name="nb_personnes_jedi" min="1" value="1">

                            </div>
                            <div>
                                <input type="checkbox" id="speeder" name="activites_tatooine[]" value="speeder">
                                <label for="speeder">Balade en speeder (<?php echo $activite_prix['speeder']; ?>€/personne)</label>
                                <input type="number" id="nb_personnes_speeder" name="nb_personnes_speeder" min="1" value="1">
                               
                            </div>
                            <div>
                                <input type="checkbox" id="palais_jabba" name="activites_tatooine[]" value="palais_jabba">
                                <label for="palais_jabba">Visite du palais de Jabba (<?php echo $activite_prix['palais_jabba']; ?>€/personne)</label>
                                <input type="number" id="nb_personnes_palaisjabba" name="nb_personnes_palaisjabba" min="1" value="1">
                               
                            </div>
                        </div>
                    </div>

                    <div class="options-group">
                        <label for="transport_tatooine">Transport pour la prochaine étape:</label>
                        <select id="transport_tatooine" name="transport_tatooine">
                            <option value="faucon">Faucon Millenium</option>
                            <option value="xwing">X-Wing</option>
                            <option value="destroyer">Destroyer stellaire</option>
                        </select>
                    </div>

                </div>

                <div id="etoile" class="section">
                    <h2>JOUR 5-7 : L'Etoile de la mort</h2>
                    <p class="description">Bienvenue à bord de l'Étoile Noire, où la galaxie tout entière est à vos pieds !
                    Préparez-vous à vivre une aventure épique, loin de toute la tranquillité de Tatooine et de ses sables brûlants. Ici, c'est l'Empire qui commande, et pas de place pour les faibles !Alors, que la Force soit avec vous... ou du moins, que la gravité de l'Étoile Noire ne vous fasse pas perdre l'équilibre !</p>

                    <div class="options-group">
                        <label for="hebergement_etoile">Hébergement:</label>
                        <select id="hebergement_etoile" name="hebergement_etoile">
                            <option value="quartier_empereur">Qartier de l'Empereur</option>
                            <option value="chambre_officier">Chambre des Officiers Impériaux</option>
                            <option value="salle_commandement">Salle de Commandement</option>
                        </select>
                    </div>

                    <div class="options-group">
                        <label for="activites-toggle-etoile" class="activites-label">Activités: 
                            <span class="arrow">&#9660;</span>  
                        </label>
                        <input type="checkbox" id="activites-toggle-etoile" class="activites-toggle">
                        
                        <div class="activites-options">
                            <div>
                                <input type="checkbox" id="tie_fighter" name="activites_etoile[]" value="tie_fighter">
                                <label for="tie_fighter">Entraînement au pilotage de TIE Fighter (<?php echo $activite_prix['tie_fighter']; ?>€/personne)</label>
                                <input type="number" id="nb_personnes_tie" name="nb_personnes_tie" min="1" value="1">
                    
                            </div>
                            <div>
                                <input type="checkbox" id="tir" name="activites_etoile[]" value="tir">
                                <label for="tir">Tir de l'Etoile de la mort (<?php echo $activite_prix['tir']; ?>€/personne)</label>
                                <input type="number" id="nb_personnes_tir" name="nb_personnes_tir" min="1" value="1">
                               
                            </div>
                            <div>
                                <input type="checkbox" id="sith" name="activites_etoile[]" value="sith">
                                <label for="sith">Entrainement des Sith (<?php echo $activite_prix['sith']; ?>€/personne)</label>
                                <input type="number" id="nb_personnes_sith" name="nb_personnes_sith" min="1" value="1">
                                
                            </div>
                        </div>
                    </div>
                    <div id="prix-total-dynamique"></div>
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
   <script src="../Javascript/Tatooine.js"></script>
';
require_once('../footer.php'); 
?>
