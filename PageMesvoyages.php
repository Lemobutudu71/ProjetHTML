<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: PageSeconnecter.php");
    exit();
}


$commandes_file = 'json/Commande.json';
$etapes_file = 'json/Etapes_Options.json';


$transaction_id = $_GET['id'] ?? null;

if (!$transaction_id) {
    die("Transaction manquante.");
}


$commandes = [];
$etapes_data = [];

if (file_exists($commandes_file)) {
    $commandes = json_decode(file_get_contents($commandes_file), true);
}

if (file_exists($etapes_file)) {
    $etapes_data = json_decode(file_get_contents($etapes_file), true);
}

$commande = null;
$option_voyage = null;

foreach ($commandes as $cmd) {
    if ($cmd['transaction_id'] === $transaction_id) {
        $commande = $cmd;
       
        foreach ($cmd['options'] as $option) {
            if ($option['user_id'] === $_SESSION['user']['id']) {
                $option_voyage = $option;
                break;
            }
        }
        break;
    }
}

if (!$commande || !$option_voyage) {
    die("Voyage introuvable ou vous n'avez pas accès à cette commande.");
}

$destination = $option_voyage['destination'];
$etapes = is_array($option_voyage['etapes']) ? $option_voyage['etapes'] : explode(',', $option_voyage['etapes']);
$total_etapes = $option_voyage['nb_etapes'];
?>

<?php require_once('header.php'); ?>

        <div class="panier-container">
            <div class="panier-details">
                <h1>Détails de votre voyage - Destination : <?php echo htmlspecialchars($destination); ?></h1>
                
                <div class="voyage-resume">
                    <p><strong>Date de départ :</strong> <?php echo htmlspecialchars($option_voyage['departure_date']); ?></p>
                    <p><strong>Date de retour :</strong> <?php echo htmlspecialchars($option_voyage['return_date']); ?></p>
                    <p><strong>Nombre total de personnes :</strong> <?php echo htmlspecialchars($option_voyage['nb_personnes_voyage']); ?></p>
                    <p><strong>Prix total payé :</strong> <?php echo number_format($option_voyage['prix_total'], 2, ',', ' '); ?> €</p>
                    <p><strong>Statut de la commande :</strong> <?php echo htmlspecialchars($commande['status']); ?></p>
                    <p><strong>Date de la commande :</strong> <?php echo date('d/m/Y H:i', strtotime($commande['date'])); ?></p>
                </div>
                
                <?php for ($i = 0; $i < $total_etapes; $i++): 
                    $current_step = $etapes[$i];
                    $clean_step = strtolower(str_replace(' ', '_', $current_step));
                    
                    $hebergement_key = 'hebergement_' . $clean_step;
                    $activites_key = 'activites_' . $clean_step;
                    $transport_key = 'transport_' . $clean_step;
                
                    $step_destination_data = $etapes_data[$destination][$current_step] ?? [];
                ?>
                    <div class="voyage-details">
                        <h2><?php echo htmlspecialchars($current_step); ?></h2>
                        
                        <p><strong>Hébergement :</strong>
                            <?php
                                if (isset($option_voyage[$hebergement_key])) {
                                    $hebergements = $step_destination_data['hebergements'] ?? [];
                                    echo htmlspecialchars(
                                        $hebergements[$option_voyage[$hebergement_key]] ?? 
                                        $option_voyage[$hebergement_key]
                                    );
                                } else {
                                    echo "Aucun hébergement sélectionné";
                                }
                            ?>
                        </p>

                        <?php if (isset($option_voyage[$activites_key]) && !empty($option_voyage[$activites_key])): ?>
                        <p><strong>Activités :</strong></p>
                        <ul>
                            <?php
                                $activites_disponibles = $step_destination_data['activites'] ?? [];
                                
                                foreach ($option_voyage[$activites_key] as $activite) {
                                    $activite_libelle = $activites_disponibles[$activite] ?? $activite;
                                    
                                    $nb_personnes = isset($option_voyage['nb_personnes'][$activite]) 
                                    ? intval($option_voyage['nb_personnes'][$activite]) 
                                    : 0;

                                    $prix_activite = isset($option_voyage['activite_prix'][$activite]) 
                                        ? intval($option_voyage['activite_prix'][$activite])
                                        : 0;

                                    
                                    $prix_total_activite = $prix_activite * $nb_personnes;
                                    
                                    echo "<li>" . htmlspecialchars($activite_libelle . " - " . $nb_personnes . " personne" . ($nb_personnes > 1 ? "s" : "")) . " - " . number_format($prix_total_activite, 2, ',', ' ') . " € </li>";
                                 }
                            ?>
                        </ul>
                        <?php else: ?>
                            <p><strong>Activités :</strong> Aucune activité sélectionnée</p>
                        <?php endif; ?>

                        <?php if ($i < $total_etapes - 1 && isset($option_voyage[$transport_key])): ?>
                        <p><strong>Transport pour la prochaine étape :</strong> 
                            <?php
                                $transports = $step_destination_data['transports'] ?? [];
                                echo htmlspecialchars(
                                    $transports[$option_voyage[$transport_key]] ?? 
                                    $option_voyage[$transport_key]
                                );
                            ?>
                        </p>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
                
                <div class='recherche'>
                    <?php if ($commande['status'] === 'accepted'): ?>
                    <div class="form-actions">
                        <a href="PageAjoutOptions.php?id=<?php echo urlencode($transaction_id); ?>" class="Page-Accueil-button">Ajouter des options supplémentaires</a>
                    </div>
                    <?php endif; ?>
                    <div class="form-actions">
                        <a href="PageProfil.php" class="Page-Accueil-button">Retour à mon profil</a>
                    </div>
                </div>
            </div>
        </div>

        <?php 
$scripts = '';
require_once('footer.php'); 
?>