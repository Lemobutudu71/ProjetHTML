<?php
session_start();


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: PageAccueil.php"); // Redirection si non admin
    exit();
}


$file = 'json/utilisateur.json';
$users = file_exists($file) ? json_decode(file_get_contents($file), true) : [];


$usersPerPage = 2; 
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1; 
$totalUsers = count($users);
$totalPages = ceil($totalUsers / $usersPerPage);
$startIndex = ($page - 1) * $usersPerPage;
$usersToShow = array_slice($users, $startIndex, $usersPerPage);
?>

<?php require_once('header.php'); ?>   
            <h2 class="table-titre">Panneau Administrateur</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>VIP</th>
                        <th>Bloqué</th>
                        <th>Profil</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usersToShow as $index => $user): ?>
                        <tr>
                            <td><?php echo $startIndex + $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($user['nom']); ?></td>
                            <td><?php echo htmlspecialchars($user['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td> <button class="<?php echo $user['Vip'] === 'Oui' ? 'Oui-btn' : 'Non-btn'; ?>" 
                                    data-user-id="<?php echo htmlspecialchars($user['id']); ?>" 
                                    data-field="Vip">
                                    <?php echo $user['Vip']; ?>
                                </button>
                            </td>
                            <td><button class="<?php echo $user['Bloquer'] === 'Oui' ? 'Oui-btn' : 'Non-btn'; ?>" 
                                        data-user-id="<?php echo htmlspecialchars($user['id']); ?>" 
                                        data-field="Bloquer">
                                    <?php echo $user['Bloquer']; ?>
                                </button>
                            </td>
                            <td><button class="profil-btn">Voir</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">Précédent</a>
            <?php endif; ?>

            <span>Page <?php echo $page; ?> sur <?php echo $totalPages; ?></span>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>">Suivant</a>
            <?php endif; ?>
        </div>


<?php 
$scripts = '
    <script src="Javascript/Admin.js"></script>
';
require_once('footer.php'); 
?>