<?php
    session_start();

    if (isset($_SESSION['user'])) {
        header("Location: PageProfil.php");
        exit();
    }

    // Fichier JSON pour stocker les utilisateurs
    $file = 'json/utilisateur.json';

        
    if ($_SERVER["REQUEST_METHOD"] == "POST") {// Vérifier si le formulaire a été soumis
        
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $email = trim($_POST['email']);
        $role = trim($_POST['role']);
        $password = trim($_POST['password']); // Hachage du mot de passe chercher hashage avec bcrypt mdp = Antoine
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); 

        
        if (file_exists($file)) { // Vérifie si l'email existe déjà
            $users = json_decode(file_get_contents($file), true);
            foreach ($users as $user) {
                if ($user['email'] === $email) {
                    $error_message = "Cet email est déjà utilisé. Veuillez en choisir un autre.";
                    break;

                }
            }
        } 
        else {
            $users = [];
        }
        if (empty($error_message)){
            $user_id = uniqid('', true); // Génère un ID unique basé sur le temps actuel
             // Création du nouvel utilisateur
            $newUser = [
                "id" => $user_id,
                "nom" => $nom,
                "prenom" => $prenom,
                "email" => $email,
                "role" => $role,
                "password" =>  $hashedPassword,
            ];

        $users[] = $newUser;// Ajouter le nouvel utilisateur
        file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));  // Enregistrement dans le fichier JSON
        header("Location: PageSeconnecter.php"); // Rediriger vers la page de connexion après l'inscription
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
                <h2 class="h2">Créer un compte</h2>
                <?php if (isset($error_message) && $error_message != ""): ?>
                    <p style="color: red;"><?php echo $error_message; ?></p>
                <?php endif; ?>
                <form action="PageInscription.php" method="post">
                    <input class="champs" name="nom" type="text" placeholder="Nom" required>
                    <input class="champs" name="prenom" type="text" placeholder="Prénom" required>
                    <input class="champs" name="email" type="email" placeholder="Email" required>
                    <select class="champs" name="role" required>
                        <option value="user">Utilisateur</option>
                        <option value="admin">Administrateur</option>
                    </select>
                    <input class="champs" name="password" type="password" placeholder="Mot de passe" required>
                    <button class="button" type="submit">S'inscrire</button>
                </form>
                <p class="redirection"><a href="PageSeconnecter.php">Déjà un compte ?</a></p>
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
    <script src="Javascript/Theme.js"></script>
    
</body>
</html>