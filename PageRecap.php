<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: PageSeconnecter.php"); 
    exit();
}


$user_id = $_SESSION['user']['id'];
$transaction_id = isset($_GET['transaction_id']) ? $_GET['transaction_id'] : null;

$options_file = 'json/options.json';
$etapes_file = 'json/Etapes_Options.json';

$user_choices = null;
$destination_data = null;


if (file_exists($options_file)) {
    $user_data = json_decode(file_get_contents($options_file), true);
    
    foreach ($user_data as $data) {
        if (isset($data['user_id']) && $data['user_id'] == $user_id) {
            $user_choices = $data;
            $destination = $user_choices['destination'];
        }
    }
}

if (file_exists($etapes_file)) {
    $etapes_data = json_decode(file_get_contents($etapes_file), true);
}


?>

<?php require_once('header.php'); ?>

        <div class="panier-container">
            <?php if ($user_choices): ?>
                <div class="panier-details">
                    <h1>Récapitulatif de votre voyage - Destination : <?php echo htmlspecialchars($destination); ?></h1>
                    
                    <?php 
                    
                    $etapes = is_array($user_choices['etapes']) ? $user_choices['etapes'] : explode(',', $user_choices['etapes']);
                    
                  
                    $total_etapes = $user_choices['nb_etapes'];
                    
                    for ($i = 0; $i < $total_etapes; $i++): 
                        $current_step = $etapes[$i];
                        $clean_step = strtolower(str_replace(' ', '_', $current_step));
                     
                        $hebergement_key = 'hebergement_' . $clean_step;
                        $activites_key = 'activites_' . $clean_step;
                        $transport_key = 'transport_' . $clean_step;
                        
                        $step_destination_data = $etapes_data[$destination][$current_step];
                    ?>
                        <div class="voyage-details">
                            <h2><?php echo htmlspecialchars($current_step); ?></h2>
                            
                            <p><strong>Hébergement :</strong>
                                <?php
                                    if (isset($user_choices[$hebergement_key])) {
                                        // Utiliser les hébergements de la destination
                                        $hebergements = $step_destination_data['hebergements'] ?? [];
                                        
                                        echo htmlspecialchars(
                                            $hebergements[$user_choices[$hebergement_key]] ?? 
                                            $user_choices[$hebergement_key]
                                        );
                                    } else {
                                        echo "Aucun hébergement sélectionné";
                                    }
                                ?>
                            </p>

                            <p><strong>Activités :</strong></p>
                            <ul>
                                <?php
                                    
                                    if (!empty($user_choices[$activites_key])) {
                                        
                                        $activites_disponibles = $step_destination_data['activites'] ?? [];
                                        
                                        foreach ($user_choices[$activites_key] as $activite) {
                                            $activite_libelle = $activites_disponibles[$activite] ?? $activite;
                                            
                                            
                                            $nb_personnes = isset($user_choices['nb_personnes'][$activite]) 
                                                ? intval($user_choices['nb_personnes'][$activite]) 
                                                : 0;

                                            
                                            $prix_activite = isset($user_choices['activite_prix'][$activite]) 
                                                ? intval($user_choices['activite_prix'][$activite])
                                                : 0;

                                            
                                            $prix_total_activite = $prix_activite * $nb_personnes;
                                             echo "<li>"  . htmlspecialchars($activite_libelle . "-" . $nb_personnes . " personne" . ($nb_personnes > 1 ? "s" : "")) . " - " . number_format($prix_total_activite, 2, ',', ' ') . " € " ."</li>";

                                        }
                                    } else {
                                        echo "<li>Aucune activité sélectionnée</li>";
                                    }
                                ?>
                            </ul>

                            <?php 
                        
                            if ($i < $total_etapes - 1): ?>
                            <p><strong>Transport pour la prochaine étape :</strong> 
                                <?php
                        
                                    if (isset($user_choices[$transport_key])) {
                                     
                                        $transports = $step_destination_data['transports'] ?? [];
                                        
                                        echo htmlspecialchars(
                                            $transports[$user_choices[$transport_key]] ?? 
                                            $user_choices[$transport_key]
                                        );
                                    } else {
                                        echo "Aucun transport sélectionné";
                                    }
                                ?>
                            </p>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>
                    
                    <div class="voyage-resume">
                        <p><strong>Date de départ :</strong> <?php echo htmlspecialchars($user_choices['departure_date']); ?></p>
                        <p><strong>Date de retour :</strong> <?php echo htmlspecialchars($user_choices['return_date']); ?></p>
                        <p><strong>Nombre total de personnes :</strong> <?php echo htmlspecialchars($user_choices['nb_personnes_voyage']); ?></p>
                        <p><strong>Prix total :</strong> <?php echo number_format($user_choices['prix_total'], 2, ',', ' '); ?> €</p>
                    </div>
                    
                    <div class='recherche'>
                        <a href="pagePayer.php" class="Page-Accueil-button">Procéder au paiement</a>
                    </div>
                    <div class='recherche'>
                        <form>
                            <input type="button" class='Page-Accueil-button' value="revenir à la page précédente" onclick="history.go(-1)">
                        </form>
                    
                    
                        
                </div>
            <?php else: ?>
                <div class="panier-vide">
                    <h1>Votre panier est vide</h1>
                    <p>Vous n'avez pas encore ajouté de voyage à votre panier.</p>
                    <a href="PageAccueil2.php" class="btn-rechercher">Rechercher un voyage</a>
                </div>
            <?php endif; ?>
        </div>

        <?php 
$scripts = '
';
require_once('footer.php'); 
?>