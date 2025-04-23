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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link id="theme" rel="stylesheet" href="CSS.css">
    <link 
    rel="stylesheet" 
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    >
    <script src="/test/Projet/Javascript/Theme.js" defer></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const savedTheme = getCookie("theme");
            if (savedTheme === "light") {
                applyTheme("light");
            } else {
                applyTheme("default");
            }
            const toggle = document.getElementById("theme-toggle");
            if (toggle) {
                toggle.checked = (savedTheme === "light");
            }
        });
    </script>
    <title>Résultat du Paiement</title>
</head>
<body>
    <section class="Page-Accueil">
        <header>
            <video autoplay loop muted id="bg-video">
                <source src="images/Vidéo5.mp4" type="video/mp4">
            </video>
            <div class="ProfilPicture">
                <img src="images/LOGO.jpg" alt="logo" width="200" class="logo">
            </div>
            <ul class="menu">
                <li><a href="PageAccueil.php">Accueil</a></li>
                <li><a href="PageAccueil2.php">Rechercher</a></li>
                <li><a href="PagePanier.php">Mon panier</a></li>
                <li><a href="PageProfil.php">Profil</a></li>
                <div class="toggle-container">                        
                    <i class="fas fa-moon"></i>
                    <label class="switch">
                    <input type="checkbox" id="theme-toggle">
                    <span class="slider"></span>
                    </label>
                    <i class="fas fa-sun"></i>
                        
                </div>
            </ul>
        </header>

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

        <footer>
            <ul class="bas-de-page">
                <li><a href="#">Mentions légales</a></li>
                <li><a href="#">Politique de confidentialité</a></li>
                <li><a href="#">À propos</a></li>
                <li><a href="pageAdministrateur.php">Administrateur</a></li>
            </ul>
        </footer>
    </section>
</body>
</html>