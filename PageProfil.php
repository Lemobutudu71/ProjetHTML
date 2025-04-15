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


// Récupérer les commandes depuis le fichier JSON
$commandesJson = file_get_contents('json/Commande.json');
$commandes = json_decode($commandesJson, true);

// Filtrer les commandes de l'utilisateur connecté
$mesVoyages = [];
foreach ($commandes as $commande) {
    if ($commande['status'] === 'accepted') {
        foreach ($commande['options'] as $option) {
            if ($option['user_id'] === $user['id']) {
                $voyage = [
                    'transaction_id' => $commande['transaction_id'],
                    'date' => $commande['date'],
                    'destination' => $option['destination'],
                    'departure_date' => $option['departure_date'],
                    'return_date' => $option['return_date'],
                    'prix_total' => $option['prix_total']
                ];
                $mesVoyages[] = $voyage;
            }
        }
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

                <form action="PageProfil.php" method="post">
                    <button type="submit" name="deconnecter" class="button">Se déconnecter</button>
                </form>           
            </div>
        
                <div class="Compte">
                    <h2 class="h2">Mes voyages</h2>
                    <div class="voyage-list">
                        <?php if (empty($mesVoyages)): ?>
                            <p class="no-voyages">Vous n'avez pas encore de voyages réservés.</p>
                        <?php else: ?>
                            <?php foreach ($mesVoyages as $voyage): ?>
                                <a href="PageMesvoyages.php?id=<?php echo urlencode($voyage['transaction_id']); ?>" class="voyage-item">
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
