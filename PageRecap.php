<?php
require_once 'load_env.php';  
require_once 'session.php'; 
$user_id = $_SESSION['user']['id'];
$transaction_id_get = isset($_GET['transaction_id']) ? $_GET['transaction_id'] : null;

if (!$transaction_id_get) {
    // Or redirect to an error page/profile page
    die("ID de transaction non fourni.");
}

$options_file = 'json/options.json';
$commandes_file = 'json/Commande.json'; // Added Commande.json
$etapes_file = 'json/Etapes_Options.json';

$trip_details = null; // This will hold the definitive details for display
$options_entry = null; // The entry from options.json
$commande_entry = null; // The entry from Commande.json

// 1. Find the relevant entry in options.json based on transaction_id from GET
if (file_exists($options_file)) {
    $options_data_all = json_decode(file_get_contents($options_file), true);
    if (is_array($options_data_all)) {
        foreach ($options_data_all as $item) {
            // Check for both direct transaction_id and payment_transaction_id
            $current_item_transaction_id = $item['transaction_id'] ?? $item['payment_transaction_id'] ?? null;
            if ($current_item_transaction_id === $transaction_id_get && (isset($item['user_id']) && $item['user_id'] == $user_id)) {
                $options_entry = $item;
                break;
            }
        }
    }
}

// 2. Fetch corresponding details from Commande.json
// The transaction_id to look for in Commande.json might be the one from options.json,
// or original_transaction_id if options_entry is a supplemental payment log.
$commande_lookup_id = $options_entry['original_transaction_id'] ?? $transaction_id_get;

if (file_exists($commandes_file)) {
    $commandes_data_all = json_decode(file_get_contents($commandes_file), true);
    if (is_array($commandes_data_all)) {
        foreach ($commandes_data_all as $commande) {
            if (isset($commande['transaction_id']) && $commande['transaction_id'] === $commande_lookup_id) {
                // Find the specific user's options within this commande
                if (isset($commande['options']) && is_array($commande['options'])) {
                    foreach ($commande['options'] as $opt) {
                        if (isset($opt['user_id']) && $opt['user_id'] === $user_id) {
                            // Check if this option set in Commande.json matches the transaction or original transaction
                            // For initial bookings, $opt['transaction_id'] might exist and match.
                            // For add-ons, we rely on the $commande_lookup_id matching $commande['transaction_id']
                            $commande_entry = $opt; // This is the detailed trip configuration
                            // If the $options_entry was for a payment (add-on), it might have its own transaction_id
                            // Store that too for reference if needed (e.g. displaying payment transaction id)
                            if(isset($options_entry['payment_transaction_id'])) {
                                $commande_entry['payment_transaction_id_for_addon'] = $options_entry['payment_transaction_id'];
                            }
                            // Ensure the main transaction_id from Commande.json (the trip's ID) is present
                            $commande_entry['main_transaction_id'] = $commande['transaction_id'];
                            break;
                        }
                    }
                }
                if ($commande_entry) break; // Found the user's options in the command
            }
        }
    }
}


// 3. Consolidate details: Prioritize Commande.json, fallback to options.json if needed for some fields
if ($commande_entry) {
    $trip_details = $commande_entry; // Commande.json is the primary source for trip structure
    // If options_entry has specific details not in commande_entry (e.g. specific added_price for an add-on) merge them if necessary
    // For now, we assume Commande.json's $commande_entry is comprehensive for recap
    $destination = $trip_details['destination'] ?? 'N/A';
} elseif ($options_entry) {
    // Fallback to options_entry if no corresponding Commande.json entry was found (less ideal)
    $trip_details = $options_entry;
    $destination = $trip_details['destination'] ?? 'N/A';
}


if (file_exists($etapes_file)) {
    $etapes_data = json_decode(file_get_contents($etapes_file), true);
}

?>

