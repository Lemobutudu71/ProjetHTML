<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: PageSeconnecter.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$transaction_id = $_GET['id'] ?? null;

if (!$transaction_id) {
    header("Location: PageProfil.php");
    exit();
}

$commandes_file = 'json/Commande.json';
$etapes_file = 'json/Etapes_Options.json';
// $options_file = 'json/options.json'; // This file seems to be a log, not a source of options

$commandes = [];
$etapes_options_data = []; // Renamed for clarity

if (file_exists($commandes_file)) {
    $commandes_json = file_get_contents($commandes_file);
    if ($commandes_json === false) { /* Handle error reading commandes_file */ $commandes = []; }
    else { $commandes = json_decode($commandes_json, true); }
    if ($commandes === null) { /* Handle JSON decode error for commandes */ $commandes = []; }
}

if (file_exists($etapes_file)) {
    $etapes_options_json = file_get_contents($etapes_file);
    if ($etapes_options_json === false) { /* Handle error reading etapes_file */ $etapes_options_data = []; }
    else { $etapes_options_data = json_decode($etapes_options_json, true); }
    if ($etapes_options_data === null) { /* Handle JSON decode error for etapes_options */ $etapes_options_data = []; }
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
    // Consider a user-friendly error page or message
    // For now, redirecting as before
    header("Location: PageProfil.php?error=trip_not_found");
    exit();
}

$destination_name = $current_trip_options['destination'] ?? 'N/A';
$trip_etapes_names = [];
if (isset($current_trip_options['etapes'])) {
    $trip_etapes_names = is_array($current_trip_options['etapes']) ? $current_trip_options['etapes'] : explode(',', $current_trip_options['etapes']);
}


