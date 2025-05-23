<?php
// Démarre ou reprend une session PHP existante.
// Doit être appelé avant tout output HTML.
session_start();

// Incluts le script pour charger les variables d'environnement (comme BASE_PATH, WEB_PATH, etc.).
require_once 'load_env.php';  

// Vérifie si l'utilisateur est connecté.
// Si la variable de session 'user' n'est pas définie, cela signifie que l'utilisateur n'est pas connecté.
if (!isset($_SESSION['user'])) {
    // Redirige l'utilisateur vers la page d'inscription/connexion.
    // WEB_PATH est supposé être défini dans load_env.php et pointer vers la racine web du projet.
    header("Location: " . WEB_PATH . "/PageInscription.php");
    exit(); // Termine le script pour s'assurer que la redirection est effectuée et qu'aucun autre code n'est exécuté.
}

/**
 * Vérifie et met à jour le statut VIP et Bloquer de l'utilisateur dans la session.
 * Lit les informations depuis le fichier utilisateur.json.
 *
 * @param string $userId L'ID de l'utilisateur à vérifier.
 * @return array|null Retourne les données de l'utilisateur s'il est trouvé, sinon null.
 */
function verifierStatutUtilisateur($userId) {
    // Construit le chemin vers le fichier utilisateur.json.
    // BASE_PATH est supposé être défini dans load_env.php et pointer vers la racine du système de fichiers du projet.
    $file = BASE_PATH . '/json/utilisateur.json';
    // Vérifie si le fichier existe.
    if (file_exists($file)) {
        // Lit le contenu du fichier JSON.
        $users = json_decode(file_get_contents($file), true); // true pour obtenir un tableau associatif.
        // Parcourt la liste des utilisateurs.
        foreach ($users as $user) {
            // Si l'ID de l'utilisateur dans le fichier correspond à l'ID fourni.
            if ($user['id'] === $userId) {
                // Met à jour les informations Vip et Bloquer dans la session de l'utilisateur actuel.
                $_SESSION['user']['Vip'] = $user['Vip'];
                $_SESSION['user']['Bloquer'] = $user['Bloquer'];
                return $user; // Retourne les informations complètes de l'utilisateur trouvé.
            }
        }
    }
    return null; // Retourne null si le fichier n'existe pas ou si l'utilisateur n'est pas trouvé.
}

// Appelle la fonction pour vérifier et mettre à jour le statut de l'utilisateur actuellement connecté.
$userStatus = verifierStatutUtilisateur($_SESSION['user']['id']);

// Si l'utilisateur a été trouvé et que son statut 'Bloquer' est 'Oui'.
if ($userStatus && $userStatus['Bloquer'] === 'Oui') {
    // Détruit toutes les données de la session.
    session_destroy();
    // Redirige l'utilisateur vers une page indiquant qu'il est bloqué.
    header("Location: bloquer.php"); // Assurez-vous que bloquer.php existe et gère ce cas.
    exit(); // Termine le script.
}

/**
 * Vérifie si l'utilisateur actuellement connecté est VIP.
 *
 * @return bool True si l'utilisateur est VIP, sinon false.
 */
function isVip() {
    return isset($_SESSION['user']['Vip']) && $_SESSION['user']['Vip'] === 'Oui';
}

/**
 * Vérifie si l'utilisateur actuellement connecté est un administrateur.
 *
 * @return bool True si l'utilisateur est admin, sinon false.
 */
function isAdmin() {
    return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin';
}

/**
 * Calcule le prix total après application de la réduction VIP (si applicable).
 * La réduction est de 10%.
 *
 * @param float $prixTotal Le prix total avant réduction.
 * @return float Le prix après réduction VIP, ou le prix original si non VIP.
 */
function calculerPrixAvecReduction($prixTotal) {
    if (isVip()) {
        $reduction = $prixTotal * 0.10; // Calcule 10% de réduction.
        return $prixTotal - $reduction; // Retourne le prix réduit.
    }
    return $prixTotal; // Retourne le prix original si l'utilisateur n'est pas VIP.
}

/**
 * Retourne le pourcentage de réduction VIP.
 *
 * @return int Le pourcentage de réduction (10 pour VIP, 0 sinon).
 */
function ReductionVIP() {
    return isVip() ? 10 : 0;
}
?> 