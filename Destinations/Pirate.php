<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../PageInscription.php"); 
    exit();
}
?>


<?php require_once('../header.php'); ?> 
        
        <div class="Page-Accueil-text">
            <h2 class="Titre">Pirate des Caraïbes </h2>
    
            <p>
                Voguez sur les mers aux côtés de Jack Sparrow dans l’univers de Pirates des Caraïbes.
                 Affrontez le Kraken, explorez des îles maudites et embarquez sur le Black Pearl !
            </p>
        
       
        </div>
        
    
       
    </div>
        
    <?php 
$scripts = '
 
';
require_once('../footer.php'); 
?>