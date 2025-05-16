<?php
session_start();

// Activation des erreurs pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 0);

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Accès non autorisé']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['userId'] ?? null;
$field = $data['field'] ?? null;
$value = $data['value'] ?? null;

error_log("Données reçues : " . print_r($data, true));

if (!$userId || !$field || !in_array($field, ['Vip', 'Bloquer'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Données invalides']);
    exit();
}

$file = __DIR__ . '/json/utilisateur.json';
error_log("Chemin du fichier : " . $file);

if (!file_exists($file) || !is_readable($file)) {
    http_response_code(500);
    echo json_encode(['error' => 'Fichier utilisateur inaccessible en lecture']);
    exit();
}

$jsonContent = file_get_contents($file);
if ($jsonContent === false) {
    http_response_code(500);
    echo json_encode(['error' => 'Impossible de lire le fichier utilisateur']);
    exit();
}

$users = json_decode($jsonContent, true);
if ($users === null) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur de décodage JSON']);
    exit();
}

error_log("Contenu users : " . print_r($users, true));

$updated = false;
$isCurrentUser = false;

foreach ($users as &$user) {
    if ($user['id'] === $userId) {
        $user[$field] = $value;
        $updated = true;
        
        // Vérifier si c'est l'utilisateur actuellement connecté
        if ($user['id'] === $_SESSION['user']['id']) {
            $isCurrentUser = true;
        }
        break;
    }
}

if ($updated) {
    $writeResult = file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
    if ($writeResult === false) {
        error_log("Erreur d'écriture dans le fichier: " . error_get_last()['message']);
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de l\'écriture du fichier']);
    } else {
        // Si c'est l'utilisateur actuel qui est bloqué, détruire sa session
        if ($isCurrentUser && $field === 'Bloquer' && $value === 'Oui') {
            session_destroy();
            echo json_encode([
                'success' => true,
                'redirect' => BASE_PATH . '/bloquer.php'
            ]);
        } else {
            echo json_encode(['success' => true]);
        }
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Utilisateur non trouvé']);
}
exit();
?>