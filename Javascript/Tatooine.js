// Attend que le contenu HTML de la page soit complètement chargé avant d'exécuter le script.
document.addEventListener('DOMContentLoaded', function(){
  
    // Prix de base du voyage sur Tatooine.
    const basePrice = 2789;
    
    // Définit les prix pour chaque activité optionnelle sur Tatooine.
    const activitePrix = {
      speeder: 20,       // Prix pour la location de Speeder.
      module: 25,        // Prix pour la course de module.
      cantina: 15,       // Prix pour la visite de la Cantina.
      fermeH: 10,        // Prix pour la visite de la ferme hydroponique.
      sable: 30,         // Prix pour l'excursion dans la mer de sable.
      droides: 12        // Prix pour la visite du marché des droïdes.
    };

    // Vérifie si l'utilisateur est VIP en lisant l'attribut 'data-vip' d'un élément de la page.
    const isVip = document.querySelector('.Page-Accueil2-text').getAttribute('data-vip') === 'true';
    // Applique une réduction de 10% si l'utilisateur est VIP, sinon pas de réduction.
    const vipReduction = isVip ? 0.9 : 1;
    
    // Récupère les éléments du DOM pour interagir avec la page.
    const nb_pers_Voyage = document.getElementById('nb_personnes_voyage');
    const priceDynamicElement = document.getElementById('prix-total-dynamique');

    const checkboxSpeeder = document.getElementById('speeder');
    const nb_personnes_speeder = document.getElementById('nb_personnes_speeder');

    const checkboxModule = document.getElementById('module');
    const nb_personnes_module = document.getElementById('nb_personnes_module');

    const checkboxCantina = document.getElementById('cantina');
    const nb_personnes_cantina = document.getElementById('nb_personnes_cantina');

    const checkboxFermeH = document.getElementById('fermeH');
    const nb_personnes_ferme = document.getElementById('nb_personnes_ferme');

    const checkboxSable = document.getElementById('sable');
    const nb_personnes_sable = document.getElementById('nb_personnes_sable');
    
    const checkboxDroides = document.getElementById('droides');
    const nb_personnes_droides = document.getElementById('nb_personnes_droides');

    // Tableau des inputs numériques pour le nombre de participants aux activités.
    const activityInputs = [
        nb_personnes_speeder,
        nb_personnes_module,
        nb_personnes_cantina,
        nb_personnes_ferme,
        nb_personnes_sable,
        nb_personnes_droides
    ];

    // Fonction pour mettre à jour le nombre maximum de participants pour chaque activité.
    // Ce maximum est basé sur le nombre total de voyageurs.
    function updateMaxPersonnes() {
        const maxPersonnes = parseInt(nb_pers_Voyage.value);
        
        activityInputs.forEach(input => {
            if (input) {
                input.max = maxPersonnes;
                if (parseInt(input.value) > maxPersonnes) {
                    input.value = maxPersonnes;
                }
            }
        });
    }

    // Si l'input du nombre de voyageurs existe, ajoute un écouteur pour mettre à jour les maximums.
    if (nb_pers_Voyage) {
        nb_pers_Voyage.addEventListener('input', updateMaxPersonnes);
        updateMaxPersonnes(); // Appel initial
    }

    // Ajoute des écouteurs aux inputs des activités pour valider le nombre de participants et mettre à jour le prix.
    activityInputs.forEach(input => {
        if (input) {
            input.addEventListener('input', function() {
                const maxPersonnes = parseInt(nb_pers_Voyage.value);
                if (parseInt(this.value) > maxPersonnes) {
                    this.value = maxPersonnes;
                }
                updatePrice(); // Met à jour le prix total.
            });
        }
    });
  
    // Fonction pour calculer et afficher le prix total estimé du voyage.
    function updatePrice() {
        let nbVoyage = nb_pers_Voyage ? parseInt(nb_pers_Voyage.value) : 1;
        let travelCost = basePrice * nbVoyage; // Coût de base du voyage.
        
        let totalActivityCost = 0; // Coût total des activités.
        // Calcule le coût pour chaque activité sélectionnée.
        if (checkboxSpeeder && checkboxSpeeder.checked) {
            let nbSpeeder = nb_personnes_speeder ? parseInt(nb_personnes_speeder.value) : 0;
            totalActivityCost += nbSpeeder * activitePrix.speeder;
        }
        if (checkboxModule && checkboxModule.checked) {
            let nbModule = nb_personnes_module ? parseInt(nb_personnes_module.value) : 0;
            totalActivityCost += nbModule * activitePrix.module;
        }
        if (checkboxCantina && checkboxCantina.checked) {
            let nbCantina = nb_personnes_cantina ? parseInt(nb_personnes_cantina.value) : 0;
            totalActivityCost += nbCantina * activitePrix.cantina;
        }
        if (checkboxFermeH && checkboxFermeH.checked) {
            let nbFerme = nb_personnes_ferme ? parseInt(nb_personnes_ferme.value) : 0;
            totalActivityCost += nbFerme * activitePrix.fermeH;
        }
        if (checkboxSable && checkboxSable.checked) {
            let nbSable = nb_personnes_sable ? parseInt(nb_personnes_sable.value) : 0;
            totalActivityCost += nbSable * activitePrix.sable;
        }
        if (checkboxDroides && checkboxDroides.checked) {
            let nbDroides = nb_personnes_droides ? parseInt(nb_personnes_droides.value) : 0;
            totalActivityCost += nbDroides * activitePrix.droides;
        }
        
        // Calcule le prix total incluant la réduction VIP.
        let totalPrice = (travelCost + totalActivityCost) * vipReduction;
        
        // Formatte et affiche le prix.
        let priceText = "Prix estimé : " + new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalPrice) + "€";
        priceDynamicElement.textContent = priceText;
    }
    
    // Ajoute des écouteurs d'événements pour mettre à jour le prix lorsque les sélections changent.
    if (nb_pers_Voyage) nb_pers_Voyage.addEventListener('input', updatePrice);
    
    if (checkboxSpeeder) checkboxSpeeder.addEventListener('change', updatePrice);
    if (nb_personnes_speeder) nb_personnes_speeder.addEventListener('change', updatePrice);
    
    if (checkboxModule) checkboxModule.addEventListener('change', updatePrice);
    if (nb_personnes_module) nb_personnes_module.addEventListener('change', updatePrice);
    
    if (checkboxCantina) checkboxCantina.addEventListener('change', updatePrice);
    if (nb_personnes_cantina) nb_personnes_cantina.addEventListener('change', updatePrice);
  
    if (checkboxFermeH) checkboxFermeH.addEventListener('change', updatePrice);
    if (nb_personnes_ferme) nb_personnes_ferme.addEventListener('change', updatePrice);
    
    if (checkboxSable) checkboxSable.addEventListener('change', updatePrice);
    if (nb_personnes_sable) nb_personnes_sable.addEventListener('change', updatePrice);
    
    if (checkboxDroides) checkboxDroides.addEventListener('change', updatePrice);
    if (nb_personnes_droides) nb_personnes_droides.addEventListener('change', updatePrice);
    
    updatePrice(); // Calcul et affichage du prix initial.
});