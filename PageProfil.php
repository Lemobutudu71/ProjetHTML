<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: PageInscription.php"); 
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deconnecter'])) {
    session_unset();// Détruire toutes les variables de session
    session_destroy();// Détruire la session
    header("Location: PageAccueil.php"); 
    exit;
}

// Si l'utilisateur est connecté, récupérer ses informations de session
$user = $_SESSION['user']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieTrip</title>
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
                <li><a href="PageInscription.php">Se connecter</a></li>
                <li><a href="PageProfil.php">Profil</a></li>
            </ul>
        </header>
        <div class="container">
            <div class="Compte">
                <h2 class="h2">Mon Profil</h2>
                <form action="PageProfil.php" method="post">
                    <div class="profil">
                        <label for="nom">Nom :</label>
                        <input type="text" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" disabled>
                    </div>
                    <div class="profil">
                        <label for="prenom">Prénom :</label>
                        <input type="text" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>" disabled>
                    </div>
                    <div class="profil">
                        <label for="email">Email :</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                    </div>
                    <div class="profil">
                        <label for="role">Role :</label>
                        <input type="text" name="role" value="<?php echo htmlspecialchars($user['role']); ?>" disabled>
                    </div>
                    <div class="profil">
                        <label for="password">Mot de passe :</label>
                        <input type="password" name="password" value="********" disabled>
                    </div>
                    <button type="submit" class="button">Modifier</button>
                </form>

                <!-- Formulaire de déconnexion -->
                <form action="PageProfil.php" method="post">
                    <button type="submit" name="deconnecter" class="button">Se déconnecter</button>
                </form>
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
</body>
</html>
