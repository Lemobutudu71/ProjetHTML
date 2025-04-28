<?php
session_start();



if (!isset($_SESSION['user'])) {
    header("Location: PageInscription.php"); 
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deconnecter'])) {
    session_unset();// Détruire toutes les variables de session
    session_destroy();// Détruire la session
    header("Location: PageAccueil.php"); 
    exit;
}

$user = $_SESSION['user']; 


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_changes'])) {
    $nom    = isset($_POST['nom']) ? trim($_POST['nom']) : "";
    $prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : "";
    $email  = isset($_POST['email']) ? trim($_POST['email']) : "";
  
    if ($nom != "" && $prenom != "" && $email != "") {
        $file = 'json/utilisateur.json';
        if (file_exists($file)) {
            $users = json_decode(file_get_contents($file), true);
            $userId = $_SESSION['user']['id'];
            $found = false;
            foreach ($users as &$user) {
                if ($user['id'] === $userId) {
                    $user['nom'] = $nom;
                    $user['prenom'] = $prenom;
                    $user['email'] = $email;
                    $found = true;
                    break;
                }
            }
            if ($found) {
               
                if (file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT))) {
                    // Mise à jour de la session
                    $_SESSION['user']['nom'] = $nom;
                    $_SESSION['user']['prenom'] = $prenom;
                    $_SESSION['user']['email'] = $email;
                } else {
                    $error_message = "Erreur lors de la mise à jour du fichier.";
                }
            } 
            else {
                $error_message = "Utilisateur non trouvé.";
            }            
        } 
        else {
            $error_message = "Fichier utilisateur introuvable.";
        }
    } 
    else {
        $error_message = "Tous les champs sont obligatoires.";
    }
}

$commandesJson = file_get_contents('json/Commande.json');
$commandes = json_decode($commandesJson, true);

$mesVoyages = [];
foreach ($commandes as $commande) {
    if ($commande['status'] === 'accepted') {
        foreach ($commande['options'] as $option) {
            if ($option['user_id'] === $user['id']) {
                $voyage = [
                    'transaction_id' => $commande['transaction_id'],
                    'date' => $commande['date'],
                    'destination' => $option['destination'],
                    'departure_date' => $option['departure_date'],
                    'return_date' => $option['return_date'],
                    'prix_total' => $option['prix_total']
                ];
                $mesVoyages[] = $voyage;
            }
        }
    }
}
?>

<?php require_once('header.php'); ?>

        <div class="container">
            <div class="Compte">
                <h2 class="h2">Mon Profil</h2>
               
                <form id="profile-form" action="PageProfil.php" method="post">
                    <div class="profil">
                        <label for="nom">Nom :</label>
                        <div class="field-wrapper">
                        <input id="nom" name="nom" type="text"
                        value="<?php echo htmlspecialchars($_SESSION['user']['nom']); ?>"
                                disabled
                                data-original="<?php echo htmlspecialchars($_SESSION['user']['nom']); ?>">
                        <i class="fas fa-pen edit-btn"></i>
                        <i class="fas fa-check save-btn hidden"></i>
                        <i class="fas fa-times cancel-btn hidden"></i>
                        </div>
                    </div>
                    <div class="profil">
                        <label for="prenom">Prénom :</label>
                        <div class="field-wrapper">
                        <input id="prenom" name="prenom" type="text"
                                value="<?php echo htmlspecialchars($_SESSION['user']['prenom']); ?>"
                                disabled
                                data-original="<?php echo htmlspecialchars($_SESSION['user']['prenom']); ?>">
                        <i class="fas fa-pen edit-btn"></i>
                        <i class="fas fa-check save-btn hidden"></i>
                        <i class="fas fa-times cancel-btn hidden"></i>
                        </div>
                    </div>
                    <div class="profil">
                        <label for="email">Email :</label>
                        <div class="field-wrapper">
                        <input id="email" name="email" type="text"
                                value="<?php echo htmlspecialchars($_SESSION['user']['email']); ?>"
                                disabled
                                data-original="<?php echo htmlspecialchars($_SESSION['user']['email']); ?>">
                        <i class="fas fa-pen edit-btn"></i>
                        <i class="fas fa-check save-btn hidden"></i>
                        <i class="fas fa-times cancel-btn hidden"></i>
                        </div>
                    </div>
                    
                    <button id="submit-changes" class="button" type="submit"   name="submit_changes" style="display:none">
                        Modifier
                    </button>    
                    
                   
                </form>

                <form action="PageProfil.php" method="post">
                    <button type="submit" name="deconnecter" class="button">Se déconnecter</button>
                </form>           
            </div>
        
                <div class="Compte">
                    <h2 class="h2">Mes voyages</h2>
                    <div class="voyage-list">
                        <?php if (empty($mesVoyages)): ?>
                            <p class="no-voyages">Vous n'avez pas encore de voyages réservés.</p>
                        <?php else: ?>
                            <?php foreach ($mesVoyages as $voyage): ?>
                                <a href="PageMesvoyages.php?id=<?php echo urlencode($voyage['transaction_id']); ?>" class="voyage-item">
                                    <div class="voyage-destination"><?php echo htmlspecialchars($voyage['destination']); ?></div>
                                    <div class="voyage-dates">
                                        Du <?php echo date('d/m/Y', strtotime($voyage['departure_date'])); ?> 
                                        au <?php echo date('d/m/Y', strtotime($voyage['return_date'])); ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    
                </div>
            
            
        </div>
        
    <?php 
$scripts = '
    <script src="Javascript/ModifProfil.js"></script>
';
require_once('footer.php'); 
?>
