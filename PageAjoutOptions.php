<?php
require_once 'load_env.php';  
require_once 'session.php'; 

$user_id = $_SESSION['user']['id'];
$transaction_id = $_GET['id'] ?? null;

if (!$transaction_id) {
    header("Location: PageProfil.php");
    exit();
}

$commandes_file = 'json/Commande.json';
$etapes_file = 'json/Etapes_Options.json';
// $options_file = 'json/options.json'; 

$commandes = [];
$etapes_options_data = []; 

if (file_exists($commandes_file)) {
    $commandes_json = file_get_contents($commandes_file);
    if ($commandes_json === false) { 
         $commandes = []; 
        }
    else { 
        $commandes = json_decode($commandes_json, true);
     }
    if ($commandes === null) { 
        $commandes = []; }
}

if (file_exists($etapes_file)) {
    $etapes_options_json = file_get_contents($etapes_file);
    if ($etapes_options_json === false) {
         $etapes_options_data = []; 
        }
    else {
         $etapes_options_data = json_decode($etapes_options_json, true); 
        }
    if ($etapes_options_data === null) { 
         $etapes_options_data = []; 
        }
}

$commande_details = null;
$current_trip_options = null;

foreach ($commandes as $cmd_item) {
    if (isset($cmd_item['transaction_id']) && $cmd_item['transaction_id'] === $transaction_id) {
        $commande_details = $cmd_item;
        if (isset($cmd_item['options']) && is_array($cmd_item['options'])) {
            foreach ($cmd_item['options'] as $option_item) {
                if (isset($option_item['user_id']) && $option_item['user_id'] === $user_id) {
                    $current_trip_options = $option_item;
                    break;
                }
            }
        }
        break;
    }
}

if (!$commande_details || !$current_trip_options) {
    
    header("Location: PageProfil.php?error=trip_not_found");
    exit();
}

$destination_name = $current_trip_options['destination'] ?? 'N/A';
$trip_etapes_names = [];
if (isset($current_trip_options['etapes'])) {
    $trip_etapes_names = is_array($current_trip_options['etapes']) ? $current_trip_options['etapes'] : explode(',', $current_trip_options['etapes']);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_options_selected = [];
    $total_new_price = 0;
    $new_nb_personnes_map = [];
    
    foreach ($trip_etapes_names as $etape_name_loop) {
        $clean_etape_name = strtolower(str_replace(' ', '_', $etape_name_loop));
        $posted_activites_key = 'activites_' . $clean_etape_name;
        
        if (isset($_POST[$posted_activites_key]) && is_array($_POST[$posted_activites_key])) {
            $selected_activites_for_etape = $_POST[$posted_activites_key];
            $new_options_selected[$posted_activites_key] = $selected_activites_for_etape;
            
            foreach ($selected_activites_for_etape as $activite_code) {
                $nb_personnes_for_activite = isset($_POST['nb_personnes'][$activite_code]) ? intval($_POST['nb_personnes'][$activite_code]) : 1;
                $price_of_activite = $current_trip_options['activite_prix'][$activite_code] ?? 0;
                $total_new_price += $price_of_activite * $nb_personnes_for_activite;
                $new_nb_personnes_map[$activite_code] = $nb_personnes_for_activite;
            }
        }
    }
    
    if ($total_new_price > 0) {
        // Mise à jour directe de Commande.json
        $updated_commandes = $commandes;
        $commande_found_for_update = false;
        
        foreach ($updated_commandes as &$cmd_ref) {
            if (isset($cmd_ref['transaction_id']) && $cmd_ref['transaction_id'] === $transaction_id) {
                $commande_found_for_update = true;
                if (isset($cmd_ref['options']) && is_array($cmd_ref['options'])) {
                    foreach ($cmd_ref['options'] as &$opt_ref) {
                        if (isset($opt_ref['user_id']) && $opt_ref['user_id'] === $user_id) {
                            // Fusion des nouvelles activités sélectionnées
                            foreach ($new_options_selected as $key_activites_etape => $value_new_activites) {
                                if (!isset($opt_ref[$key_activites_etape]) || !is_array($opt_ref[$key_activites_etape])) {
                                    $opt_ref[$key_activites_etape] = [];
                                }
                                $opt_ref[$key_activites_etape] = array_unique(array_merge($opt_ref[$key_activites_etape], $value_new_activites));
                            }
                            
                            // Mise à jour du nombre de personnes
                            if (!isset($opt_ref['nb_personnes']) || !is_array($opt_ref['nb_personnes'])) {
                                $opt_ref['nb_personnes'] = [];
                            }
                            $opt_ref['nb_personnes'] = array_merge($opt_ref['nb_personnes'], $new_nb_personnes_map);
                            
                            // Stocker simplement le total des options
                            $opt_ref['options_prix'] = $total_new_price;
                            $opt_ref['prix_total'] = ($opt_ref['prix_total'] ?? 0) + $total_new_price;
                            break;
                        }
                    }
                }
                break;
            }
        }
        unset($cmd_ref); unset($opt_ref);

        if ($commande_found_for_update) {
            if (file_put_contents($commandes_file, json_encode($updated_commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
                header("Location: pagePayer.php?transaction_id=" . $transaction_id . "&montant=" . $total_new_price . "&type=options");
                exit();
            } else {
                echo "Erreur: Impossible de sauvegarder les modifications dans Commande.json.";
                exit;
            }
        } else {
            echo "Erreur: Commande non trouvée pour la mise à jour.";
            exit;
        }
    }
}
?>

<?php require_once('header.php'); ?>

<div class="Page-Accueil2-text">

    <h1>Ajouter des options à votre voyage - <?php echo htmlspecialchars($destination_name); ?></h1>
    
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . '?id=' . urlencode($transaction_id)); ?>">
        <?php foreach ($trip_etapes_names as $etape_name_render): 
            $clean_etape_name_render = strtolower(str_replace(' ', '_', $etape_name_render));
        ?>
            <div class="section etape-section" data-etape-nom="<?php echo htmlspecialchars($etape_name_render); ?>" data-etape-clean-nom="<?php echo htmlspecialchars($clean_etape_name_render); ?>">
                <h2><?php echo htmlspecialchars($etape_name_render); ?></h2>
                
                <div class="activites-container" id="activites-container-<?php echo htmlspecialchars($clean_etape_name_render); ?>">
                    <h3>Activités disponibles</h3>
                    
                    <p class="loading-text">Chargement des activités...</p>
                </div>
            </div>
        <?php endforeach; ?>
        
        <div class="recherche">
            <button type="submit" class="Page-Accueil-button">Ajouter </button>
            <a href="PageMesvoyages.php?id=<?php echo urlencode($transaction_id); ?>" class="Page-Accueil-button">Retour à Mon Voyage</a>
        </div>
    </form>

</div>

<script>
    const tripDataForJS = <?php echo json_encode([
        'destination' => $destination_name,
        'etapes' => $trip_etapes_names,
        'etapesOptions' => $etapes_options_data, 
        'currentTripOptions' => $current_trip_options, 
        'nbPersonnesVoyage' => $current_trip_options['nb_personnes_voyage'] ?? 1 
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE); ?>;
</script>

<?php 
$scripts = '<script src="Javascript/dynamicTripOptions.js"></script>';
require_once('footer.php'); 
?>