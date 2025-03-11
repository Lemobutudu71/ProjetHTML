<?php
session_start();


if (isset($_SESSION['user'])) { // Si  déjà connecté, on redirige vers la page Accueil
    header("Location: PageAcceuil.php");
    exit;
}

$file = 'utilisateur.json';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (file_exists($file)) {
        $users = json_decode(file_get_contents($file), true);

        // Parcourir la liste des utilisateurs pour trouver une correspondance
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                if (password_verify($password, $user['password'])) {
                    // Enregistrer l'utilisateur en session
                    $_SESSION['user'] = [
                        "nom" => $user['nom'],
                        "prenom" => $user['prenom'],
                        "email" => $user['email']
                    ];

                    // Rediriger vers la page du profil après connexion réussie
                    header("Location: PageProfil.php");
                    exit;
                } else {
                    $error_message = "Le mot de passe est incorrect.";
                }
            }
        }
    }
    // Si aucune correspondance, afficher un message d'erreur
    if (!isset($error_message)) {
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
                <h2 class="h2">Connexion</h2>
                <?php if (isset($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>
                <form action="PageSeconnecter.php" method="post">
                    <input class="champs" name="email" type="email" placeholder="Email" required>
                    <input class="champs" name="password" type="password" placeholder="Mot de passe" required>
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
</body>
</html>