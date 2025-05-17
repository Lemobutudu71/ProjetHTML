<?php

require_once('../session.php');

$user_id = $_SESSION['user']['id'];


$file_path = '../json/options.json';

$activite_prix = [
    
    'combat' => 20, 
    'chasse' => 35, 
    'mur' => 30, 
    'tournoi' => 12,
    'trone' => 30, 
    'fleuve' => 50,
    'dragons' => 100,
    'gladiateur' => 77,
    'marche' => 10  
];

 $prix =5300;
  if (isset($_SESSION['user']['Vip']) && $_SESSION['user']['Vip'] === "Oui") {
        $prix = $prix * 0.9; // Réduction de 10%
         foreach ($activite_prix as $key => $price) {
        $activite_prix[$key] = $price * 0.9;
        }
    }

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $hebergement_winterfell = isset($_POST['hebergement_winterfell']) ? $_POST['hebergement_winterfell'] : null;
    $hebergement_portreal = isset($_POST['hebergement_portreal']) ? $_POST['hebergement_portreal'] : null;
    $hebergement_meereen = isset($_POST['hebergement_meereen']) ? $_POST['hebergement_meereen'] : null;

    $activites_winterfell = isset($_POST['activites_winterfell']) ? $_POST['activites_winterfell'] : [];
    $activites_portreal = isset($_POST['activites_portreal']) ? $_POST['activites_portreal'] : [];
    $activites_meereen = isset($_POST['activites_meereen']) ? $_POST['activites_meereen'] : [];

    $transport_winterfell = isset($_POST['transport_winterfell']) ? $_POST['transport_winterfell'] : [];
    $transport_portreal = isset($_POST['transport_portreal']) ? $_POST['transport_portreal'] : [];
    $departure_date = isset($_POST['departure_date']) ? $_POST['departure_date'] : null; 

    $nb_personnes_voyage = isset($_POST['nb_personnes_voyage']) ? $_POST['nb_personnes_voyage'] : null;
    $nb_personnes = [];

    $activite_total_prix = 0;


    // Ajouter le nombre de personnes pour chaque activité
    if (in_array('combat', $activites_winterfell)) {
        $nb_personnes['combat'] = isset($_POST['nb_personnes_combat']) ? $_POST['nb_personnes_combat'] : null;
        $personnes = $nb_personnes['combat'];
        $activite_total_prix += $personnes * $activite_prix['combat'];
    }
    if (in_array('chasse', $activites_winterfell)) {
        $nb_personnes['chasse'] = isset($_POST['nb_personnes_chasse']) ? $_POST['nb_personnes_chasse'] : null;
        $personnes = $nb_personnes['chasse'];
        $activite_total_prix += $personnes * $activite_prix['chasse'];
    }
    if (in_array('mur', $activites_winterfell)) {
        $nb_personnes['mur'] = isset($_POST['nb_personnes_mur']) ? $_POST['nb_personnes_mur'] : null;
        $personnes = $nb_personnes['mur'];
        $activite_total_prix += $personnes * $activite_prix['mur'];
    }
    if (in_array('tournoi', $activites_portreal)) {
        $nb_personnes['tournoi'] = isset($_POST['nb_personnes_tournoi']) ? $_POST['nb_personnes_tournoi'] : null;
        $personnes = $nb_personnes['tournoi'];
        $activite_total_prix += $personnes * $activite_prix['tournoi'];
    }
    if (in_array('trone', $activites_portreal)) {
        $nb_personnes['trone'] = isset($_POST['nb_personnes_trone']) ? $_POST['nb_personnes_trone'] : null;
        $personnes = $nb_personnes['trone'];
        $activite_total_prix += $personnes * $activite_prix['trone'];
    }
    if (in_array('fleuve', $activites_portreal)) {
        $nb_personnes['fleuve'] =  isset($_POST['nb_personnes_fleuve']) ? $_POST['nb_personnes_fleuve'] : null;
        $personnes = $nb_personnes['fleuve'];
        $activite_total_prix += $personnes * $activite_prix['fleuve'];
    }
    if (in_array('dragons', $activites_meereen)) {
        $nb_personnes['dragons'] = isset($_POST['nb_personnes_dragons']) ? $_POST['nb_personnes_dragons'] : null;
        $personnes = $nb_personnes['dragons'];
        $activite_total_prix += $personnes * $activite_prix['dragons'];
    }
    if (in_array('gladiateur', $activites_meereen)) {
        $nb_personnes['gladiateur'] = isset($_POST['nb_personnes_gladiateur']) ? $_POST['nb_personnes_gladiateur'] : null;
        $personnes = $nb_personnes['gladiateur'];
        $activite_total_prix += $personnes * $activite_prix['gladiateur'];
    }
    if (in_array('marche', $activites_meereen)) {
        $nb_personnes['marche'] = $nb_personnes_marche = isset($_POST['nb_personnes_marche']) ? $_POST['nb_personnes_marche'] : null;
        $personnes = $nb_personnes['marche'];
        $activite_total_prix += $personnes * $activite_prix['marche'];
    }

   
    $prix_total = $prix * $nb_personnes_voyage + $activite_total_prix;

    // Vérifier si l'utilisateur est VIP et appliquer la réduction
  

    $etapes = ["Winterfell", "PortReal", "Meereen"];
    $return_date = null;
    if ($departure_date) {
        $departure_timestamp = strtotime($departure_date);
        
        $return_timestamp = strtotime("+7 days", $departure_timestamp);
        
        $return_date = date("Y-m-d", $return_timestamp);
    }

    $user_choices = [
        'user_id' => $user_id,
        'hebergement_winterfell' => $hebergement_winterfell,
        'hebergement_portreal' => $hebergement_portreal,
        'hebergement_meereen' => $hebergement_meereen,
        'activites_winterfell' => $activites_winterfell,
        'activites_portreal' => $activites_portreal,
        'activites_meereen' => $activites_meereen,
        'transport_winterfell' => $transport_winterfell,
        'transport_portreal' => $transport_portreal,
        'nb_personnes' => $nb_personnes,
        'prix_total' => $prix_total,
        'nb_personnes_voyage' => $nb_personnes_voyage,
        'departure_date' => $departure_date,
        'return_date' => $return_date, 
        'destination' => 'Westeros',
        "nb_etapes"=> 3,
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
                <img src="../images/GOT.webp" alt="Game of Thrones" >
                <div class="Lieuxdestinations">Westeros</div>
            </div>

            <p class="description">Préparez-vous à traverser des terres gelées, des châteaux de pierre, des marchés animés, et même à prendre place sur le Trône de Fer… si vous surviviez à l'aventure.  Enfilez vos bottes, préparez vos épées et rejoignez-nous pour un périple inoubliable dans les royaumes les plus sauvages et passionnants de l'histoire des séries télé !</p>

            <div class="buttons-container">
                <a href="#winterfell" class="Page-Accueil-button">JOUR 1-4 : Winterfell</a>
                <a href="#portreal" class="Page-Accueil-button">JOUR 5-7 : Port-Réal</a>
                <a href="#meereen" class="Page-Accueil-button">JOUR 7-10 : Meereen</a>
            </div>

        <form action="Westeros.php" method="POST">
            <div class="options-group">
                <label for="departure-date">Date de départ:</label>
                <input type="date" id="departure-date" name="departure_date" required min="<?php echo date('Y-m-d'); ?>">
                <label for="nb_voyage"><br>Nombre de personnes pour le voyage</label>
                <input type="number" id="nb_personnes_voyage" name="nb_personnes_voyage" min="1" value="1">
            </div>
          
            <div id="winterfell" class="section">
                <h2>JOUR 1-4 : Winterfell</h2>
                <p class="description">Bienvenue à Winterfell, là où le froid ne s'arrête jamais, mais l'hospitalité des Stark est toujours au rendez-vous ! Ici, vous allez apprendre à combattre comme un vrai guerrier du Nord, ou au moins à survivre à une session de « combat à l'épée ». Vous pensiez que les loups géants étaient mignons ? Détrompez-vous, ces bêtes ont de grandes dents et une envie de vengeance !</p>

                <div class="options-group">
                    <label for="hebergement_winterfell">Hébergement:</label>
                    <select id="hebergement_winterfell" name="hebergement_winterfell">
                        <option value="chateau_stark">Château Stark</option>
                        <option value="chateaunoir">Chateaunoir</option>
                        <option value="auberge">Auberge</option>
                    </select>
                </div>
                <div class="options-group">
                        <label for="activites-toggle" class="activites-label">Activités: 
                            <span class="arrow">&#9660;</span>  
                        </label>
                        <input type="checkbox" id="activites-toggle" class="activites-toggle">
                        
                        <div class="activites-options">
                            <div>
                               
                                <input type="checkbox" id="combat" name="activites_winterfell[]" value="combat">
                                <label for="combat">Entraînement au combat  (<?php echo $activite_prix['combat']; ?>€/personne)</label>
                                <input type="number" id="nb_personnes_combat" name="nb_personnes_combat" min="1" value="1">
                            </div>
                            <div>
                                <input type="checkbox" id="chasse" name="activites_winterfell[]" value="chasse">
                                <label for="chasse">Chasse avec les loups  (<?php echo $activite_prix['chasse']; ?>€/personne)</label>
                                <input type="number" id="nb_personnes_chasse" name="nb_personnes_chasse" min="1" value="1">
                            </div>
                            <div>
                                <input type="checkbox" id="mur" name="activites_winterfell[]" value="mur">
                                <label for="mur">Visite du Mur  (<?php echo $activite_prix['mur']; ?>€/personne)</label>
                                <input type="number" id="nb_personnes_mur" name="nb_personnes_mur" min="1" value="1">
                               
                            </div>
                        </div>
                    </div>
                    <div class="options-group">
                        <label for="transport_winterfell">Transport pour la prochaine étape:</label>
                        <select id="transport_winterfell" name="transport_winterfell">
                            <option value="chevaux">Chevaux</option>
                            <option value="pieds">A pieds</option>
                            <option value="caleche">Calèche</option>
                        </select>
                    </div>
                            
            </div>

            <!-- Port-Réal -->
            <div id="portreal" class="section">
                <h2>JOUR 5-7 : Port-Réal</h2>
                <p class="description">Bienvenue à Port-Réal, la capitale de Westeros, où les intrigues royales et les complots sont aussi nombreux que les grains de sable sur la plage (mais beaucoup moins agréables). C'est aussi l'endroit idéal pour boire une bière (ou cinq) après une journée bien remplie d'intrigues et de jeux de pouvoir. Attention à ne pas trop vous attacher à vos alliés... tout peut changer en un clin d'œil !</p>

                <div class="options-group">
                    <label for="hebergement_portreal">Hébergement:</label>
                    <select id="hebergement_portreal" name="hebergement_portreal">
                        <option value="donjon_royal">Donjon Royal</option>
                        <option value="auberge_marchands">Auberge des Marchands</option>
                        <option value="septuaire">Septuaire de Baelor</option>
                        <option value="bordel">Bordel</option>
                    </select>
                </div>

                <div class="options-group">
                        <label for="activites-toggle_port" class="activites-label">Activités: 
                            <span class="arrow">&#9660;</span>  
                        </label>
                        <input type="checkbox" id="activites-toggle_port" class="activites-toggle">
                        
                        <div class="activites-options">
                            <div>
                                
                                <input type="checkbox" id="tournoi" name="activites_portreal[]" value="tournoi">
                                <label for="tournoi">Tournoi de combat  (<?php echo $activite_prix['tournoi']; ?>€/personne)</label>
                                <input type="number" id="nb_personnes_tournoi" name="nb_personnes_tournoi" min="1" value="1">
                            </div>
                            <div>
                                <input type="checkbox" id="trone" name="activites_portreal[]" value="trone">
                                <label for="trone">Photos sur le Trône de Fer  (<?php echo $activite_prix['trone']; ?>€/personne)</label>
                                <input type="number" id="nb_personnes_trone" name="nb_personnes_trone" min="1" value="1">

                            </div>
                            <div>
                                <input type="checkbox" id="fleuve" name="activites_portreal[]" value="fleuve">
                                <label for="fleuve">Balade en bateau sur le Fleuve Noir  (<?php echo $activite_prix['fleuve']; ?>€/personne)</label>
                                <input type="number" id="nb_personnes_fleuve" name="nb_personnes_fleuve" min="1" value="1">
                               
                            </div>
                        </div>
                    </div>
                    <div class="options-group">
                        <label for="transport_portreal">Transport pour la prochaine étape:</label>
                        <select id="transport_portreal" name="transport_portreal">
                            <option value="bateau">Bâteau</option>
                            <option value="dos_dragon">Dragons</option>
                        </select>
                    </div>

            </div>

            <!-- Meereen -->
            <div id="meereen" class="section">
                <h2>JOUR 7-10 : Meereen</h2>
                <p class="description">Bienvenue à Meereen, la cité des esclaves libérés. Si vous avez un peu de temps libre, ne manquez pas l'occasion de visiter la Caverne des Dragons pour quelques photos souvenir. Mais, attention, ne regardez pas trop les dragons de près, car même un selfie peut être risqué si vous êtes trop près d'un de ces géants volants ! Vous repartirez avec des souvenirs (et probablement quelques cicatrices aussi).</p>

                <div class="options-group">
                    <label for="hebergement_meereen">Hébergement:</label>
                    <select id="hebergement_meereen" name="hebergement_meereen">
                        <option value="pyramide">Pyramide de Meereen</option>
                        <option value="caverne_dragons">Caverne des Dragons</option>
                        <option value="villa">Villa des nobles d'Essos</option>
                    </select>
                </div>
                <div class="options-group">
                        <label for="activites-toggle_meereen" class="activites-label">Activités: 
                            <span class="arrow">&#9660;</span>  
                        </label>
                        <input type="checkbox" id="activites-toggle_meereen" class="activites-toggle">
                        
                        <div class="activites-options">
                            <div>
                                
                                <input type="checkbox" id="dragons" name="activites_meereen[]" value="dragons">
                                <label for="dragons">Dompter des dragons  (<?php echo $activite_prix['dragons']; ?>€/personne)</label>
                                <input type="number" id="nb_personnes_dragons" name="nb_personnes_dragons" min="1" value="1">
                               
                            </div>
                            <div>
                                <input type="checkbox" id="gladiateur" name="activites_meereen[]" value="gladiateur">
                                <label for="gladiateur">Assistez aux combats de gladiateurs  (<?php echo $activite_prix['gladiateur']; ?>€/personne)</label>
                                <input type="number" id="nb_personnes_gladiateur" name="nb_personnes_gladiateur" min="1" value="1">
                               
                            </div>
                            <div>
                                <input type="checkbox" id="marche" name="activites_meereen[]" value="marche">
                                <label for="marche">Marché d'Essos  (<?php echo $activite_prix['marche']; ?>€/personne)</label>
                                <input type="number" id="nb_personnes_marche" name="nb_personnes_marche" min="1" value="1">
                               
                            </div>
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
   <script src="../Javascript/Westeros.js"></script>
';
require_once('../footer.php'); 
?>