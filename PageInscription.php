<?php
    session_start();

    if (isset($_SESSION['user'])) {
        header("Location: PageProfil.php");
        exit();
    }


    $file = 'json/utilisateur.json';

        
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $email = trim($_POST['email']);
        $role = trim($_POST['role']);
        $password = trim($_POST['password']); 
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); 

        
        if (file_exists($file)) { 
            $users = json_decode(file_get_contents($file), true);
            foreach ($users as $user) {
                if ($user['email'] === $email) {
                    $error_message = "Cet email est déjà utilisé. Veuillez en choisir un autre.";
                    break;

                }
            }
        } 
        else {
            $users = [];
        }
        if (empty($error_message)){
            $user_id = uniqid('', true); 
            $Vip ="Non";
            $Bloquer = "Non";

            $newUser = [
                "id" => $user_id,
                "nom" => $nom,
                "prenom" => $prenom,
                "email" => $email,
                "role" => $role,
                "password" =>  $hashedPassword,
                "Vip" => $Vip,
                "Bloquer" => $Bloquer,
            ];

        $users[] = $newUser;
        file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));  
        header("Location: PageSeconnecter.php"); 
        } 
    }
      
?>

<?php require_once('header.php'); ?>

        <div class="container">
            <div class="Compte">
                <h2 class="h2">Créer un compte</h2>
                <?php if (isset($error_message) && $error_message != ""): ?>
                    <p style="color: red;"><?php echo $error_message; ?></p>
                <?php endif; ?>
                <div class="form-error" id="registerError"></div>
                <form id="registerForm" action="PageInscription.php" method="post">
                    <input class="champs" name="nom" type="text" placeholder="Nom" required>
                    <input class="champs" name="prenom" type="text" placeholder="Prénom" required>
                    <input class="champs" name="email" type="email" placeholder="Email" required>
                    <select class="champs" name="role" required>
                        <option value="user">Utilisateur</option>
                        <option value="admin">Administrateur</option>
                    </select>
                    <div class="password-container">
                        <input class="champs" name="password" type="password" id="registerPassword" placeholder="Mot de passe" required>
                        <i class="fas fa-eye toggle-password" data-for="registerPassword" onclick="togglePassword('registerPassword')"></i>
                       
                    </div>
                    <p class="Page-Accueil-text" id="Compteur">0 / 30</p>
                    <button class="button" type="submit">S'inscrire</button>
                </form>
                <p class="redirection"><a href="PageSeconnecter.php">Déjà un compte ?</a></p>
            </div>
        </div>

<?php 
$scripts = '
    <script src="Javascript/Icone.js"></script>
    <script src="Javascript/FormVerif.js"></script>
    <script>
        document.getElementById("registerPassword").addEventListener("input", function() {
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