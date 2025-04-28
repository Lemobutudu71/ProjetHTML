<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../PageInscription.php"); 
    exit();
}
?>


<?php require_once('../header.php'); ?>     
       
        <div class="Page-Accueil-text">
            <h2 class="Titre">Narnia</h2>
            <p>
                Traversez l’armoire magique et plongez dans Le Monde de Narnia, 
                un royaume peuplé de créatures fantastiques et gouverné par Aslan.
                 Combattez la Sorcière Blanche aux côtés de Peter, Susan, Edmund et Lucy !
            </p>
        
       
        </div>
    
        
    
       
   
        <?php 
$scripts = '

';
require_once('../footer.php'); 
?>
