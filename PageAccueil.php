<?php
session_start();
require_once 'load_env.php';

?>

<?php require_once('header.php'); ?>
    
        <div class="Page-Accueil-text">
            <h1>Qui sommes nous</h1>
            <p >
                <strong>Movietrip - Voyagez au cœur de vos films préférés ! </strong><br>
                Envie de traverser l'écran et de vivre une aventure inoubliable ? 
                Avec Movietrip, explorez des destinations mythiques au coeur de vos films préférés ! 
            </p>
            <ul>
                <li>🎩 Visitez Poudlard et apprenez à manier la magie </li>
                <li>🦖 Partez en expédition dans un parc rempli de dinosaures </li>
                <li>🚀 Voyagez vers une galaxie lointaine, très lointaine... </li>
            </ul>
            <p >
                Marchez dans les pas de vos héros, découvrez des lieux iconiques et plongez dans des expériences uniques.
                Que vous soyez fan de fantasy, de science-fiction ou d'aventure,
                Movietrip réalise vos rêves de cinéma. 
                Préparez-vous à une immersion totale et réservez votre voyage dès maintenant !</p>
            </p>
            <a class="Page-Accueil-button" href="PageAccueil2.php" >Réserver un séjour</a>
        </div>
<?php 
$scripts = '

';
require_once('footer.php'); 
?>