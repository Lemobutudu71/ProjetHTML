<?php
require_once 'load_env.php';  
require_once 'session.php'; 


$is_ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

if ($is_ajax) {
   
    header('Content-Type: application/json');
    $ajax_general_debug = []; 
    $ajax_general_debug[] = "AJAX request detected by PageProfil.php.";
    $ajax_general_debug[] = "Request Method: " . $_SERVER["REQUEST_METHOD"];
    $ajax_general_debug[] = "Raw POST data: " . json_encode($_POST);
    
   
if (!isset($_SESSION['user'])) {
        echo json_encode(['success' => false, 'message' => 'Session expirée ou non connecté.', 'debug' => $ajax_general_debug]);
    exit;
}

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_changes'])) {
        
        $operation_response = ['success' => false, 'message' => 'Erreur initiale dans la logique de mise à jour du profil.']; // Default for this operation
        $operation_specific_debug = []; 
        
        $operation_specific_debug[] = "'submit_changes' POST condition met.";

        $nom = isset($_POST['nom']) ? trim($_POST['nom']) : "";
    $prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : "";
        $email = isset($_POST['email']) ? trim($_POST['email']) : "";
  
        $operation_specific_debug[] = "Processed Input: nom='$nom', prenom='$prenom', email='$email'";
        
        if (empty($nom) || empty($prenom) || empty($email)) {
            $operation_response = ['success' => false, 'message' => 'Tous les champs sont obligatoires.'];
            $operation_specific_debug[] = "Validation failed: Fields empty.";
        } else {
            $operation_specific_debug[] = "Validation passed.";
            $file_path = 'json/utilisateur.json';
            $operation_specific_debug[] = "User file path: $file_path";

            if (file_exists($file_path)) {
                $operation_specific_debug[] = "User file exists.";
                $users_json = file_get_contents($file_path);
                if ($users_json === false) {
                    $operation_response = ['success' => false, 'message' => 'Erreur: Impossible de lire le fichier utilisateur.'];
                    $operation_specific_debug[] = "Error: Cannot read user file.";
                } else {
                    $users = json_decode($users_json, true);
                    if ($users === null && json_last_error() !== JSON_ERROR_NONE) {
                        $operation_response = ['success' => false, 'message' => 'Erreur de décodage JSON: ' . json_last_error_msg()];
                        $operation_specific_debug[] = "Error: JSON decode failed - " . json_last_error_msg();
                    } 
                    else {
                        $current_user_id = $_SESSION['user']['id'];
                        $operation_specific_debug[] = "Current user ID: $current_user_id";
                        $user_found = false;
                        
                foreach ($users as &$user_record) {
                            if (isset($user_record['id']) && $user_record['id'] === $current_user_id) {
                             $operation_specific_debug[] = "Matching user found. Updating record.";
                            $user_record['nom'] = $nom;
                            $user_record['prenom'] = $prenom;
                            $user_record['email'] = $email;
                                        $user_found = true;
                            break;
                        }
               }
                        
             if ($user_found) {
                if (file_put_contents($file_path, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
                    $operation_specific_debug[] = "User file updated successfully.";
                    $_SESSION['user']['nom'] = $nom;
                    $_SESSION['user']['prenom'] = $prenom;
                    $_SESSION['user']['email'] = $email;
                     $operation_specific_debug[] = "Session updated.";
                    $operation_response = ['success' => true, 'newData' => ['nom' => $nom, 'prenom' => $prenom, 'email' => $email]];
                } 
                else {
                    $operation_response = ['success' => false, 'message' => 'Erreur lors de l\'écriture des données.'];
                    $operation_specific_debug[] = "Error: file_put_contents failed.";
                }
                        } else {
                            $operation_response = ['success' => false, 'message' => 'Utilisateur non trouvé pour la mise à jour.'];
                            $operation_specific_debug[] = "Error: User ID not found in JSON file.";
            }            
        } 
                }
            } else {
                $operation_response = ['success' => false, 'message' => 'Fichier utilisateur introuvable.'];
                $operation_specific_debug[] = "Error: User file does not exist.";
    }
        }
        
        
        $operation_response['debug'] = array_merge($ajax_general_debug, $operation_specific_debug);
        echo json_encode($operation_response);
        exit;
    }
    
    
    echo json_encode([
        'success' => false, 
        'message' => 'Opération AJAX non reconnue ou conditions non remplies (ex: submit_changes non présent dans POST).',
        'debug'   => $ajax_general_debug 
    ]);
    exit;
}

if (!isset($_SESSION['user'])) {
    header("Location: PageInscription.php"); 
        exit;
    }


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deconnecter'])) {
    session_unset();
    session_destroy();
    header("Location: PageAccueil.php"); 
    exit;
}


$user = $_SESSION['user'];


$commandesJson = @file_get_contents('json/Commande.json'); 
$commandes = [];
$mesVoyages = [];

if ($commandesJson === false) {
    
} else {
    $commandes = json_decode($commandesJson, true);
    if ($commandes === null && json_last_error() !== JSON_ERROR_NONE) {
        
    } else if (is_array($commandes)) {
foreach ($commandes as $commande) {
            if (isset($commande['status']) && $commande['status'] === 'accepted' && isset($commande['options']) && is_array($commande['options'])) {
        foreach ($commande['options'] as $option) {
                    if (isset($option['user_id']) && $option['user_id'] === $user['id']) {
                $voyage = [
                            'transaction_id' => $commande['transaction_id'] ?? 'N/A',
                            'date' => $commande['date'] ?? 'N/A',
                            'destination' => $option['destination'] ?? 'N/A',
                            'departure_date' => $option['departure_date'] ?? 'N/A',
                            'return_date' => $option['return_date'] ?? 'N/A',
                            'prix_total' => $option['prix_total'] ?? '0'
                ];
                $mesVoyages[] = $voyage;
            }
        }
    }
}
    }
}

require_once('header.php');

?>

        <div class="container">
            <div class="Compte">
                <h2 class="h2">Mon Profil</h2>
               
                <form id="profile-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
