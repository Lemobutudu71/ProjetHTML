<?php require_once __DIR__ . '/load_env.php'; ?>
<footer>
            <ul class="bas-de-page">
                <li><a href="#">Mentions légales</a></li>
                <li><a href="#">Politique de confidentialité</a></li>
                <li><a href="#">&Agrave; propos</a></li>
                <li><a href="<?php echo $_ENV['PATH']; ?>/pageAdministrateur.php">Administrateur</a></li>
            </ul>
        </footer>
    </section>
    <?php
    if (isset($scripts)) {
        echo $scripts;
    }
    ?>
    
</body>
</html>