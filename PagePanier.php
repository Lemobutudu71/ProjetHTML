<?php

session_start();
if (!isset($_SESSION['user'])) {
    header("Location: PageInscription.php");
    exit();
}

$user = $_SESSION['user'];
$optionsFile = 'json/options.json';
$mesVoyages = [];

if (file_exists($optionsFile)) {
    $orders = json_decode(file_get_contents($optionsFile), true);
    // Filtrer les commandes non payées de l'utilisateur connecté
    foreach ($orders as $order) {
        if (isset($order['user_id']) && $order['user_id'] === $user['id'] 
            && (!isset($order['status']) || $order['status'] !== 'accepted')) {
            $mesVoyages[] = $order;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitulatif de Voyage</title>
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
        <div class="description">

            <h2 class="h2">Mes voyages</h2>
            <div class="voyage-list">
                <?php if (empty($mesVoyages)): ?>
                    <p class="no-voyages">Vous n'avez pas encore ajouté de voyage à votre panier.</p>
                <?php else: ?>
                    <?php foreach ($mesVoyages as $voyage): ?>
                        <a href="PageRecap.php?transaction_id=<?php echo urlencode($voyage['transaction_id']); ?>" class="voyage-item">
                            <div class="voyage-destination"><?php echo htmlspecialchars($voyage['destination']); ?></div>
                            <div class="voyage-dates">
                                Du <?php echo date('d/m/Y', strtotime($voyage['departure_date'])); ?> 
                                au <?php echo date('d/m/Y', strtotime($voyage['return_date'])); ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
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
    
</body>
</html>