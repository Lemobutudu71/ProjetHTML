<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../PageInscription.php");  
    exit();
}
?>

<?php require_once('../header.php'); ?>   
        <div class="Page-Accueil-text">
            <h2 class="Titre">Fondcombe</h2>
            <p>
                Plongez dans l'univers féerique du Seigneur des Anneaux et visitez Fondcombe,
                 le havre de paix des Elfes dirigé par Elrond. 
                 Un lieu de nature et de sagesse où l'on ressent toute la puissance du monde fantastique de Tolkien.
            </p>
        
       
        </div>
        
    
        
    
        <?php 
$scripts = '

';
require_once('../footer.php'); 
?>
