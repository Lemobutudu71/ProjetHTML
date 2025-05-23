// Attend que le contenu HTML de la page soit complètement chargé avant d'exécuter le script.
document.addEventListener('DOMContentLoaded', function(){
  
    // Prix de base du voyage à Westeros.
    const basePrice = 4300;
    
    // Définit les prix pour chaque activité optionnelle à Westeros.
    const activitePrix = {
      troneFer: 60,      // Prix pour s'asseoir sur le Trône de Fer.
      mur: 45,           // Prix pour la visite du Mur.
      dragon: 120,       // Prix pour un vol à dos de dragon.
      tournoi: 35,       // Prix pour assister à un tournoi de chevaliers.
      festin: 50,        // Prix pour participer à un festin royal.
      portReal: 25       // Prix pour la visite guidée de Port-Réal.
    };

    // Vérifie si l'utilisateur est VIP en lisant l'attribut 'data-vip' d'un élément de la page.
    const isVip = document.querySelector('.Page-Accueil2-text').getAttribute('data-vip') === 'true';
    // Applique une réduction de 10% si l'utilisateur est VIP, sinon pas de réduction.
    const vipReduction = isVip ? 0.9 : 1;
    
    // Récupère les éléments du DOM pour interagir avec la page.
    const nb_pers_Voyage = document.getElementById('nb_personnes_voyage');
    const priceDynamicElement = document.getElementById('prix-total-dynamique');

    const checkboxTroneFer = document.getElementById('troneFer');
    const nb_personnes_trone = document.getElementById('nb_personnes_trone');

    const checkboxMur = document.getElementById('mur');
    const nb_personnes_mur = document.getElementById('nb_personnes_mur');

    const checkboxDragon = document.getElementById('dragon');
    const nb_personnes_dragon = document.getElementById('nb_personnes_dragon');

    const checkboxTournoi = document.getElementById('tournoi');
    const nb_personnes_tournoi = document.getElementById('nb_personnes_tournoi');

    const checkboxFestin = document.getElementById('festin');
    const nb_personnes_festin = document.getElementById('nb_personnes_festin');
    
    const checkboxPortReal = document.getElementById('portReal');
    const nb_personnes_portreal = document.getElementById('nb_personnes_portreal');

    // Tableau des inputs numériques pour le nombre de participants aux activités.
    const activityInputs = [
        nb_personnes_trone,
        nb_personnes_mur,
        nb_personnes_dragon,
        nb_personnes_tournoi,
        nb_personnes_festin,
        nb_personnes_portreal
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
        if (checkboxTroneFer && checkboxTroneFer.checked) {
            let nbTrone = nb_personnes_trone ? parseInt(nb_personnes_trone.value) : 0;
            totalActivityCost += nbTrone * activitePrix.troneFer;
        }
        if (checkboxMur && checkboxMur.checked) {
            let nbMur = nb_personnes_mur ? parseInt(nb_personnes_mur.value) : 0;
            totalActivityCost += nbMur * activitePrix.mur;
        }
        if (checkboxDragon && checkboxDragon.checked) {
            let nbDragon = nb_personnes_dragon ? parseInt(nb_personnes_dragon.value) : 0;
            totalActivityCost += nbDragon * activitePrix.dragon;
        }
        if (checkboxTournoi && checkboxTournoi.checked) {
            let nbTournoi = nb_personnes_tournoi ? parseInt(nb_personnes_tournoi.value) : 0;
            totalActivityCost += nbTournoi * activitePrix.tournoi;
        }
        if (checkboxFestin && checkboxFestin.checked) {
            let nbFestin = nb_personnes_festin ? parseInt(nb_personnes_festin.value) : 0;
            totalActivityCost += nbFestin * activitePrix.festin;
        }
        if (checkboxPortReal && checkboxPortReal.checked) {
            let nbPortReal = nb_personnes_portreal ? parseInt(nb_personnes_portreal.value) : 0;
            totalActivityCost += nbPortReal * activitePrix.portReal;
        }
        
        // Calcule le prix total incluant la réduction VIP.
        let totalPrice = (travelCost + totalActivityCost) * vipReduction;
        
        // Formatte et affiche le prix.
        let priceText = "Prix estimé : " + new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalPrice) + "€";
        priceDynamicElement.textContent = priceText;
    }
    
    // Ajoute des écouteurs d'événements pour mettre à jour le prix lorsque les sélections changent.
    if (nb_pers_Voyage) nb_pers_Voyage.addEventListener('input', updatePrice);
    
    if (checkboxTroneFer) checkboxTroneFer.addEventListener('change', updatePrice);
    if (nb_personnes_trone) nb_personnes_trone.addEventListener('change', updatePrice);
    
    if (checkboxMur) checkboxMur.addEventListener('change', updatePrice);
    if (nb_personnes_mur) nb_personnes_mur.addEventListener('change', updatePrice);
    
    if (checkboxDragon) checkboxDragon.addEventListener('change', updatePrice);
    if (nb_personnes_dragon) nb_personnes_dragon.addEventListener('change', updatePrice);
  
    if (checkboxTournoi) checkboxTournoi.addEventListener('change', updatePrice);
    if (nb_personnes_tournoi) nb_personnes_tournoi.addEventListener('change', updatePrice);
    
    if (checkboxFestin) checkboxFestin.addEventListener('change', updatePrice);
    if (nb_personnes_festin) nb_personnes_festin.addEventListener('change', updatePrice);
    
    if (checkboxPortReal) checkboxPortReal.addEventListener('change', updatePrice);
    if (nb_personnes_portreal) nb_personnes_portreal.addEventListener('change', updatePrice);
    
    updatePrice(); // Calcul et affichage du prix initial.
});