<?php 
// Inclut le script load_env.php pour charger les variables d'environnement (notamment $_ENV['PATH']).
// __DIR__ assure que le chemin est relatif au répertoire du fichier footer.php.
require_once __DIR__ . '/load_env.php'; 
?>
<footer>
            <!-- Liste non ordonnée pour les liens du pied de page -->
            <ul class="bas-de-page">
                <li><a href="#">Mentions légales</a></li> <!-- Lien vers les mentions légales (actuellement un placeholder #) -->
                <li><a href="#">Politique de confidentialité</a></li> <!-- Lien vers la politique de confidentialité (placeholder #) -->
                <li><a href="#">&Agrave; propos</a></li> <!-- Lien vers la page À propos (placeholder #) -->
                <!-- Lien vers la page administrateur, construit dynamiquement avec la variable d'environnement PATH -->
                <li><a href="<?php echo $_ENV['PATH']; ?>/pageAdministrateur.php">Administrateur</a></li>
            </ul>
        </footer>
    </section> <!-- Balise de fermeture pour <section class="Page-Accueil"> ouverte dans header.php -->
    
    <?php
    // Vérifie si la variable $scripts est définie.
    // Cette variable peut être définie dans les fichiers PHP spécifiques à une page 
    // pour inclure des scripts JavaScript additionnels uniquement sur cette page.
    if (isset($scripts)) {
        // Si $scripts est définie, son contenu (qui devrait être des balises <script> complètes) est affiché ici.
        echo $scripts;
    }
    ?>
    
</body> <!-- Balise de fermeture du corps de la page HTML -->
</html> <!-- Balise de fermeture de la page HTML -->