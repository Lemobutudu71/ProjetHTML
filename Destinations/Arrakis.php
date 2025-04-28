<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../PageInscription.php"); 
    exit();
}
?>

<?php require_once('../header.php'); ?>     

        <div class="Page-Accueil-text">
            <h2 class="Titre">Arrakis</h2>
            <p>
                Voyagez sur la planète désertique d’Arrakis, 
                connue sous le nom de Dune. Une terre où l’épice est la ressource
                 la plus précieuse et où les vers de sable géants règnent.
            </p>
        
       
        </div>
        
    
        <?php 
$scripts = '
   
';
require_once('../footer.php'); 
?>     
   