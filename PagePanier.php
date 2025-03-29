<?php
session_start();


// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: PageSeconnecter.php"); 
    exit();
}

// Récupérer l'ID de l'utilisateur connecté
$user_id = $_SESSION['user']['id'];

// Charger les fichiers JSON
$options_file = 'json/options.json';
$etapes_file = 'json/Etapes_Options.json';

// Initialiser les variables
$user_choices = null;
$destination_data = null;

// Charger les données de l'utilisateur
if (file_exists($options_file)) {
    $user_data = json_decode(file_get_contents($options_file), true);
    
    // Recherche des données utilisateur dans le tableau plat
    foreach ($user_data as $data) {
        if (isset($data['user_id']) && $data['user_id'] == $user_id) {
            $user_choices = $data;
            $destination = $user_choices['destination'];
        }
    }
}

// Charger les étapes disponibles en fonction de la destination
if (file_exists($etapes_file)) {
    $etapes_data = json_decode(file_get_contents($etapes_file), true);
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitulatif de Voyage</title>
    <link rel="stylesheet" href="CSS.css">
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
            </ul>
        </header>

        <div class="panier-container">
            <?php if ($user_choices): ?>
                <div class="panier-details">
                    <h1>Récapitulatif de votre voyage - Destination : <?php echo htmlspecialchars($destination); ?></h1>
                    
                    <?php 
                    // Convertir les étapes en tableau si ce n'est pas déjà le cas
                    $etapes = is_array($user_choices['etapes']) ? $user_choices['etapes'] : explode(',', $user_choices['etapes']);
                    
                    // Calculer le nombre total d'étapes
                    $total_etapes = $user_choices['nb_etapes'];
                    
                    for ($i = 0; $i < $total_etapes; $i++): 
                        $current_step = $etapes[$i];
                        $clean_step = strtolower(str_replace(' ', '_', $current_step));
                        
                        // Déterminer les clés dynamiquement
                        $hebergement_key = 'hebergement_' . $clean_step;
                        $activites_key = 'activites_' . $clean_step;
                        $transport_key = 'transport_' . $clean_step;
                        
                        // Récupérer les données de destination dynamiquement
                        $step_destination_data = $etapes_data[$destination][$current_step];
                    ?>
                        <div class="voyage-details">
                            <h2><?php echo htmlspecialchars($current_step); ?></h2>
                            
                            <p><strong>Hébergement :</strong>
                                <?php
                                    // Vérifier si l'hébergement existe
                                    if (isset($user_choices[$hebergement_key])) {
                                        // Utiliser les hébergements de la destination
                                        $hebergements = $step_destination_data['hebergements'] ?? [];
                                        
                                        echo htmlspecialchars(
                                            $hebergements[$user_choices[$hebergement_key]] ?? 
                                            $user_choices[$hebergement_key]
                                        );
                                    } else {
                                        echo "Aucun hébergement sélectionné";
                                    }
                                ?>
                            </p>

                            <p><strong>Activités :</strong></p>
                            <ul>
                                <?php
                                    // Vérifier si des activités sont sélectionnées
                                    if (!empty($user_choices[$activites_key])) {
                                        // Utiliser les activités de la destination
                                        $activites_disponibles = $step_destination_data['activites'] ?? [];
                                        
                                        foreach ($user_choices[$activites_key] as $activite) {
                                            $activite_libelle = $activites_disponibles[$activite] ?? $activite;
                                            
                                            // Afficher le nombre de personnes si disponible
                                            $nb_personnes = isset($user_choices['nb_personnes'][$activite]) 
                                                ? " (". $user_choices['nb_personnes'][$activite] ." personnes)" 
                                                : "";
                                            
                                            echo "<li>" . htmlspecialchars($activite_libelle . $nb_personnes) . "</li>";
                                        }
                                    } else {
                                        echo "<li>Aucune activité sélectionnée</li>";
                                    }
                                ?>
                            </ul>

                            <?php 
                            // N'afficher le transport que si ce n'est pas la dernière étape
                            if ($i < $total_etapes - 1): ?>
                            <p><strong>Transport pour la prochaine étape :</strong> 
                                <?php
                                    // Vérifier si le transport existe
                                    if (isset($user_choices[$transport_key])) {
                                        // Utiliser les transports de la destination
                                        $transports = $step_destination_data['transports'] ?? [];
                                        
                                        echo htmlspecialchars(
                                            $transports[$user_choices[$transport_key]] ?? 
                                            $user_choices[$transport_key]
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
                        <p><strong>Date de départ :</strong> <?php echo htmlspecialchars($user_choices['departure_date']); ?></p>
                        <p><strong>Date de retour :</strong> <?php echo htmlspecialchars($user_choices['return_date']); ?></p>
                        <p><strong>Nombre total de personnes :</strong> <?php echo htmlspecialchars($user_choices['nb_personnes_voyage']); ?></p>
                        <p><strong>Prix total :</strong> <?php echo number_format($user_choices['prix_total'], 2, ',', ' '); ?> €</p>
                    </div>
                    
                    <div class='recherche'>
                        <a href="pagePayer.php" class="Page-Accueil-button">Procéder au paiement</a>
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