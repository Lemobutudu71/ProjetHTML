<?php
session_start();


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


if (!$userId || !$field || !in_array($field, ['Vip', 'Bloquer'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Données invalides']);
    exit();
}

$file = 'json/utilisateur.json';

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


$updated = false;
foreach ($users as &$user) {
    if ($user['id'] === $userId) {
        $user[$field] = $value;
        $updated = true;
        
        if ($user['id'] === $_SESSION['user']['id']) {
            $_SESSION['user'][$field] = $value;
        }
        break;
    }
}

if ($updated) {
    $writeResult = file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
    if ($writeResult === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de l\'écriture du fichier']);
    } else {
        echo json_encode(['success' => true]);
    }
} 

else {
    http_response_code(404);
    echo json_encode(['error' => 'Utilisateur non trouvé']);
}
exit(); 
?>