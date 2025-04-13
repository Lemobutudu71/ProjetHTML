<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: PageSeconnecter.php");
    exit();
}

// Charger les fichiers JSON
$commandes_file = 'json/Commande.json';
$etapes_file = 'json/Etapes_Options.json';

// Récupérer l'ID de la transaction depuis l'URL
$transaction_id = $_GET['id'] ?? null;

if (!$transaction_id) {
    die("Transaction manquante.");
}

// Charger les commandes et les étapes disponibles
$commandes = [];
$etapes_data = [];

if (file_exists($commandes_file)) {
    $commandes = json_decode(file_get_contents($commandes_file), true);
}

if (file_exists($etapes_file)) {
    $etapes_data = json_decode(file_get_contents($etapes_file), true);
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

// Préparer les données pour l'affichage
$destination = $option_voyage['destination'];
$etapes = is_array($option_voyage['etapes']) ? $option_voyage['etapes'] : explode(',', $option_voyage['etapes']);
$total_etapes = $option_voyage['nb_etapes'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link id="theme"rel="stylesheet" href="CSS.css">
    <link 
    rel="stylesheet" 
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
    >
    <title>Détails du Voyage</title>
</head>
<body>
    <section class="Page-Accueil">
        <video autoplay loop muted id="bg-video">
            <source src="images/Vidéo5.mp4" type="video/mp4">
        </video>
        <header>
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
                    
                    // Déterminer les clés dynamiquement
                    $hebergement_key = 'hebergement_' . $clean_step;
                    $activites_key = 'activites_' . $clean_step;
                    $transport_key = 'transport_' . $clean_step;
                    
                    // Récupérer les données de destination dynamiquement
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

                                    // Convertir le prix de l'activité en entier (si nécessaire)
                                    $prix_activite = isset($option_voyage['activite_prix'][$activite]) 
                                        ? intval($option_voyage['activite_prix'][$activite])
                                        : 0;

                                    // Calculer le prix total de l'activité
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
                    <a href="PageProfil.php" class="Page-Accueil-button">Retour à mon profil</a>
                </div>
            </div>
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
    <script src="Javascript/Theme.js"></script>
</body>
</html>