// Attend que le contenu HTML de la page soit complètement chargé avant d'exécuter le script.
document.addEventListener('DOMContentLoaded', function(){
  
    // Prix de base du voyage à Poudlard.
    const basePrice = 3666;
    
    // Définit les prix pour chaque activité optionnelle à Poudlard.
    const activitePrix = {
      sorts: 25,         // Prix pour l'activité 'cours de sorts'.
      quidditch: 35,     // Prix pour l'activité 'match de Quidditch'.
      hyppogriffe: 40,   // Prix pour l'activité 'vol en Hyppogriffe'.
      zonko: 15,         // Prix pour la visite de la boutique 'Zonko'.
      degustation: 20,   // Prix pour la 'dégustation de Bièraubeurre'.
      honeydukes: 18     // Prix pour la visite de la confiserie 'Honeydukes'.
    };
  
    // Vérifie si l'utilisateur est VIP en lisant l'attribut 'data-vip' d'un élément de la page.
    // L'élément avec la classe 'Page-Accueil2-text' doit avoir un attribut 'data-vip="true"' ou 'data-vip="false"'.
    const isVip = document.querySelector('.Page-Accueil2-text').getAttribute('data-vip') === 'true';
    // Applique une réduction de 10% si l'utilisateur est VIP (prix * 0.9), sinon pas de réduction (prix * 1).
    const vipReduction = isVip ? 0.9 : 1;
    

    // Récupère l'élément input pour le nombre total de personnes pour le voyage.
    const nb_pers_Voyage = document.getElementById('nb_personnes_voyage');
    // Récupère l'élément où afficher le prix total calculé dynamiquement.
    const priceDynamicElement = document.getElementById('prix-total-dynamique');

    // Récupère la case à cocher pour l'activité 'cours de sorts'.
    const checkboxSorts = document.getElementById('sorts');
    // Récupère l'input pour le nombre de personnes pour l'activité 'cours de sorts'.
    const nb_personnes_sort = document.getElementById('nb_personnes_sort');

    // Récupère la case à cocher pour l'activité 'match de Quidditch'.
    const checkboxQuidditch = document.getElementById('quidditch');
    // Récupère l'input pour le nombre de personnes pour l'activité 'match de Quidditch'.
    const nb_personnes_quid = document.getElementById('nb_personnes_quid');

    // Récupère la case à cocher pour l'activité 'vol en Hyppogriffe'.
    const checkboxHyppogriffe = document.getElementById('hyppogriffe');
    // Récupère l'input pour le nombre de personnes pour l'activité 'vol en Hyppogriffe'.
    const nb_personnes_hy = document.getElementById('nb_personnes_hy');

    // Récupère la case à cocher pour la visite de 'Zonko'.
    const checkboxZonko = document.getElementById('zonko');
    // Récupère l'input pour le nombre de personnes pour la visite de 'Zonko'.
    const nb_personnes_visite = document.getElementById('nb_personnes_visite');

    // Récupère la case à cocher pour la 'dégustation de Bièraubeurre'.
    const checkboxDegustation = document.getElementById('degustation');
    // Récupère l'input pour le nombre de personnes pour la 'dégustation de Bièraubeurre'.
    const nb_personnes_degustation = document.getElementById('nb_personnes_degustation');
    
    // Récupère la case à cocher pour la visite de 'Honeydukes'.
    const checkboxHoneydukes = document.getElementById('honeydukes');
    // Récupère l'input pour le nombre de personnes pour la visite de 'Honeydukes'.
    const nb_personnes_honeydukes = document.getElementById('nb_personnes_honeydukes');

    // Crée un tableau contenant tous les inputs de nombre de personnes pour les activités.
    // Cela facilite l'application de logiques communes, comme la mise à jour de leur valeur maximale.
    const activityInputs = [
        nb_personnes_sort,
        nb_personnes_quid,
        nb_personnes_hy,
        nb_personnes_visite,
        nb_personnes_degustation,
        nb_personnes_honeydukes
    ];

    // Fonction pour mettre à jour l'attribut 'max' des inputs de nombre de personnes pour les activités.
    // Le maximum est basé sur le nombre total de personnes pour le voyage.
    function updateMaxPersonnes() {
        // Lit la valeur actuelle du nombre total de personnes pour le voyage et la convertit en entier.
        const maxPersonnes = parseInt(nb_pers_Voyage.value);
        
        // Parcourt chaque input de nombre de personnes pour les activités.
        activityInputs.forEach(input => {
            // Vérifie si l'input existe (au cas où certains éléments ne seraient pas sur la page).
            if (input) {
                // Définit l'attribut 'max' de l'input à la valeur de maxPersonnes.
                input.max = maxPersonnes;
                // Si la valeur actuelle de l'input est supérieure au nouveau maximum, la réduit au maximum.
                if (parseInt(input.value) > maxPersonnes) {
                    input.value = maxPersonnes;
                }
            }
        });
    }

    // Si l'input pour le nombre total de personnes pour le voyage existe.
    if (nb_pers_Voyage) {
        // Ajoute un écouteur d'événement 'input' pour appeler updateMaxPersonnes chaque fois que sa valeur change.
        nb_pers_Voyage.addEventListener('input', updateMaxPersonnes);
        updateMaxPersonnes(); // Appelle la fonction une fois au chargement pour initialiser les maximums.
    }

    // Ajoute un écouteur d'événement 'input' à chaque input de nombre de personnes pour les activités.
    activityInputs.forEach(input => {
        if (input) {
            input.addEventListener('input', function() {
                // Récupère le nombre maximum de personnes autorisé (basé sur le nombre total de voyageurs).
                const maxPersonnes = parseInt(nb_pers_Voyage.value);
                // Si la valeur saisie dépasse ce maximum, la ramène au maximum.
                if (parseInt(this.value) > maxPersonnes) {
                    this.value = maxPersonnes;
                }
                updatePrice(); // Met à jour le prix total affiché.
            });
        }
    });
  
    // Fonction pour calculer et afficher le prix total estimé du voyage.
    function updatePrice() {
        // Récupère le nombre de voyageurs. S'il n'est pas défini, utilise 1 par défaut.
        let nbVoyage = nb_pers_Voyage ? parseInt(nb_pers_Voyage.value) : 1;
        // Calcule le coût de base du voyage pour le nombre de voyageurs.
        let travelCost = basePrice * nbVoyage;
        
        // Initialise le coût total des activités.
        let totalActivityCost = 0;
        // Si la case 'cours de sorts' est cochée et que l'input de nombre de personnes existe.
        if (checkboxSorts && checkboxSorts.checked) {
            let nbSort = nb_personnes_sort ? parseInt(nb_personnes_sort.value) : 0;
            totalActivityCost += nbSort * activitePrix.sorts;
        }
        // Répète la logique pour chaque activité...
        if (checkboxQuidditch && checkboxQuidditch.checked) {
            let nbQuid = nb_personnes_quid ? parseInt(nb_personnes_quid.value) : 0;
            totalActivityCost += nbQuid * activitePrix.quidditch;
        }
        if (checkboxHyppogriffe && checkboxHyppogriffe.checked) {
            let nbHy = nb_personnes_hy ? parseInt(nb_personnes_hy.value) : 0;
            totalActivityCost += nbHy * activitePrix.hyppogriffe;
        }
        
        if (checkboxZonko && checkboxZonko.checked) {
            let nbZonko = nb_personnes_visite ? parseInt(nb_personnes_visite.value) : 0;
            totalActivityCost += nbZonko * activitePrix.zonko;
        }
        if (checkboxDegustation && checkboxDegustation.checked) {
            let nbDegust = nb_personnes_degustation ? parseInt(nb_personnes_degustation.value) : 0;
            totalActivityCost += nbDegust * activitePrix.degustation;
        }
        if (checkboxHoneydukes && checkboxHoneydukes.checked) {
            let nbHoney = nb_personnes_honeydukes ? parseInt(nb_personnes_honeydukes.value) : 0;
            totalActivityCost += nbHoney * activitePrix.honeydukes;
        }
        
        // Calcule le prix total en ajoutant le coût du voyage et le coût des activités, puis applique la réduction VIP.
        let totalPrice = (travelCost + totalActivityCost) * vipReduction;
        
        // Formatte le prix total en euros avec deux décimales pour l'affichage.
        let priceText = "Prix estimé : " + new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalPrice) + "€";
        
        // Met à jour le contenu de l'élément d'affichage du prix.
        priceDynamicElement.textContent = priceText;
    }
    
    // Ajoute des écouteurs d'événements pour mettre à jour le prix lorsque les inputs ou checkboxes changent.
    // Si l'input du nombre de voyageurs existe, écoute ses changements.
    if (nb_pers_Voyage) nb_pers_Voyage.addEventListener('input', updatePrice);
    
    // Pour chaque activité, si la checkbox existe, écoute ses changements.
    if (checkboxSorts) checkboxSorts.addEventListener('change', updatePrice);
    // Si l'input de nombre de personnes pour l'activité existe, écoute ses changements.
    if (nb_personnes_sort) nb_personnes_sort.addEventListener('change', updatePrice);
    
    if (checkboxQuidditch) checkboxQuidditch.addEventListener('change', updatePrice);
    if (nb_personnes_quid) nb_personnes_quid.addEventListener('change', updatePrice);
    
    if (checkboxHyppogriffe) checkboxHyppogriffe.addEventListener('change', updatePrice);
    if (nb_personnes_hy) nb_personnes_hy.addEventListener('change', updatePrice);
  
    if (checkboxZonko) checkboxZonko.addEventListener('change', updatePrice);
    if (nb_personnes_visite) nb_personnes_visite.addEventListener('change', updatePrice);
    
    if (checkboxDegustation) checkboxDegustation.addEventListener('change', updatePrice);
    if (nb_personnes_degustation) nb_personnes_degustation.addEventListener('change', updatePrice);
    
    if (checkboxHoneydukes) checkboxHoneydukes.addEventListener('change', updatePrice);
    if (nb_personnes_honeydukes) nb_personnes_honeydukes.addEventListener('change', updatePrice);
    
    updatePrice(); // Appelle updatePrice une fois au chargement pour afficher le prix initial.
});