<?php
session_start();

// Si l'utilisateur est déjà connecté, rediriger vers la page Profil
if (isset($_SESSION['user'])) {
    header("Location: PageProfil.php");
    exit;
}

$file = 'json/utilisateur.json';

// Gestion de la connexion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $error_message = '';

    if (file_exists($file)) {
        $users = json_decode(file_get_contents($file), true);

        // Parcourir la liste des utilisateurs pour trouver une correspondance
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                if (password_verify($password, $user['password'])) {
                    // Enregistrer l'utilisateur en session
                    $_SESSION['user'] = [
                        "id" => $user['id'],
                        "nom" => $user['nom'],
                        "prenom" => $user['prenom'],
                        "email" => $user['email'],
                        "role" => $user['role']
                    ];
                    // Rediriger vers la page Profil après connexion réussie
                    header("Location: PageProfil.php");
                    exit;
                } else {
                    $error_message = "Le mot de passe est incorrect.";
                }
            }
        }
    }

    // Si aucune correspondance, afficher un message d'erreur
    if (empty($error_message)) {
        $error_message = "L'email n'existe pas.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieTrip</title>
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
                <?php if (isset($_SESSION['user'])): ?>
                     <li><a href="PagePanier.php">Mon panier</a></li>
                <?php else: ?>
                        <li><a href="PageInscription.php">Se connecter</a></li>
                <?php endif; ?>
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

        <div class="container">
            <div class="Compte">
                <h2 class="h2">Connexion</h2>
                <?php if (isset($error_message) && $error_message != ''): ?>
                    <p style="color: red;"><?php echo $error_message; ?></p>
                <?php endif; ?>
                <div class="form-error" id="registerError"></div>
                <form id="loginForm" action="PageSeconnecter.php" method="post">
                    <input class="champs" name="email" type="email" placeholder="Email" required>
                    <div class="password-container">
                        <input class="champs" name="password" type="password" id="loginPassword" placeholder="Mot de passe" required>
                        <i class="fas fa-eye toggle-password" data-for="loginPassword" onclick="togglePassword('loginPassword')"></i>
                    </div>
                    <button class="button" type="submit">Se connecter</button>
                </form>
                <p class="redirection"><a href="#">Mot de passe oublié ?</a></p>
            </div>
        </div>

        <footer>
            <ul class="bas-de-page">
                <li><a href="#">Mentions légales</a></li>
                <li><a href="#">Politique de confidentialité</a></li>
                <li><a href="#">&Agrave; propos</a></li>
                <li><a href="pageAdministrateur.php">Administrateur</a></li>
            </ul>
        </footer>
    </section>
    <script src="Javascript/Icone.js"></script>
    <script src="Javascript/Connexion.js"></script>

</body>
</html>
