<?php
require_once 'load_env.php';  
require_once 'session.php'; 
require('getapikey.php');


// Récupérer les informations de l'utilisateur et du voyage
$user_id = $_SESSION['user']['id'];
$display_details = []; 
$options_file = 'json/options.json';
$commandes_file = 'json/Commande.json';

$transaction_id_get = $_GET['transaction_id'] ?? null;
$montant_get = $_GET['montant'] ?? null;
$payment_type = $_GET['type'] ?? 'reservation'; 

$transaction_for_platform = null;
$montant_for_platform = null;

if ($payment_type === 'options' && $transaction_id_get && $montant_get) {
   
    $montant_for_platform = $montant_get;
    error_log("[pagePayer.php DEBUG INSIDE OPTIONS BLOCK] $montant_for_platform set to: " . print_r($montant_for_platform, true));

    
    $options_entry_for_payment = null;
    if (file_exists($options_file)) {
        $all_options_entries = json_decode(file_get_contents($options_file), true);
        if (is_array($all_options_entries)) {
            foreach ($all_options_entries as $entry) {
                if (isset($entry['transaction_id']) && $entry['transaction_id'] === $transaction_id_get && 
                    isset($entry['user_id']) && $entry['user_id'] == $user_id) {
                    $options_entry_for_payment = $entry;
                    break;
                }
            }
        }
    }

    if ($options_entry_for_payment && isset($options_entry_for_payment['original_transaction_id'])) {
        $transaction_for_platform = $options_entry_for_payment['original_transaction_id'];
    } else {
        error_log("[pagePayer.php ERROR] Could not find original_transaction_id for options payment: " . $transaction_id_get);
        $transaction_for_platform = $transaction_id_get; 
    }
    $_SESSION['transaction'] = $transaction_id_get;
    error_log("[pagePayer.php DEBUG INSIDE OPTIONS BLOCK] $transaction_for_platform set to: " . print_r($transaction_for_platform, true));

  
    if (file_exists($commandes_file) && isset($transaction_for_platform)) {
        $all_commandes = json_decode(file_get_contents($commandes_file), true);
        if (is_array($all_commandes)) {
            foreach ($all_commandes as $commande) {
                if (isset($commande['transaction_id']) && $commande['transaction_id'] === $transaction_for_platform) {
                    if (isset($commande['options']) && is_array($commande['options'])) {
                        foreach ($commande['options'] as $opt) {
                            if (isset($opt['user_id']) && $opt['user_id'] === $user_id) {
                                $display_details['destination'] = $opt['destination'] ?? 'N/A';
                              
                                break;
                            }
                        }
                    }
                    break;
                }
            }
        }
    }

} elseif ($transaction_id_get && $montant_get) {
 
    $transaction_for_platform = $transaction_id_get;
    $montant_for_platform = $montant_get;
    $_SESSION['transaction'] = $transaction_for_platform;

    
    if (file_exists($commandes_file)) {
        $all_commandes = json_decode(file_get_contents($commandes_file), true);
        if (is_array($all_commandes)) {
            foreach ($all_commandes as $commande) {
                if (isset($commande['transaction_id']) && $commande['transaction_id'] === $transaction_for_platform) {
                    if (isset($commande['options']) && is_array($commande['options'])) {
                        foreach ($commande['options'] as $opt) {
                            if (isset($opt['user_id']) && $opt['user_id'] === $user_id) {
                                $display_details = $opt; 
                                break;
                            }
                        }
                    }
                    break;
                }
            }
        }
    }

    if (empty($display_details) && file_exists($options_file)) {
        $options_data = json_decode(file_get_contents($options_file), true);
        if (is_array($options_data)) {
            foreach ($options_data as $option_item) {
                if (isset($option_item['transaction_id']) && $option_item['transaction_id'] === $transaction_for_platform &&
                    isset($option_item['user_id']) && $option_item['user_id'] === $user_id) {
                    $display_details = $option_item;
                    break;
                }
            }
        }
    }
} else {
    
    $user_choices_fallback = null; 
    if (file_exists($options_file)) {
        $user_data = json_decode(file_get_contents($options_file), true);
        if(is_array($user_data)) {
            foreach ($user_data as $data) {
                if (isset($data['user_id']) && $data['user_id'] == $user_id && (!isset($data['status']) || $data['status'] !== 'accepted')) {
                    $user_choices_fallback = $data; 
                }
            }
        }
    }
    if ($user_choices_fallback) {
        $montant_for_platform = $user_choices_fallback['prix_total'] ?? null;
        $transaction_for_platform = $user_choices_fallback['transaction_id'] ?? null;
        $_SESSION['transaction'] = $transaction_for_platform;
        $display_details = $user_choices_fallback; // Populate $display_details
    } else {
        die("Erreur: Impossible de récupérer les détails de la transaction pour le paiement.");
    }
}

