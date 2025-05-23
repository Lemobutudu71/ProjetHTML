<?php 
// Inclut le script load_env.php pour charger les variables d'environnement.
// __DIR__ assure que le chemin est relatif au répertoire du fichier header.php.
require_once __DIR__ . '/load_env.php'; 
?>
<!DOCTYPE html>
<html lang="fr"> <!-- Définit la langue de la page en français -->
<head>
    <meta charset="UTF-8"> <!-- Définit l'encodage des caractères en UTF-8, qui supporte la plupart des caractères mondiaux -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configure le viewport pour un design responsive, s'adaptant à la largeur de l'appareil -->
    <title>MovieTrip</title> <!-- Titre de la page affiché dans l'onglet du navigateur -->
    
    <!-- Script JavaScript pour définir une variable globale `basePath` -->
    <!-- Cette variable contient le chemin racine de l'application, utile pour les chemins relatifs en JavaScript. -->
    <!-- rtrim évite un double slash si $_ENV['PATH'] se termine déjà par un / -->
    <script>
        const basePath = "<?php echo rtrim($_ENV['PATH'], '/'); ?>"; 
    </script>
    
    <!-- Lien vers la feuille de style principale (CSS) -->
    <!-- L'ID "theme" est utilisé par JavaScript (Theme.js) pour changer dynamiquement la feuille de style (thème clair/sombre). -->
    <link id="theme" rel="stylesheet" href="<?php echo $_ENV['PATH']; ?>/CSS.css">
    
    <!-- Lien vers la bibliothèque Font Awesome pour les icônes -->
    <!-- Utilisé pour les icônes comme la lune et le soleil pour le sélecteur de thème, et potentiellement d'autres. -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Inclusion du script JavaScript pour la gestion des thèmes -->
    <!-- `defer` assure que le script est exécuté après que le HTML a été complètement parsé. -->
    <script src="<?php echo $_ENV['PATH']; ?>/Javascript/Theme.js" defer></script>
    
    <!-- Script JavaScript pour appliquer le thème sauvegardé au chargement de la page -->
    <script>
        // Attend que le contenu HTML de la page soit complètement chargé.
        document.addEventListener("DOMContentLoaded", function() {
            // Récupère le thème sauvegardé depuis un cookie (en utilisant la fonction getCookie de Theme.js).
            const savedTheme = getCookie("theme");
            // Si le thème sauvegardé est "light".
            if (savedTheme === "light") {
                applyTheme("light"); // Applique le thème clair (fonction de Theme.js).
            } else {
                applyTheme("default"); // Sinon, applique le thème par défaut (sombre).
            }
            // Récupère le bouton de basculement de thème.
            const toggle = document.getElementById("theme-toggle");
            // Si le bouton existe, coche ou décoche la case en fonction du thème sauvegardé.
            if (toggle) {
                toggle.checked = (savedTheme === "light");
            }
        });
    </script>
</head>
<body>
    <!-- Section principale de la page d'accueil, contenant la vidéo de fond et l'en-tête -->
    <section class="Page-Accueil">
        <!-- Vidéo de fond en lecture automatique, en boucle et sans son -->
        <video autoplay loop muted id="bg-video">
            <source src="<?php echo $_ENV['PATH']; ?>/images/Vidéo5.mp4" type="video/mp4">
        </video> 
        <header>
            <!-- Section pour le logo du site -->
            <div class="ProfilPicture">
                <img src="<?php echo $_ENV['PATH']; ?>/images/LOGO.jpg" alt="logo" width="200" class="logo">
            </div>
            <!-- Navigation principale du site -->
            <nav>
                <ul class="menu">
                    <li><a href="<?php echo $_ENV['PATH']; ?>/PageAccueil.php">Accueil</a></li>
                    <li><a href="<?php echo $_ENV['PATH']; ?>/PageAccueil2.php">Rechercher</a></li>
                    <?php if (isset($_SESSION['user'])): // Vérifie si l'utilisateur est connecté ?>
                        <li><a href="<?php echo $_ENV['PATH']; ?>/PagePanier.php">Mon panier</a></li>
                    <?php else: // Si l'utilisateur n'est pas connecté ?>
                        <li><a href="<?php echo $_ENV['PATH']; ?>/PageInscription.php">Se connecter</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo $_ENV['PATH']; ?>/PageProfil.php">Profil</a></li>
                    <!-- Conteneur pour le sélecteur de thème (mode sombre/clair) -->
                    <div class="toggle-container">
                        <i class="fas fa-moon"></i> <!-- Icône de lune -->
                        <label class="switch">
                            <input type="checkbox" id="theme-toggle"> <!-- Case à cocher pour basculer le thème -->
                            <span class="slider"></span> <!-- Curseur visuel du switch -->
                        </label>
                        <i class="fas fa-sun"></i> <!-- Icône de soleil -->
                    </div>
                </ul>
            </nav>
        </header>