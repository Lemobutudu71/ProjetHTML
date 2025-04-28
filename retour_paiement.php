<?php
session_start();
require('getapikey.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: PageSeconnecter.php");
    exit();
}

// Vérifier la présence des paramètres nécessaires
if (!isset($_GET['transaction']) || !isset($_GET['status']) || !isset($_GET['montant']) || !isset($_GET['vendeur']) || !isset($_GET['control'])) {
    die("Paramètres de retour de paiement incomplets.");
}

$transaction = $_GET['transaction'] ?? $_SESSION['transaction'] ?? null;

if (!$transaction) {
    die("Transaction manquante.");
}
$status = $_GET['status'];
$montant = $_GET['montant'];
$vendeur = $_GET['vendeur'];
$control_recu = $_SESSION['control'];

$user_id = $_SESSION['user']['id'];
$options_file = 'json/options.json';
$commandes_file = 'json/Commande.json';


$last_choice = null;
if (file_exists($options_file)) {
    $orders = json_decode(file_get_contents($options_file), true);
    foreach ($orders as &$order) {
        if (isset($order['transaction_id']) && $order['transaction_id'] === $transaction) {
            
            $order['status'] = $status;
            $last_choice = $order;
            $destination = $order['destination'];
            break;
        }
    }
    
    file_put_contents($options_file, json_encode($orders, JSON_PRETTY_PRINT));
}

$api_key = getAPIKey($vendeur);
$retour = $_SESSION['retour'] ?? '';

$control_calcule = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $retour . "#");

$transaction_valide = ($control_recu === $control_calcule);

$options_data = file_exists($options_file) ? json_decode(file_get_contents($options_file), true) : [];

$transaction_data = [
    'transaction_id' => $transaction,
    'status' => $status,
    'date' => date('Y-m-d H:i:s'),
    'validation_securite' => $transaction_valide ? 'Validée' : 'Échouée',
    'options' => $last_choice ? [$last_choice] : [], 
];

$commandes = [];
if (file_exists($commandes_file)) {
    $commandes = json_decode(file_get_contents($commandes_file), true);
}
$commandes[] = $transaction_data;
file_put_contents($commandes_file, json_encode($commandes, JSON_PRETTY_PRINT));

?>

<?php require_once('header.php'); ?>

        <div class="description">
            <?php if ($status === 'accepted' && $transaction_valide): ?>
                <h1 class="Titre">Paiement Réussi</h1>
                <p><strong>Destination :</strong> <?php echo htmlspecialchars($destination); ?></p>
                <p><strong>Montant payé :</strong> <?php echo htmlspecialchars($montant); ?> €</p>
                <p><strong>Numéro de transaction :</strong> <?php echo htmlspecialchars($transaction); ?></p>
                <p>Votre réservation a été confirmée avec succès !</p>
                <div class='recherche'>
                    <a href="PageAccueil.php" class="Page-Accueil-button">Retour à l'accueil</a>
                </div>
            <?php else: ?>
                <h1 class="titre">Échec du Paiement</h1>
                <p>Un problème est survenu lors du paiement.</p>
                <p><strong>Statut :</strong> <?php echo htmlspecialchars($status); ?></p>
                <?php if (!$transaction_valide): ?>
                    <p>Erreur de validation de sécurité</p>
                <?php endif; ?>
                <div class='recherche'>
                    <a href="PagePanier.php" class="Page-Accueil-button">Réessayer le paiement</a>
                </div>
            <?php endif; ?>
        </div>

        <?php 
$scripts = '
';
require_once('footer.php'); 
?>