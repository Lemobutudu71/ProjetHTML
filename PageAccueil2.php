
<?php
session_start();


$voyagesJson = file_get_contents("voyages.json");
$voyages = json_decode($voyagesJson, true);

if ($voyages === null) {
    die("Erreur lors du chargement des voyages.");
}

// Récupérer le mot-clé de recherche (GET)
$motCle = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';

// Filtrer les voyages si un mot-clé est entré
$voyagesFiltres = array_filter($voyages, function ($voyage) use ($motCle) {
    return empty($motCle) || stripos($voyage['nom'], $motCle) !== false;
});

$voyagesTendances = file_get_contents("voyagestendances.json");
$voyagesT = json_decode($voyagesTendances, true);

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
        <div class="Page-Accueil2-text">
            <h2>Nos Fondateurs</h2>
            <div class="fondateurs">
                <div class="Cadre">
                    <img src="images/Antoine.jpeg" alt="Marmelat Antoine">
                    <p>Marmelat Antoine</p>
                </div>
                <div class="Cadre">
                    <img src="images/Lina.JPEG" alt="Pereira-Alaoui Lina">
                    <p>Pereira-Alaoui Lina</p>
                </div>
                <div class="Cadre">
                    <img src="images/Dimittry.jpg" alt="Choudhury Dimittri">
                    <p>Choudhury Dimittri</p>
                </div>
            </div>
            <p class="Page-Accueil-text">"Tout a commencé par une discussion animée autour d’un popcorn renversé :</br>
                — « Et si on pouvait VRAIMENT visiter Poudlard ? »</br>
                — « Ou partir sur Tatooine sans finir grillé façon brochette ? »</br>
                — « Et si on créait Movietrip ? »</br>
                
                C’est ainsi que nos trois fondateurs, animés par une passion démesurée pour le cinéma (et une légère obsession pour les cartes d’embarquement), ont décidé de transformer les rêves en voyages.
                
                Aujourd’hui, grâce à eux, vous pouvez marcher sur les traces de vos héros, explorer des mondes iconiques et, soyons honnêtes… prendre des photos épiques pour rendre jaloux vos amis.
                
                Alors, prêts à embarquer ? Movietrip vous attend, baguette (ou sabre laser) en main !"</p>
        
        <div class="recherche">
            <form method="GET" class="recherche">
                <input type="text" name="search" placeholder="Rechercher un voyage..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                <button type="submit">Rechercher</button>
            </form>
        </div>
        <h2 class="Titre">
                <?= empty($motCle) ? "NOS SÉJOURS TENDANCES" : "RÉSULTATS DE VOTRE RECHERCHE" ?>
            </h2>

            <div class="ListePhotos">
                <?php if (empty($motCle)): ?>
                    <!-- Affichage des voyages tendances par défaut -->
                    <?php foreach ($voyagesT as $voyage): ?>
                        <div class="gallerie-img">
                            <a href="<?= htmlspecialchars($voyage['lien']) ?>">
                                <img src="<?= htmlspecialchars($voyage['image']) ?>" alt="<?= htmlspecialchars($voyage['nom']) ?>" width="200">
                                <div class="Lieux"><p><?= htmlspecialchars($voyage['nom']) ?></p></div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Affichage des résultats de recherche -->
                    <?php if (empty($voyagesFiltres)): ?>
                        <p>Aucun voyage trouvé pour "<?= htmlspecialchars($motCle) ?>"</p>
                    <?php else: ?>
                        <?php foreach ($voyagesFiltres as $voyage): ?>
                            <div class="gallerie-img">
                                <a href="<?= htmlspecialchars($voyage['lien']) ?>">
                                    <img src="<?= htmlspecialchars($voyage['image']) ?>" alt="<?= htmlspecialchars($voyage['nom']) ?>" width="200">
                                    <div class="Lieux"><p><?= htmlspecialchars($voyage['nom']) ?></p></div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
    
        <a class="Page-Accueil-button" href="PageFiltres.php">cliquer ici pour plus de choix</a>
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
