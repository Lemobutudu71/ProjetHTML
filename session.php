<?php
session_start();
require_once 'load_env.php';  


if (!isset($_SESSION['user'])) {
    header("Location: " . WEB_PATH . "/PageInscription.php");
    exit();
}

function verifierStatutUtilisateur($userId) {
    $file = BASE_PATH . '/json/utilisateur.json';
    if (file_exists($file)) {
        $users = json_decode(file_get_contents($file), true);
        foreach ($users as $user) {
            if ($user['id'] === $userId) {
                $_SESSION['user']['Vip'] = $user['Vip'];
                $_SESSION['user']['Bloquer'] = $user['Bloquer'];
                return $user;
            }
        }
    }
    return null;
}

$userStatus = verifierStatutUtilisateur($_SESSION['user']['id']);

if ($userStatus && $userStatus['Bloquer'] === 'Oui') {
    session_destroy();
    header("Location: bloquer.php");
    exit();
}

function isVip() {
    return isset($_SESSION['user']['Vip']) && $_SESSION['user']['Vip'] === 'Oui';
}

function isAdmin() {
    return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
}

function calculerPrixAvecReduction($prixTotal) {
    if (isVip()) {
        $reduction = $prixTotal * 0.10; 
        return $prixTotal - $reduction;
    }
    return $prixTotal;
}

function ReductionVIP() {
    return isVip() ? 10 : 0;
}
?> 