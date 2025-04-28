<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../PageInscription.php"); 
    exit();
}
?>

<?php require_once('../header.php'); ?>    
        
        <div class="Page-Accueil-text">
            <h2 class="Titre">Hawkins</h2>
            <p>
                Plongez dans l’atmosphère mystérieuse de Stranger Things et explorez Hawkins, 
                une petite ville où des événements paranormaux et une autre dimension menacent la tranquillité des habitants.
            </p>
        
       
        </div>
        
    
        <?php 
$scripts = '
   
';
require_once('../footer.php'); 
?>
