<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: PageSeconnecter.php");
    exit();
}

// Charger les fichiers JSON
$commandes_file = 'Commande.json';

// Récupérer l'ID de la transaction depuis l'URL
$transaction_id = $_GET['id'] ?? null;

if (!$transaction_id) {
    die("Transaction manquante.");
}

// Charger les commandes
$commandes = [];
if (file_exists($commandes_file)) {
    $commandes = json_decode(file_get_contents($commandes_file), true);
}

// Trouver la commande correspondant à la transaction
$commande = null;
$option_voyage = null;

foreach ($commandes as $cmd) {
    if ($cmd['transaction_id'] === $transaction_id) {
        $commande = $cmd;
        // Trouver l'option correspondant à l'utilisateur connecté
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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS.css">
    <title>Détails du Voyage</title>
</head>
<body>
    <section class="Page-Accueil">
        <header>
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

        <div class="voyage-detail-container">
            <h1>Détails de votre voyage</h1>
            
            <div class="detail-section">
                <div class="detail-label">Destination:</div>
                <div class="detail-value"><?php echo htmlspecialchars($option_voyage['destination']); ?></div>
            </div>
            
            <div class="detail-section">
                <div class="detail-label">Dates du voyage:</div>
                <div class="detail-value">
                    Du <?php echo date('d/m/Y', strtotime($option_voyage['departure_date'])); ?> 
                    au <?php echo date('d/m/Y', strtotime($option_voyage['return_date'])); ?>
                </div>
            </div>
            
            <div class="detail-section">
                <div class="detail-label">Nombre de voyageurs:</div>
                <div class="detail-value"><?php echo htmlspecialchars($option_voyage['nb_personnes_voyage']); ?> personne(s)</div>
            </div>
            
            <div class="detail-section">
                <div class="detail-label">Prix total payé:</div>
                <div class="detail-value"><?php echo number_format($option_voyage['prix_total'], 2, ',', ' '); ?> €</div>
            </div>
            
            <div class="detail-section">
                <div class="detail-label">Étapes du voyage:</div>
                <div class="etape-list">
                    <?php foreach ($option_voyage['etapes'] as $etape): ?>
                        <div class="etape-item"><?php echo htmlspecialchars($etape); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <?php if (!empty($hebergements)): ?>
            <div class="detail-section">
                <div class="detail-label">Hébergements:</div>
                <div class="detail-value">
                    <?php foreach ($hebergements as $hebergement): ?>
                        <div><?php echo htmlspecialchars($hebergement); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($activites)): ?>
            <div class="detail-section">
                <div class="detail-label">Activités réservées:</div>
                <div class="activity-list">
                    <?php foreach ($activites as $activite): ?>
                        <div class="activity-item"><?php echo htmlspecialchars($activite); ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (isset($option_voyage['transport_poudlard']) || isset($option_voyage['transport_tatooine'])): ?>
            <div class="detail-section">
                <div class="detail-label">Transport:</div>
                <div class="detail-value">
                    <?php if (isset($option_voyage['transport_poudlard'])): ?>
                        <div>Vers Poudlard: <?php echo htmlspecialchars($option_voyage['transport_poudlard']); ?></div>
                    <?php endif; ?>
                    <?php if (isset($option_voyage['transport_tatooine'])): ?>
                        <div>Vers Tatooine: <?php echo htmlspecialchars($option_voyage['transport_tatooine']); ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <div class="detail-section">
                <div class="detail-label">Statut de la commande:</div>
                <div class="detail-value"><?php echo htmlspecialchars($commande['status']); ?></div>
            </div>
            
            <div class="detail-section">
                <div class="detail-label">Date de la commande:</div>
                <div class="detail-value"><?php echo date('d/m/Y H:i', strtotime($commande['date'])); ?></div>
            </div>
            
            <a href="PageProfil.php" class="back-button">Retour à mon profil</a>
        </div>
    </section>
</body>
</html>