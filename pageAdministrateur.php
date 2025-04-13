<?php
session_start();

// Vérifier si l'utilisateur est connecté et s'il est admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: PageAccueil.php"); // Redirection si non admin
    exit();
}

// Charger les utilisateurs depuis le fichier JSON
$file = 'json/utilisateur.json';
$users = file_exists($file) ? json_decode(file_get_contents($file), true) : [];


$usersPerPage = 2; // Nombre d'utilisateurs par page
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1; // Récupérer la page actuelle
$totalUsers = count($users);
$totalPages = ceil($totalUsers / $usersPerPage);
$startIndex = ($page - 1) * $usersPerPage;
$usersToShow = array_slice($users, $startIndex, $usersPerPage);
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
          
            <h2 class="table-titre">Panneau Administrateur</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>VIP</th>
                        <th>Bloqué</th>
                        <th>Profil</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usersToShow as $index => $user): ?>
                        <tr>
                            <td><?php echo $startIndex + $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($user['nom']); ?></td>
                            <td><?php echo htmlspecialchars($user['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><button class="Oui-btn">Oui</button></td>
                            <td><button class="Non-btn">Non</button></td>
                            <td><button class="profil-btn">Voir</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">Précédent</a>
            <?php endif; ?>

            <span>Page <?php echo $page; ?> sur <?php echo $totalPages; ?></span>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>">Suivant</a>
            <?php endif; ?>
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