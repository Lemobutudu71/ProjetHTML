<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../PageInscription.php");  
    exit();
}
?>

<?php require_once('../header.php'); ?>   
        
        <div class="Page-Accueil-text">
            <h2 class="Titre">Far far away</h2>
            <p>
                Entrez dans l’univers déjanté de Shrek et visitez Far Far Away,
                un royaume où les contes de fées ne se déroulent jamais comme prévu.
                Croiserez-vous l’Âne ou Fiona ?
            </p>
        
       
        </div>
        
    
        <?php 
$scripts = '
   
';
require_once('../footer.php'); 
?>     
