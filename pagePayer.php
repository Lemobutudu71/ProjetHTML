<?php
session_start();
require('getapikey.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: PageSeconnecter.php");
    exit();
}

// Récupérer les informations de l'utilisateur et du voyage
$user_id = $_SESSION['user']['id'];
$user_choices = null;
$options_file = 'json/options.json';
$commandes_file = 'json/Commande.json';

// Charger les données de l'utilisateur
if (file_exists($options_file)) {
    $user_data = json_decode(file_get_contents($options_file), true);
    foreach ($user_data as $data) {
        if (isset($data['user_id']) && $data['user_id'] == $user_id) {
            $user_choices = $data;
            $destination = $user_choices['destination'];
            
        }
    }
}

// Calculer le montant total
$montant = $user_choices['prix_total'];
$transaction = uniqid();
$_SESSION['transaction'] = $transaction;
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
$retour = "{$base_url}/retour_paiement.php?transaction={$transaction}";
$_SESSION['retour'] = $retour;
// Générer le contrôle pour la sécurité
$api_key = getAPIKey($vendeur); 
$control = md5($api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $retour . "#");
$_SESSION['control'] = $control;

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS.css">
    <title>Page de Paiement</title>
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
            </ul>
        </header>

        
        <div class="description">
            <h2 class='h2'>Récapitulatif de la commande</h2>
            <p><strong>Destination :</strong> <?php echo htmlspecialchars($destination); ?></p>
            <p><strong>Date de départ :</strong> <?php echo htmlspecialchars($user_choices['departure_date']); ?></p>
            <p><strong>Date de retour :</strong> <?php echo htmlspecialchars($user_choices['return_date']); ?></p>
            <p><strong>Nombre de personnes :</strong> <?php echo htmlspecialchars($user_choices['nb_personnes_voyage']); ?></p>
            <p><strong>Montant total :</strong> <?php echo number_format($montant, 2, ',', ' '); ?> €</p>
        </div>

        <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST" class="carte">
            <input type="hidden" name="transaction" value="<?php echo $transaction; ?>">
            <input type="hidden" name="montant" value="<?php echo $montant; ?>">
            <input type="hidden" name="vendeur" value="<?php echo $vendeur; ?>">
            <input type="hidden" name="retour" value="<?php echo $retour; ?>">
            <input type="hidden" name="control" value="<?php echo $control; ?>">
            <div class='recherche'>
                <button type="submit" class="Page-Accueil-button">Valider et Payer</button>
            </div>
        </form>

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