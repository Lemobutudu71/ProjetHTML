<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../PageInscription.php"); 
    exit();
}
?>
<?php require_once('../header.php'); ?>      
        <div class="Page-Accueil-text">
            <h2 class="Titre">Croisière sur le Titanic</h2>
            <p>
                Revivez l’histoire tragique du Titanic, inspirée du film culte de James Cameron.
                 Montez à bord du paquebot légendaire et dansez dans la grande salle de bal… avant l’iceberg !
            </p>
        
       
        </div>

<?php     
$scripts = '
';
require_once('../footer.php'); 
?>
