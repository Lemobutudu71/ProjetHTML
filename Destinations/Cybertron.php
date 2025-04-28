<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../PageInscription.php");  
    exit();
}
?>
<?php require_once('../header.php'); ?>     
        
        <div class="Page-Accueil-text">
            <h2 class="Titre">Cybertron</h2>
            <p>
                Embarquez pour Cybertron, la planète des Autobots et des Decepticons dans Transformers !
                 Découvrez un monde métallique où les robots se livrent une guerre sans merci.
            </p>
        
       
        </div>
    
        
        <?php 
$scripts = '
';
require_once('../footer.php'); 
?>