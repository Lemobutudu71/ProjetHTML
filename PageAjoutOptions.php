<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: PageSeconnecter.php");
    exit();
}

$user_id = $_SESSION['user']['id'];
$transaction_id = $_GET['id'] ?? null;

if (!$transaction_id) {
    header("Location: PageProfil.php");
    exit();
}

$commandes_file = 'json/Commande.json';
$etapes_file = 'json/Etapes_Options.json';
$options_file = 'json/options.json';


$commandes = [];
$etapes_data = [];
$user_choices = null;

if (file_exists($commandes_file)) {
    $commandes = json_decode(file_get_contents($commandes_file), true);
}

if (file_exists($etapes_file)) {
    $etapes_data = json_decode(file_get_contents($etapes_file), true);
}

$commande = null;
$option_voyage = null;

foreach ($commandes as $cmd) {
    if ($cmd['transaction_id'] === $transaction_id) {
        $commande = $cmd;
        foreach ($cmd['options'] as $option) {
            if ($option['user_id'] === $user_id) {
                $option_voyage = $option;
                break;
            }
        }
        break;
    }
}

if (!$commande || !$option_voyage) {
    header("Location: PageProfil.php");
    exit();
}

$destination = $option_voyage['destination'];
$etapes = is_array($option_voyage['etapes']) ? $option_voyage['etapes'] : explode(',', $option_voyage['etapes']);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_options = [];
    $new_prix = 0;
    $new_nb_personnes = [];
    
    foreach ($etapes as $etape) {
        $clean_step = strtolower(str_replace(' ', '_', $etape));
        $activites_key = 'activites_' . $clean_step;
        
        if (isset($_POST[$activites_key])) {
            $new_activites = $_POST[$activites_key];
            $new_options[$activites_key] = $new_activites;
            foreach ($new_activites as $activite) {
                if (!in_array($activite, $option_voyage[$activites_key] ?? [])) {
                    $nb_personnes = isset($_POST['nb_personnes'][$activite]) ? intval($_POST['nb_personnes'][$activite]) : 1;
                    $prix_activite = $option_voyage['activite_prix'][$activite] ?? 0;
                    $new_prix += $prix_activite * $nb_personnes;
                    $new_nb_personnes[$activite] = $nb_personnes;
                }
            }
        }
    }
    
    if ($new_prix > 0) {
        $new_transaction_id = uniqid();
        foreach ($commandes as &$cmd) {
            if ($cmd['transaction_id'] === $transaction_id) {
                foreach ($cmd['options'] as &$opt) {
                    if ($opt['user_id'] === $user_id) {
                        foreach ($new_options as $key => $value) {
                            $opt[$key] = array_merge($opt[$key] ?? [], $value);
                        }
                        if (!isset($opt['nb_personnes'])) {
                            $opt['nb_personnes'] = [];
                        }
                        $opt['nb_personnes'] = array_merge($opt['nb_personnes'], $new_nb_personnes);
                        $opt['prix_total'] += $new_prix;
                        break;
                    }
                }
                break;
            }
        }
   file_put_contents($commandes_file, json_encode($commandes, JSON_PRETTY_PRINT));
        header("Location: pagePayer.php?transaction_id=" . $new_transaction_id . "&montant=" . $new_prix);
        exit();
    }
}
?>

<?php require_once('header.php'); ?>

<div class="Page-Accueil2-text">

    <h1>Ajouter des options à votre voyage - <?php echo htmlspecialchars($destination); ?></h1>
    
    <form method="POST" action="">
        <?php foreach ($etapes as $etape): 
            $clean_step = strtolower(str_replace(' ', '_', $etape));
            $step_data = $etapes_data[$destination][$etape] ?? [];
            $activites_disponibles = $step_data['activites'] ?? [];
            $activites_actuelles = $option_voyage['activites_' . $clean_step] ?? [];
        ?>
            <div class="section">
                <h2><?php echo htmlspecialchars($etape); ?></h2>
                
                <div class="activites">
                    <h3>Activités disponibles</h3>
                    <?php foreach ($activites_disponibles as $key => $activite): 
                        if (!in_array($key, $activites_actuelles)):
                    ?>
                        <div class="options-group">
                            <label>
                                <input type="checkbox" name="activites_<?php echo $clean_step; ?>[]" value="<?php echo $key; ?>">
                                <?php echo htmlspecialchars($activite); ?> 
                                (<?php echo number_format($option_voyage['activite_prix'][$key] ?? 0, 2, ',', ' '); ?> €)
                            </label>
                            <input type="number" name="nb_personnes[<?php echo $key; ?>]" value="1" min="1" max="<?php echo $option_voyage['nb_personnes_voyage']; ?>">
                        </div>
                    <?php endif; endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
        
        <div class="recherche">
            <button type="submit" class="Page-Accueil-button">Ajouter les options sélectionnées</button>
            <a href="PageMesvoyages.php?id=<?php echo urlencode($transaction_id); ?>" class="Page-Accueil-button">Retour</a>
        </div>
    </form>

</div>
<?php 
$scripts = '';
require_once('footer.php'); 
?>