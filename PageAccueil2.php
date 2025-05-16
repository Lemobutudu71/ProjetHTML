
<?php
session_start();
require_once 'load_env.php';

$voyagesJson = file_get_contents("json/voyages.json");
$voyages = json_decode($voyagesJson, true);

if ($voyages === null) {
    die("Erreur lors du chargement des voyages.");
}

$motCle = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';

$voyagesFiltres = array_filter($voyages, function ($voyage) use ($motCle) {
    if (empty($motCle)) {
        return true; 
    }

    
    if (isset($voyage['mot-clés']) && is_array($voyage['mot-clés'])) {
        foreach ($voyage['mot-clés'] as $mot) {
            if (stripos($mot, $motCle) !== false) {
                return true; // Mot-clé trouvé, inclure ce voyage
            }
        }
    }

    return false; 
});

$voyagesTendances = file_get_contents("json/voyagestendances.json");
$voyagesT = json_decode($voyagesTendances, true);

?>

<?php require_once('header.php'); ?>

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

    <?php 
$scripts = '
';
require_once('footer.php'); 
?>