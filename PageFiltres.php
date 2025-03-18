<?php
session_start();

// Vérifie si l'utilisateur est déjà connecté, sinon redirige vers la page d'inscription
if (!isset($_SESSION['user'])) {
    header("Location: PageInscription.php");
    exit;
}

// Charger les données des voyages à partir du fichier JSON
$voyagesJson = file_get_contents("voyages.json");
$voyages = json_decode($voyagesJson, true);

// Vérifier si la lecture du JSON a réussi
if ($voyages === null) {
    die("Erreur lors du chargement des voyages.");
}

// Définir le nombre de voyages par page
$voyagesParPage = 4;

// Variables pour filtrer les voyages
$filtrageActif = isset($_GET['filtrer']);
$transport = $filtrageActif && isset($_GET['transport']) ? $_GET['transport'] : ''; 
$logement = $filtrageActif && isset($_GET['logement']) ? $_GET['logement'] : ''; 
$monde = $filtrageActif && isset($_GET['monde']) ? $_GET['monde'] : ''; 

// Appliquer les filtres
$voyagesFiltres = array_filter($voyages, function ($voyage) use ($transport, $logement, $monde) {
    if ($transport === '' && $logement === '' && $monde === '') {
        return true;
    }

    return 
        ($transport === '' || in_array($transport, $voyage['transport'])) &&
        ($logement === '' || in_array($logement, $voyage['logement'])) &&
        ($monde === '' || in_array($monde, $voyage['monde']));
});

// Si aucun filtrage, afficher tous les voyages
$voyagesAffiches = $filtrageActif ? $voyagesFiltres : $voyages;

// Pagination
$totalVoyages = count($voyagesAffiches);
$totalPages = ceil($totalVoyages / $voyagesParPage);
$pageActuelle = isset($_GET['page']) ? max(1, min($totalPages, (int)$_GET['page'])) : 1;
$depart = ($pageActuelle - 1) * $voyagesParPage;
$voyagesPage = array_slice($voyagesAffiches, $depart, $voyagesParPage);

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
                <?php if (isset($_SESSION['user'])): ?>
                     <li><a href="PagePanier.php">Mon panier</a></li>
                <?php else: ?>
                        <li><a href="PageInscription.php">Se connecter</a></li>
                <?php endif; ?>
                <li><a href="PageProfil.php">Profil</a></li>
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
                            <option value="Vaisseau" <?= ($transport === "Vaisseau") ? "selected" : "" ?>>Vaisseau spatial</option>
                            <option value="Bateau" <?= ($transport === "Bateau") ? "selected" : "" ?>>Bateau</option>
                            <option value="Poudre-cheminette" <?= ($transport === "Poudre-cheminette") ? "selected" : "" ?>>Poudre de cheminette</option>
                            <option value="Cheval" <?= ($transport === "Cheval") ? "selected" : "" ?>>Cheval</option>
                            <option value="Avion" <?= ($transport === "Avion") ? "selected" : "" ?>>Avion</option>
                            <option value="Voiture" <?= ($transport === "Voiture") ? "selected" : "" ?>>Voiture</option>
                        </select>
                    </div>

                    <div class="filter-input">
                        <label for="logement">Logement:</label>
                        <select id="logement" name="logement">
                            <option value="">-</option>
                            <option value="Château" <?= ($logement === "Château") ? "selected" : "" ?>>Château</option>
                            <option value="Chez-habitant" <?= ($logement === "Chez-habitant") ? "selected" : "" ?>>Chez l'habitant</option>
                            <option value="Camping" <?= ($logement === "Camping") ? "selected" : "" ?>>Camping</option>
                            <option value="Maison" <?= ($logement === "Maison") ? "selected" : "" ?>>Maison</option>
                            <option value="Hôtel" <?= ($logement === "Hôtel") ? "selected" : "" ?>>Hôtel</option>
                            <option value="Cabine" <?= ($logement === "Cabine") ? "selected" : "" ?>>Cabine</option>
                        </select>
                    </div>

                    <div class="filter-input">
                        <label for="monde">Monde:</label>
                        <select id="monde" name="monde">
                            <option value="">-</option>
                            <option value="Médiéval" <?= ($monde === "Médiéval") ? "selected" : "" ?>>Médiéval</option>
                            <option value="Magique" <?= ($monde === "Magique") ? "selected" : "" ?>>Magique</option>
                            <option value="Préhistorique" <?= ($monde === "Préhistorique") ? "selected" : "" ?>>Préhistorique</option>
                            <option value="Futuriste" <?= ($monde === "Futuriste") ? "selected" : "" ?>>Futuriste</option>
                            <option value="Éxotique" <?= ($monde === "Éxotique") ? "selected" : "" ?>>Éxotique</option>
                            <option value="Surnaturel" <?= ($monde === "Surnaturel") ? "selected" : "" ?>>Surnaturel</option>
                        </select>
                    </div>
                </div>

                <div class="recherche">
                    <button type="submit" name="filtrer">Appliquer les filtres</button>
                </div>
            </form>

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
                    <a href="?page=<?= $pageActuelle - 1 ?>&transport=<?= urlencode($transport) ?>&logement=<?= urlencode($logement) ?>&monde=<?= urlencode($monde) ?><?= $filtrageActif ? '&filtrer=1' : '' ?>">Précédent</a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>&transport=<?= urlencode($transport) ?>&logement=<?= urlencode($logement) ?>&monde=<?= urlencode($monde) ?><?= $filtrageActif ? '&filtrer=1' : '' ?>" <?= ($i == $pageActuelle) ? 'class="active"' : '' ?>><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($pageActuelle < $totalPages): ?>
                    <a href="?page=<?= $pageActuelle + 1 ?>&transport=<?= urlencode($transport) ?>&logement=<?= urlencode($logement) ?>&monde=<?= urlencode($monde) ?><?= $filtrageActif ? '&filtrer=1' : '' ?>">Suivant</a>
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
