<?php require_once __DIR__ . '/load_env.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieTrip</title>
    <script>
        const basePath = "<?php echo rtrim($_ENV['PATH'], '/'); ?>";
    </script>
    <link id="theme" rel="stylesheet" href="<?php echo $_ENV['PATH']; ?>/CSS.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="<?php echo $_ENV['PATH']; ?>/Javascript/Theme.js" defer></script>
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
            <source src="<?php echo $_ENV['PATH']; ?>/images/Vidéo5.mp4" type="video/mp4">
        </video> 
        <header>
            <div class="ProfilPicture">
                <img src="<?php echo $_ENV['PATH']; ?>/images/LOGO.jpg" alt="logo" width="200" class="logo">
            </div>
            <nav>
                <ul class="menu">
                    <li><a href="<?php echo $_ENV['PATH']; ?>/PageAccueil.php">Accueil</a></li>
                    <li><a href="<?php echo $_ENV['PATH']; ?>/PageAccueil2.php">Rechercher</a></li>
                    <?php if (isset($_SESSION['user'])): ?>
                        <li><a href="<?php echo $_ENV['PATH']; ?>/PagePanier.php">Mon panier</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo $_ENV['PATH']; ?>/PageInscription.php">Se connecter</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo $_ENV['PATH']; ?>/PageProfil.php">Profil</a></li>
                    <div class="toggle-container">
                        <i class="fas fa-moon"></i>
                        <label class="switch">
                            <input type="checkbox" id="theme-toggle">
                            <span class="slider"></span>
                        </label>
                        <i class="fas fa-sun"></i>
                    </div>
                </ul>
            </nav>
        </header>