<?php
require_once('session.php');  

$user = $_SESSION['user'];
$optionsFile = 'json/options.json';
$mesVoyages = [];

if (file_exists($optionsFile)) {
    $orders = json_decode(file_get_contents($optionsFile), true);
    
    foreach ($orders as $order) {
        if (isset($order['user_id']) && $order['user_id'] === $user['id'] 
            && (!isset($order['status']) || $order['status'] !== 'accepted')) {
            $mesVoyages[] = $order;
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
                <?php endif; ?>
            </div>
        </div>
       
        <?php 
$scripts = '

';
require_once('footer.php'); 
?>