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
                <h2 class="h2">Créer un compte</h2>
                <form action="" method="post">
                    <input class="champs" name="nom" type="text" placeholder="Nom" required>
                    <input class="champs" name="prenom" type="text" placeholder="Prénom" required>
                    <input class="champs" name="email" type="email" placeholder="Email" required>
                    <input class="champs" name="password "type="password" placeholder="Mot de passe" required>
                    <button class="button" type="submit">S'inscrire</button>
                </form>
                <p class="redirection"><a href="PageSeconnecter.php">Déjà un compte ?</a></p>
            </div>
        </div>

        <?php
        // Fichier JSON pour stocker les utilisateurs
        $file = 'utilisateur.json';

        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {// Vérifier si le formulaire a été soumis
        
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hachage du mot de passe chercher hashage avec bcrypt

        
        if (file_exists($file)) { // Vérifie si l'email existe déjà
            $users = json_decode(file_get_contents($file), true);
            foreach ($users as $user) {
                if ($user['email'] === $email) {
                    echo "Cet email est déjà utilisé. Veuillez en choisir un autre.";
                    exit;
                }
            }
        } 
        else {
            $users = [];
        }

        // Création du nouvel utilisateur
        $newUser = [
            "nom" => $nom,
            "prenom" => $prenom,
            "email" => $email,
            "password" => $password 
        ];

        $users[] = $newUser;// Ajouter le nouvel utilisateur
        file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));  // Enregistrement dans le fichier JSON

        echo "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        } 
        else {
            echo "Erreur lors de l'inscription.";
        }
        ?>
        
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