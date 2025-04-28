<?php
session_start();


if (isset($_SESSION['user'])) {
    header("Location: PageProfil.php");
    exit;
}

$file = 'json/utilisateur.json';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $error_message = '';

    if (file_exists($file)) {
        $users = json_decode(file_get_contents($file), true);

        foreach ($users as $user) {
            if ($user['email'] === $email) {
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user'] = [
                        "id" => $user['id'],
                        "nom" => $user['nom'],
                        "prenom" => $user['prenom'],
                        "email" => $user['email'],
                        "role" => $user['role']
                    ];
                    header("Location: PageProfil.php");
                    exit;
                } else {
                    $error_message = "Le mot de passe est incorrect.";
                }
            }
        }
    }

    if (empty($error_message)) {
        $error_message = "L'email n'existe pas.";
    }
}
?>

<?php require_once('header.php'); ?>


        <div class="container">
            <div class="Compte">
                <h2 class="h2">Connexion</h2>
                <?php if (isset($error_message) && $error_message != ''): ?>
                    <p style="color: red;"><?php echo $error_message; ?></p>
                <?php endif; ?>
                <div class="form-error" id="registerError"></div>
                <form id="loginForm" action="PageSeconnecter.php" method="post">
                <input class="champs" name="email" type="email" placeholder="Email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                    <div class="password-container">
                        <input class="champs" name="password" type="password" id="loginPassword" placeholder="Mot de passe" required>
                        <i class="fas fa-eye toggle-password" data-for="loginPassword" onclick="togglePassword('loginPassword')"></i>
                    </div>
                    <button class="button" type="submit">Se connecter</button>
                </form>
                <p class="redirection"><a href="#">Mot de passe oublié ?</a></p>
            </div>
        </div>

        <?php 
$scripts = '
    <script src="Javascript/Icone.js"></script>
    <script src="Javascript/Connexion.js"></script>
';
require_once('footer.php'); 
?>