if ($transaction_for_platform === null || $montant_for_platform === null) {
    die("Erreur: Données de transaction ou montant manquantes pour initier le paiement.");
}


$vendeur = 'MEF-1_B';

$script_name = $_SERVER['SCRIPT_NAME'];
$script_dir = dirname($script_name);
$project_root = str_replace('\\', '/', dirname(dirname(__FILE__))); 
$document_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']); 
$relative_path = '';

if (strpos($project_root, $document_root) === 0) {
    $relative_path = substr($project_root, strlen($document_root));
}
// Déterminer l'URL de retour
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$base_url = $protocol . "://" . $host . $script_dir;

$retour_url_query_transaction = $transaction_for_platform; 
$retour = "{$base_url}/retour_paiement.php?transaction={$retour_url_query_transaction}";
$_SESSION['retour'] = $retour; 

// Log values before generating control hash
error_log("[pagePayer.php DEBUG] Payment Type: " . $payment_type);
error_log("[pagePayer.php DEBUG] Transaction ID from GET: " . print_r($transaction_id_get, true));
error_log("[pagePayer.php DEBUG] Montant from GET: " . print_r($montant_get, true));
error_log("[pagePayer.php DEBUG] Transaction FOR PLATFORM (before hash): " . print_r($transaction_for_platform, true));
error_log("[pagePayer.php DEBUG] Montant FOR PLATFORM (before hash): " . print_r($montant_for_platform, true)); // Log it again right before use
error_log("[pagePayer.php DEBUG] Vendeur: " . print_r($vendeur, true));
error_log("[pagePayer.php DEBUG] Retour URL for session (and used in hash): " . $retour);


$api_key = getAPIKey($vendeur); 
// Use $transaction_for_platform and $montant_for_platform for control hash generation
$control = md5($api_key . "#" . $transaction_for_platform . "#" . $montant_for_platform . "#" . $vendeur . "#" . $retour . "#");
$_SESSION['control'] = $control;

error_log("[pagePayer.php DEBUG] API Key used: " . $api_key);
error_log("[pagePayer.php DEBUG] Control hash generated (based on _for_platform vars): " . $control);

?>

<?php require_once('header.php'); ?>
        
        <div class="description">
            <h2 class='h2'>Récapitulatif de la commande</h2>
            <?php 
                // Display based on what was actually processed for the platform
                $display_montant = $montant_for_platform;
                $display_transaction = $transaction_for_platform; // Or $transaction_id_get if more appropriate for display
            ?>
            <?php if ($payment_type === 'options'): ?>
                <p><strong>Type de paiement :</strong> Options supplémentaires</p>
                <p><strong>Montant à payer :</strong> <?php echo number_format($display_montant, 2, ',', ' '); ?> €</p>
                <p><em>(Pour la réservation: <?php echo htmlspecialchars($display_transaction); ?>)</em></p>
            <?php else: ?>
                <?php 
                    $disp_destination = $display_details['destination'] ?? 'N/A';
                    $disp_departure_date = $display_details['departure_date'] ?? 'N/A';
                    $disp_return_date = $display_details['return_date'] ?? 'N/A';
                    $disp_nb_personnes = $display_details['nb_personnes_voyage'] ?? 'N/A';
                ?>
                <p><strong>Transaction ID :</strong> <?php echo htmlspecialchars($transaction_for_platform); ?></p>
                <p><strong>Destination :</strong> <?php echo htmlspecialchars($disp_destination); ?></p>
                <p><strong>Date de départ :</strong> <?php echo htmlspecialchars($disp_departure_date); ?></p>
                <p><strong>Date de retour :</strong> <?php echo htmlspecialchars($disp_return_date); ?></p>
                <p><strong>Nombre de personnes :</strong> <?php echo htmlspecialchars($disp_nb_personnes); ?></p>
                <p><strong>Montant total :</strong> <?php echo number_format($montant_for_platform, 2, ',', ' '); ?> €</p>
            <?php endif; ?>
        </div>

        <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST" class="carte">
            <input type="hidden" name="transaction" value="<?php echo htmlspecialchars($transaction_for_platform); ?>">
            <input type="hidden" name="montant" value="<?php echo htmlspecialchars(strval($montant_for_platform)); ?>">
            <input type="hidden" name="vendeur" value="<?php echo htmlspecialchars($vendeur); ?>">
            <input type="hidden" name="retour" value="<?php echo htmlspecialchars($retour); ?>">
            <input type="hidden" name="control" value="<?php echo htmlspecialchars($control); ?>">
            <div class='recherche'>
                <button type="submit" class="Page-Accueil-button">Valider et Payer</button>
            </div>
        </form>

<?php 
$scripts = '';
require_once('footer.php'); 
?>      