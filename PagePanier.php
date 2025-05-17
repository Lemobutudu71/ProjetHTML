<?php

require_once 'load_env.php';  
require_once 'session.php'; 
$user = $_SESSION['user'];
$optionsFile = 'json/options.json';
$mesVoyages = [];
$voyages_dates = []; // Pour stocker les dates de départ déjà vues

if (file_exists($optionsFile)) {
    $orders = json_decode(file_get_contents($optionsFile), true);
    // Filtrer les commandes non payées de l'utilisateur connecté
    foreach ($orders as $order) {
        if (isset($order['user_id']) && $order['user_id'] === $user['id'] 
            && (!isset($order['status']) || $order['status'] !== 'accepted')) {
            
            // Vérifier si un voyage avec la même date de départ existe déjà
            $departure_date = $order['departure_date'] ?? '';
            if (!empty($departure_date) && !in_array($departure_date, $voyages_dates)) {
                $mesVoyages[] = $order;
                $voyages_dates[] = $departure_date;
            }
        }
    }
}

?>

<?php require_once('header.php'); ?>

        <div class="description">

            <h2 class="h2">Mes voyages</h2>
            <div class="voyage-list">
                <?php if (empty($mesVoyages)): ?>
                    <p class="no-voyages">Vous n'avez pas encore ajouté de voyage à votre panier.</p>
                <?php else: ?>
                    <?php foreach ($mesVoyages as $voyage): ?>
                        <a href="PageRecap.php?transaction_id=<?php echo urlencode($voyage['transaction_id'] ?? 'N/A'); ?>" class="voyage-item">
                            <div class="voyage-destination"><?php echo htmlspecialchars($voyage['destination'] ?? 'Destination inconnue'); ?></div>
                            <div class="voyage-dates">
                                Du <?php echo date('d/m/Y', strtotime($voyage['departure_date'] ?? 'now')); ?> 
                                au <?php echo date('d/m/Y', strtotime($voyage['return_date'] ?? 'now')); ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                    <div class='recherche'>
                        <form>
                            <input type="button" class='Page-Accueil-button' value="revenir à la page précédente" onclick="history.go(-1)">
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
       
        <?php 
$scripts = '

';
require_once('footer.php'); 
?>