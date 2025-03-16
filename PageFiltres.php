<?php

session_start();

// Vérifie si l'utilisateur est déjà connecté, sinon redirige vers la page d'inscription
if (!isset($_SESSION['user'])) {
    header("Location: PageInscription.php");
    exit;
}


$voyagesJson = file_get_contents("voyages.json");
$voyages = json_decode($voyagesJson, true);

// Vérifier si la lecture du JSON a réussi
if ($voyages === null) {
    die("Erreur lors du chargement des voyages.");
}

// Définir le nombre de voyages par page
$voyagesParPage = 4;

// Récupérer le numéro de la page actuelle (par défaut 1)
$pageActuelle = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculer l'index de départ
$depart = ($pageActuelle - 1) * $voyagesParPage;

// Extraire les voyages pour la page actuelle
$voyagesPage = array_slice($voyages, $depart, $voyagesParPage);

// Nombre total de pages
$totalPages = ceil(count($voyages) / $voyagesParPage);

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
        <div class="Page-Accueil-text">
            <h1>Rechercher un voyage</h1>
            <div class="date-container">
                <div class="date-input">
                    <label for="date-depart">Date de départ :</label>
                    <input  type="date" id="date-depart" name="date-depart" required>
                </div>

                <div class="date-input">
                    <label for="date-retour">Date de retour :</label>
                    <input type="date" id="date-retour" name="date-retour" required>
                </div>
            </div>
           
            
            <div class="filters-container">
                <div class="filter-input">
                    <label for="transport">Moyen d'accès:</label>
                    <select id="transport" name="transport" >
                        <option value="Vaisseau">Vaisseau spatial</option>
                        <option value="Bateau">Bateau</option>
                        <option value="Poudre-cheminette">Poudre de cheminette</option>
                        <option value="Cheval">Cheval</option>
                        <option value="Avion">Avion</option>
                        <option value="Voiture">Voiture</option>
                    </select>
                </div>
            
                <div class="filter-input">
                    <label for="Logement">Logement:</label>
                    <select id="Logement" name="Logement">
                        <option value="chateau">Château</option>
                        <option value="Chez-habitant">Chez l'habitant</option>
                        <option value="camping">Camping</option>
                        <option value="Maison">Maison</option>
                        <option value="Hotel">Hôtel 5 étoiles</option>
                        
                    </select>
                </div>
            
                <div class="filter-input">
                    <label for="Logement">Monde:</label>
                    <select id="Logement" name="Logement">
                        <option value="medieval">Médiéval</option>
                        <option value="magique">Magique</option>
                        <option value="prehistorique">Préhistorique</option>
                        <option value="Futuriste">Futuriste</option>
                        <option value="exotique">&Eacute;xotique</option>
                        <option value="Surnaturel">Surnaturel</option>
                    </select>
                </div>
            </div>


            <div class="ListePhotos">
                <?php foreach ($voyagesPage as $voyage): ?>
                    <div class="gallerie-img">
                        <a href="<?= htmlspecialchars($voyage['lien']) ?>">
                            <img src="<?= htmlspecialchars($voyage['image']) ?>" alt="<?= htmlspecialchars($voyage['nom']) ?>">
                            <div class="Lieux"><p><?= htmlspecialchars($voyage['nom']) ?></p></div>
                            <div class="Prix"><p>À partir de <?= number_format($voyage['prix'], 2, ',', ' ') ?>€</p></div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="pagination">
                <?php if ($pageActuelle > 1): ?>
                    <a href="?page=<?= $pageActuelle - 1 ?>">Précédent</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>" <?= ($i == $pageActuelle) ? 'class="active"' : '' ?>><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($pageActuelle < $totalPages): ?>
                    <a href="?page=<?= $pageActuelle + 1 ?>">Suivant</a>
                <?php endif; ?>
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
