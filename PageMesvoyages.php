<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: PageSeconnecter.php");
    exit();
}

// Charger les fichiers JSON
$commandes_file = 'Commande.json';

// Récupérer l'ID de la transaction depuis l'URL
$transaction_id = $_GET['transaction'] ?? null;

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
foreach ($commandes as $cmd) {
    if ($cmd['transaction_id'] === $transaction_id) {
        $commande = $cmd;
        break;
    }
}

if (!$commande) {
    die("Commande introuvable.");
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
            <h1>Détails du Voyage</h1>
            <p><strong>Destination :</strong> <?php echo htmlspecialchars($commande['voyage']); ?></p>
            <p><strong>Date de départ :</strong> <?php echo htmlspecialchars($commande['date']); ?></p>
            <p><strong>Nombre de personnes :</strong> <?php echo htmlspecialchars($commande['nb_personne']); ?></p>
            <p><strong>Montant payé :</strong> <?php echo number_format($commande['montant'], 2, ',', ' '); ?> €</p>
            <p><strong>Statut :</strong> <?php echo htmlspecialchars($commande['status']); ?></p>
            <p><strong>Étapes :</strong> <?php echo implode(", ", $commande['etapes']); ?></p>
            <p><strong>Options :</strong> <?php echo htmlspecialchars(json_encode($commande['options'])); ?></p>
        </div>
    </section>
</body>
</html>