<?php require_once('header.php'); ?>

        <div class="panier-container">
            <?php if ($trip_details): ?>
                <div class="panier-details">
                    <h1>Récapitulatif de votre voyage - Destination : <?php echo htmlspecialchars($destination ?? 'N/A'); ?></h1>
                    
                    <?php 
                    
                    // Ensure 'etapes' is an array
                    $etapes_list = [];
                    if (isset($trip_details['etapes'])) {
                        $etapes_list = is_array($trip_details['etapes']) ? $trip_details['etapes'] : explode(',', $trip_details['etapes']);
                    }
                    
                    $total_etapes_count = isset($trip_details['nb_etapes']) ? (int)$trip_details['nb_etapes'] : count($etapes_list);
                    
                    for ($i = 0; $i < $total_etapes_count; $i++): 
                        if (!isset($etapes_list[$i])) continue; // Skip if etape is not defined
                        $current_step = $etapes_list[$i];
                        $clean_step = strtolower(str_replace(' ', '_', $current_step));
                     
                        $hebergement_key = 'hebergement_' . $clean_step;
                        $activites_key = 'activites_' . $clean_step;
                        $transport_key = 'transport_' . $clean_step;
                        
                        // Ensure $etapes_data and $destination are set before accessing them
                        $step_destination_data = null;
                        if (isset($etapes_data) && isset($destination) && isset($etapes_data[$destination][$current_step])) {
                            $step_destination_data = $etapes_data[$destination][$current_step];
                        }
                    ?>
                        <div class="voyage-details">
                            <h2><?php echo htmlspecialchars($current_step); ?></h2>
                            
                            <p><strong>Hébergement :</strong>
                                <?php
                                    if (isset($trip_details[$hebergement_key])) {
                                        $hebergements = $step_destination_data['hebergements'] ?? [];
                                        echo htmlspecialchars(
                                            $hebergements[$trip_details[$hebergement_key]] ?? 
                                            $trip_details[$hebergement_key]
                                        );
                                    } else {
                                        echo "Aucun hébergement sélectionné";
                                    }
                                ?>
                            </p>

                            <p><strong>Activités :</strong></p>
                            <ul>
                                <?php
                                    if (!empty($trip_details[$activites_key]) && is_array($trip_details[$activites_key])) {
                                        $activites_disponibles = $step_destination_data['activites'] ?? [];
                                        foreach ($trip_details[$activites_key] as $activite_code) {
                                            $activite_libelle = $activites_disponibles[$activite_code] ?? $activite_code;
                                            
                                            $nb_personnes_for_activite = 0;
                                            if(isset($trip_details['nb_personnes'][$activite_code])) {
                                                 $nb_personnes_for_activite = intval($trip_details['nb_personnes'][$activite_code]);
                                            }

                                            $prix_activite = 0;
                                            if(isset($trip_details['activite_prix'][$activite_code])) {
                                                $prix_activite = intval($trip_details['activite_prix'][$activite_code]);
                                            }
                                            
                                            $prix_total_activite = $prix_activite * $nb_personnes_for_activite;
                                             echo "<li>"  . htmlspecialchars($activite_libelle . " - " . $nb_personnes_for_activite . " personne" . ($nb_personnes_for_activite > 1 ? "s" : "")) . " - " . number_format($prix_total_activite, 2, ',', ' ') . " €</li>";
                                        }
                                    } else {
                                        echo "<li>Aucune activité sélectionnée pour cette étape</li>";
                                    }
                                ?>
                            </ul>

                            <?php 
                            if ($i < $total_etapes_count - 1): ?>
                            <p><strong>Transport pour la prochaine étape :</strong> 
                                <?php
                                    if (isset($trip_details[$transport_key])) {
                                        $transports = $step_destination_data['transports'] ?? [];
                                        echo htmlspecialchars(
                                            $transports[$trip_details[$transport_key]] ?? 
                                            $trip_details[$transport_key]
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
                        <p><strong>Date de départ :</strong> <?php echo htmlspecialchars($trip_details['departure_date'] ?? 'Non spécifiée'); ?></p>
                        <p><strong>Date de retour :</strong> <?php echo htmlspecialchars($trip_details['return_date'] ?? 'Non spécifiée'); ?></p>
                        <p><strong>Nombre total de personnes :</strong> <?php echo htmlspecialchars($trip_details['nb_personnes_voyage'] ?? 'Non spécifié'); ?></p>
                        <p><strong>Prix total :</strong> <?php echo number_format(floatval($trip_details['prix_total'] ?? 0), 2, ',', ' '); ?> €</p>
                    </div>
                    
                    <div class='recherche'>
                        <?php
                        // Determine transaction ID for payment:
                        // If it was an options_entry for an add-on, it has 'payment_transaction_id'.
                        // Otherwise, it's the main trip's transaction_id.
                        $payment_transaction_id_for_url = $options_entry['payment_transaction_id'] ?? $trip_details['main_transaction_id'] ?? $transaction_id_get;
                        $montant_for_url = $options_entry['added_price'] ?? $trip_details['prix_total'] ?? 0;
                        $type_for_url = isset($options_entry['added_price']) ? 'options' : 'reservation';

                        // Only show payment button if status is not 'accepted' or if it is an add-on payment entry.
                        $is_already_paid_main_trip = isset($trip_details['status']) && $trip_details['status'] === 'accepted' && $type_for_url === 'reservation';
                        $is_pending_add_on_payment = isset($options_entry['status']) && $options_entry['status'] === 'pending_payment' && $type_for_url === 'options';
                        $is_fresh_reservation_not_yet_paid = !isset($trip_details['status']) && $type_for_url === 'reservation'; // Or status is 'pending' etc.

                        if ($is_pending_add_on_payment || $is_fresh_reservation_not_yet_paid) {
                            // For a fresh reservation not yet in Commande.json fully or an add-on, the $options_entry will drive payment.
                            if ($options_entry) {
                                $payment_transaction_id_for_url = $options_entry['transaction_id'] ?? $options_entry['payment_transaction_id'] ?? $transaction_id_get;
                                // If it's an add-on from options.json, use its specific price.
                                if (isset($options_entry['added_price'])) {
                                    $montant_for_url = $options_entry['added_price'];
                                    $type_for_url = 'options'; // Explicitly options
                                } else { // Otherwise it's a full reservation price from options.json (less ideal but fallback)
                                    $montant_for_url = $options_entry['prix_total'] ?? 0;
                                    $type_for_url = 'reservation';
                                }
                            }
                            echo '<a href="pagePayer.php?transaction_id=' . urlencode($payment_transaction_id_for_url) . '&montant=' . urlencode(strval($montant_for_url)) . '&type=' . urlencode($type_for_url) . '" class="Page-Accueil-button">Procéder au paiement</a>';
                        } elseif ($is_already_paid_main_trip) {
                            echo '<p>Ce voyage a déjà été payé. <a href="PageMesVoyages.php?id='.urlencode($trip_details['main_transaction_id'] ?? $transaction_id_get).'">Voir les détails de mon voyage</a></p>';
                        } else {
                             // Default/fallback payment link if logic above doesn't catch it, points to initial reservation details.
                             // This case might indicate an unpaid initial reservation that has made it to Commande.json without 'accepted' status
                             $final_payment_tid = $trip_details['main_transaction_id'] ?? $transaction_id_get;
                             $final_montant = $trip_details['prix_total'] ?? 0;
                             echo '<a href="pagePayer.php?transaction_id=' . urlencode($final_payment_tid) . '&montant=' . urlencode(strval($final_montant)) . '&type=reservation" class="Page-Accueil-button">Procéder au paiement</a>';
                        }
                        ?>
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