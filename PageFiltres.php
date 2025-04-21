<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: PageInscription.php");
    exit;
}

$voyagesJson = file_get_contents("json/voyages.json");
$voyages = json_decode($voyagesJson, true);

if ($voyages === null) {
    die("Erreur lors du chargement des voyages.");
}


$voyagesParPage = 4;

$totalVoyages = count($voyages);
$totalPages = ceil($totalVoyages / $voyagesParPage);
$pageActuelle = isset($_GET['page']) ? max(1, min($totalPages, (int)$_GET['page'])) : 1;
$depart = ($pageActuelle - 1) * $voyagesParPage;
$voyagesPage = array_slice($voyages, $depart, $voyagesParPage);
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
        
        <div class="Page-Accueil-text">
            <h1>Rechercher un voyage</h1>
            
            <form method="GET"> 
                <div class="filters-container">
                    <div class="filter-input">
                        <label for="transport">Moyen d'accès:</label>
                        <select id="transport" name="transport">
                            <option value="">-</option>
                            <option value="Vaisseau">Vaisseau spatial</option>
                            <option value="Bateau">Bateau</option>
                            <option value="Poudre-cheminette">Poudre de cheminette</option>
                            <option value="Cheval">Cheval</option>
                            <option value="Avion">Avion</option>
                            <option value="Voiture">Voiture</option>
                        </select>
                    </div>

                    <div class="filter-input">
                        <label for="logement">Logement:</label>
                        <select id="logement" name="logement">
                            <option value="">-</option>
                            <option value="Château">Château</option>
                            <option value="Chez-habitant">Chez l'habitant</option>
                            <option value="Camping">Camping</option>
                            <option value="Maison">Maison</option>
                            <option value="Hôtel">Hôtel</option>
                            <option value="Cabine">Cabine</option>
                        </select>
                    </div>

                    <div class="filter-input">
                        <label for="monde">Monde:</label>
                        <select id="monde" name="monde">
                            <option value="">-</option>
                            <option value="Médiéval">Médiéval</option>
                            <option value="Magique">Magique</option>
                            <option value="Préhistorique">Préhistorique</option>
                            <option value="Futuriste">Futuriste</option>
                            <option value="Éxotique">Éxotique</option>
                            <option value="Surnaturel">Surnaturel</option>
                        </select>
                    </div>
                </div>

                
            </form>
            <div class="filter-input">
                    <label for="prix-filtre">Trier par prix :</label>
                    <select id="prix-filtre">
                        <option value="">-- Sélectionnez --</option>
                        <option value="asc">Croissant</option>
                        <option value="desc">Décroissant</option>
                    </select>
                </div>
            <div class="ListePhotos">
                
            </div>

            <div class="pagination">
                
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
    <script>
        const allVoyages = <?php echo json_encode($voyages); ?>;
    </script>

    <script src="Javascript/Filtre.js"></script>
</body>
</html>
