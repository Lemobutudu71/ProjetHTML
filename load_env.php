<?php
// Vérifie si les variables d'environnement ont déjà été chargées pour éviter de le faire plusieurs fois.
if (!defined('ENV_LOADED')) {
    // Définit une constante pour marquer que les variables d'environnement sont en cours de chargement ou ont été chargées.
    define('ENV_LOADED', true);

    // Définit le chemin de base du projet (BASE_PATH) s'il n'est pas déjà défini.
    // __DIR__ est une constante magique PHP qui retourne le répertoire du fichier actuel.
    if (!defined('BASE_PATH')) {
        define('BASE_PATH', __DIR__);
    }
    // Définit le chemin web racine du projet (WEB_PATH) s'il n'est pas déjà défini.
    // Utile pour construire des URLs absolues au sein de l'application.
    // Exemple : /ProjetHTML si votre projet est dans un sous-dossier de votre serveur web.
    // MISE À JOUR : La valeur '/test/Projet' semble être spécifique à un environnement de développement.
    // Il est généralement préférable de rendre cela dynamique ou de le configurer correctement pour la production.
    if (!defined('WEB_PATH')) {
        define('WEB_PATH', '/test/Projet'); // TODO: Vérifier si cette valeur est correcte pour tous les environnements.
    }
    // Construit le chemin complet vers le fichier .env.
    // On suppose que le fichier .env se trouve dans le même répertoire que ce script (load_env.php).
    $envPath = __DIR__ . '/.env';

    // Vérifie si le fichier .env existe et est lisible.
    if (is_readable($envPath)) {
        // Lit toutes les lignes du fichier .env dans un tableau.
        // FILE_IGNORE_NEW_LINES: Ne pas ajouter de nouvelle ligne à la fin de chaque élément du tableau.
        // FILE_SKIP_EMPTY_LINES: Ignorer les lignes vides.
        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        // Parcourt chaque ligne lue du fichier .env.
        foreach ($lines as $line) {
            // Ignore les lignes qui commencent par '#' (commentaires dans le fichier .env).
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Sépare chaque ligne en nom et valeur en utilisant le premier '=' comme délimiteur.
            // La limite de 2 assure que si la valeur contient des '=', ils ne sont pas pris en compte pour la séparation.
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name); // Supprime les espaces autour du nom.
            $value = trim($value); // Supprime les espaces autour de la valeur.

            // Supprime les guillemets (simples ou doubles) qui pourraient entourer la valeur.
            if (substr($value, 0, 1) == '"' && substr($value, -1) == '"') {
                $value = substr($value, 1, -1);
            }
            if (substr($value, 0, 1) == "'" && substr($value, -1) == "'") {
                $value = substr($value, 1, -1);
            }

            // Définit la variable d'environnement dans $_ENV si elle n'existe pas déjà.
            if (!isset($_ENV[$name])) {
                $_ENV[$name] = $value;
            }
            // Définit également la variable dans $_SERVER si elle n'existe pas déjà (pour compatibilité avec certaines configurations/frameworks).
            if (!isset($_SERVER[$name])) {
                $_SERVER[$name] = $value;
            }
        }
    } else {
        // Solution de repli si le fichier .env n'est pas trouvé ou lisible.
        // Vous pourriez vouloir logger cette situation ou la gérer différemment en production.
        // Pour l'instant, on définit une variable PATH par défaut (vide) si elle n'est pas déjà définie.
        // Ceci est un exemple, adaptez selon les besoins de votre application si .env est crucial.
        if (!isset($_ENV['PATH'])) {
            $_ENV['PATH'] = ''; // Ou une valeur par défaut plus significative.
        }
        if (!isset($_SERVER['PATH'])) {
            $_SERVER['PATH'] = '';
        }
        // Il serait judicieux de logger une erreur ici pour informer qu'un fichier .env est manquant.
        // error_log("Fichier .env non trouvé ou illisible à l'emplacement: " . $envPath);
    }
}
?> 