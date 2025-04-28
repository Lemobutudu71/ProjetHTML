
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieTrip</title>
    <link id="theme" rel="stylesheet" href="/test/Projet/CSS.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            <source src="/test/Projet/images/Vidéo5.mp4" type="video/mp4">
        </video> 
        <header>
            <div class="ProfilPicture">
                <img src="/test/Projet/images/LOGO.jpg" alt="logo" width="200" class="logo">
            </div>
            <nav>
                <ul class="menu">
                    <li><a href="/test/Projet/PageAccueil.php">Accueil</a></li>
                    <li><a href="/test/Projet/PageAccueil2.php">Rechercher</a></li>
                    <?php if (isset($_SESSION['user'])): ?>
                        <li><a href="/test/Projet/PagePanier.php">Mon panier</a></li>
                    <?php else: ?>
                        <li><a href="/test/Projet/PageInscription.php">Se connecter</a></li>
                    <?php endif; ?>
                    <li><a href="/test/Projet/PageProfil.php">Profil</a></li>
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