// POST handling logic remains the same for now
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_options_selected = [];
    $total_new_price = 0;
    $new_nb_personnes_map = [];
    
    foreach ($trip_etapes_names as $etape_name_loop) {
        $clean_etape_name = strtolower(str_replace(' ', '_', $etape_name_loop));
        $posted_activites_key = 'activites_' . $clean_etape_name;
        
        if (isset($_POST[$posted_activites_key]) && is_array($_POST[$posted_activites_key])) {
            $selected_activites_for_etape = $_POST[$posted_activites_key];
            $new_options_selected[$posted_activites_key] = $selected_activites_for_etape; // Store all selected, even if already present for simplicity here, can refine
            
            foreach ($selected_activites_for_etape as $activite_code) {
                // Check if it's genuinely a new activity not previously selected for this etape
                $current_etape_activities_key = 'activites_' . $clean_etape_name;
                $already_selected_activities_for_etape = $current_trip_options[$current_etape_activities_key] ?? [];
                
                if (!in_array($activite_code, $already_selected_activities_for_etape)) {
                    $nb_personnes_for_activite = isset($_POST['nb_personnes'][$activite_code]) ? intval($_POST['nb_personnes'][$activite_code]) : 1;
                    $price_of_activite = $current_trip_options['activite_prix'][$activite_code] ?? 0; // Prices should be in current_trip_options from Commande.json
                    $total_new_price += $price_of_activite * $nb_personnes_for_activite;
                    $new_nb_personnes_map[$activite_code] = $nb_personnes_for_activite;
                }
            }
        }
    }
    
    if ($total_new_price > 0) {
        $new_payment_transaction_id = uniqid('pay_'); // Make it distinct
        // Update Commande.json
        $updated_commandes = $commandes; // Work on a copy
        $commande_found_for_update = false;
        foreach ($updated_commandes as &$cmd_ref) { // Use reference
            if (isset($cmd_ref['transaction_id']) && $cmd_ref['transaction_id'] === $transaction_id) {
                 $commande_found_for_update = true;
                if (isset($cmd_ref['options']) && is_array($cmd_ref['options'])) {
                    foreach ($cmd_ref['options'] as &$opt_ref) { // Use reference
                        if (isset($opt_ref['user_id']) && $opt_ref['user_id'] === $user_id) {
                            // Merge newly selected activities
                            foreach ($new_options_selected as $key_activites_etape => $value_new_activites) {
                                if (!isset($opt_ref[$key_activites_etape]) || !is_array($opt_ref[$key_activites_etape])) {
                                    $opt_ref[$key_activites_etape] = [];
                                }
                                $opt_ref[$key_activites_etape] = array_unique(array_merge($opt_ref[$key_activites_etape], $value_new_activites));
                            }
                            // Merge nb_personnes for these new activities
                            if (!isset($opt_ref['nb_personnes']) || !is_array($opt_ref['nb_personnes'])) {
                                $opt_ref['nb_personnes'] = [];
                            }
                            $opt_ref['nb_personnes'] = array_merge($opt_ref['nb_personnes'], $new_nb_personnes_map);
                            $opt_ref['prix_total'] = ($opt_ref['prix_total'] ?? 0) + $total_new_price;
                            // Potentially update a "last_modified_options" timestamp or similar if needed
                            break; // Found and updated the specific user's options
                        }
                    }
                }
                break; // Found and processed the main commande
            }
        }
        unset($cmd_ref); unset($opt_ref); // Unset references

        if ($commande_found_for_update) {
            if (file_put_contents($commandes_file, json_encode($updated_commandes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
                 // Create a new entry in options.json (if that's its purpose for payment logging) or similar
                 // For now, redirecting to payment page.
                 // The new_transaction_id for payment should be distinct.
                 // Let's assume options.json is for logging successful additions that go to payment
                $options_log_file = 'json/options.json';
                $options_log_data = [];
                if(file_exists($options_log_file)) {
                    $options_log_json = file_get_contents($options_log_file);
                    if ($options_log_json !== false) {
                        $options_log_data = json_decode($options_log_json, true);
                        if ($options_log_data === null) $options_log_data = [];
                    }
                }
                // Construct an entry for what's being paid for NOW
                $payment_log_entry = [
                    'payment_transaction_id' => $new_payment_transaction_id,
                    'original_transaction_id' => $transaction_id,
                    'user_id' => $user_id,
                    'added_options' => $new_options_selected, // what was just added
                    'nb_personnes_for_added' => $new_nb_personnes_map,
                    'added_price' => $total_new_price,
                    'date_added' => date('Y-m-d H:i:s'),
                    'status' => 'pending_payment' // Update upon successful payment
                ];
                $options_log_data[] = $payment_log_entry;
                file_put_contents($options_log_file, json_encode($options_log_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

                header("Location: pagePayer.php?transaction_id=" . $new_payment_transaction_id . "&montant=" . $total_new_price . "&type=options");
                exit();
            } else {
                // Error saving Commande.json
                // Handle this error (e.g., display message to user)
                 echo "Erreur: Impossible de sauvegarder les modifications dans Commande.json.";
                 exit;
            }
        } else {
            // Commande not found for update, should not happen if initial checks passed
            echo "Erreur: Commande non trouvée pour la mise à jour.";
            exit;
        }
    } else {
        // No new options selected or price is zero, perhaps redirect back or show message
        // For now, just falling through will re-render the page.
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
                    <!-- JavaScript will populate this section -->
                    <p class="loading-text">Chargement des activités...</p>
                </div>
            </div>
        <?php endforeach; ?>
        
        <div class="recherche">
            <button type="submit" class="Page-Accueil-button">Ajouter les options sélectionnées et Payer</button>
            <a href="PageMesvoyages.php?id=<?php echo urlencode($transaction_id); ?>" class="Page-Accueil-button">Retour à Mon Voyage</a>
        </div>
    </form>

</div>

<script>
    // Pass PHP data to JavaScript
    const tripDataForJS = <?php echo json_encode([
        'destination' => $destination_name,
        'etapes' => $trip_etapes_names,
        'etapesOptions' => $etapes_options_data, // All available options
        'currentTripOptions' => $current_trip_options, // User's current selections from Commande.json
        'nbPersonnesVoyage' => $current_trip_options['nb_personnes_voyage'] ?? 1 // Max persons for the trip
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE); ?>;
</script>

<?php 
// Include the new JS file here (assuming it will be created in Javascript/dynamicTripOptions.js)
$scripts = '<script src="Javascript/dynamicTripOptions.js"></script>';
require_once('footer.php'); 
?>