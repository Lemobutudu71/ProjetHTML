<?php
session_start(); 

$debug_messages = []; 

if (isset($_SESSION['user'])) {
    header("Location: PageProfil.php");
    exit;
}

$file = 'json/utilisateur.json';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $error_message = '';

    $debug_messages[] = "Login attempt started.";
    $debug_messages[] = "Submitted Email: " . $email;
    
    $debug_messages[] = "Submitted Password Length: " . strlen($password);

    if (file_exists($file)) {
        $users_data = file_get_contents($file);

        if ($users_data === false) {
            $error_message = "Erreur de lecture du fichier utilisateur.";
            $debug_messages[] = "Error: Could not read the user file (' . $file . ').";
        } else {
            $users = json_decode($users_data, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $error_message = "Erreur de décodage des données utilisateur (JSON).";
                $debug_messages[] = "Error: JSON decoding failed. " . json_last_error_msg();
            } else {
                $user_found = false;
                foreach ($users as $user) {
                    $debug_messages[] = "Checking against user: " . $user['email'];
                    if ($user['email'] === $email) {
                        $user_found = true;
                        $debug_messages[] = "User found in JSON: " . $user['email'];
                        $debug_messages[] = "Stored Password Hash: " . $user['password'];
                       

                        if (password_verify($password, $user['password'])) {
                            $debug_messages[] = "Password verification SUCCESSFUL for " . $email;
                            $_SESSION['user'] = [
                                "id" => $user['id'],
                                "nom" => $user['nom'],
                                "prenom" => $user['prenom'],
                                "email" => $user['email'],
                                "role" => $user['role'],
                                "Vip" => $user['Vip'],
                                "Bloquer" => $user['Bloquer'],
                            ];
        
                            header("Location: PageProfil.php");
                            exit;
                        } else {
                            $error_message = "Le mot de passe est incorrect.";
                            $debug_messages[] = "Password verification FAILED for " . $email;
                        }
                        break; 
                    }
                }

                if (!$user_found && empty($error_message)) { 
                    $error_message = "L\'email n\'existe pas.";
                    $debug_messages[] = "Email not found in user list: " . $email;
                }
            }
        }
    } else {
        $error_message = "Fichier utilisateur introuvable.";
        $debug_messages[] = "Error: User file (' . $file . ') not found.";
    }


    if (empty($error_message) && !$user_found) {
        $error_message = "L\'email n\'existe pas.";
        $debug_messages[] = "Final check: Email not found - " . $email;
    }
}
?>

<?php require_once('header.php'); ?>


        <div class="container">
            <div class="Compte">
                <h2 class="h2">Connexion</h2>
                <?php if (isset($error_message) && $error_message != ''): ?>
                    <p style="color: red;"><?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php endif; ?>
                <div class="form-error" id="registerError"></div>
                <form id="loginForm" action="PageSeconnecter.php" method="post">
                <input class="champs" name="email" type="email" placeholder="Email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required autocomplete="email">
                    <div class="password-container">
                        <input class="champs" name="password" type="password" id="loginPassword" placeholder="Mot de passe" required autocomplete="current-password">
                        <i class="fas fa-eye toggle-password" data-for="loginPassword" onclick="togglePassword('loginPassword')"></i>
                    </div>
                    <p class="Page-Accueil-text" id="Compteur">0 / 30</p>
                    <button class="button" type="submit">Se connecter</button>
                </form>
            </div>
        </div>

        <?php 

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($debug_messages)) {
   
    echo "<script>console.log('PHP Debug Messages:', " . json_encode($debug_messages, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) . ");</script>";
}

$scripts = '
    <script src="Javascript/Icone.js"></script>
    <script src="Javascript/Connexion.js"></script>
     <script>
        document.getElementById("loginPassword").addEventListener("input", function() {
        const maxLength = 30;
        let value = this.value;
        if (value.length > maxLength) {
            this.value = value.slice(0, maxLength);
        }
        document.getElementById("Compteur").textContent = this.value.length + " / " + maxLength;
    });
    </script>
';
require_once('footer.php'); 
?>
