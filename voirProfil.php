<?php
require_once 'load_env.php';
require_once('session.php');

// Vérification des droits d'administrateur
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: PageAccueil.php");
    exit();
}

// Récupération de l'ID de l'utilisateur
$userId = isset($_GET['id']) ? $_GET['id'] : null;

if (!$userId) {
    header("Location: pageAdministrateur.php");
    exit();
}

$file = 'json/utilisateur.json';
$users = file_exists($file) ? json_decode(file_get_contents($file), true) : [];


$user = null;
foreach ($users as $u) {
    if ($u['id'] === $userId) {
        $user = $u;
        break;
    }
}

if (!$user) {
    header("Location: pageAdministrateur.php");
    exit();
}

require_once('header.php');
?>

<div class="Page-Accueil-text">
    <h2>Profil de l'utilisateur</h2>
    
    <div class="options-group">
        <div class="options-group">
            <label>Nom:</label>
            <span><?php echo htmlspecialchars($user['nom']); ?></span>
        </div>
        
        <div class="options-group">
            <label>Prénom:</label>
            <span><?php echo htmlspecialchars($user['prenom']); ?></span>
        </div>
        
        <div class="options-group">
            <label>Email:</label>
            <span><?php echo htmlspecialchars($user['email']); ?></span>
        </div>
        
        <div class="info-group">
            <label>VIP:</label>
            <?php echo htmlspecialchars($user['Vip']); ?>
        </div>
        
        <div class="info-group">
            <label>Bloqué:</label>
            <?php echo htmlspecialchars($user['Bloquer']); ?>
        </div>
    </div>
    
    <div class="recherche">
        <a href="pageAdministrateur.php" class="Page-Accueil-button">Retour à la liste</a>
    </div>
</div>

<?php require_once('footer.php'); ?> 