document.addEventListener('DOMContentLoaded', function(){
  
    const basePrice = 5300;
    
   
    const activitePrix = {
      combat: 20,
      chasse: 35,
      mur: 30,
      tournoi: 12,
      trone: 30,
      fleuve: 50,
      dragons: 100,
      gladiateur: 77,
      marche: 10
    };
  
    
    const nb_pers_Voyage = document.getElementById('nb_personnes_voyage');
    const priceDynamicElement = document.getElementById('prix-total-dynamique');

    const checkboxCombat = document.getElementById('combat');
    const nb_personnes_combat = document.getElementById('nb_personnes_combat');

    const checkboxChasse = document.getElementById('chasse');
    const nb_personnes_chasse = document.getElementById('nb_personnes_chasse');

    const checkboxMur = document.getElementById('mur');
    const nb_personnes_mur = document.getElementById('nb_personnes_mur');

    const checkboxTournoi = document.getElementById('tournoi');
    const nb_personnes_tournoi = document.getElementById('nb_personnes_tournoi');

    const checkboxTrone = document.getElementById('trone');
    const nb_personnes_trone = document.getElementById('nb_personnes_trone');

    const checkboxFleuve = document.getElementById('fleuve');
    const nb_personnes_fleuve = document.getElementById('nb_personnes_fleuve');

    const checkboxDragons = document.getElementById('dragons');
    const nb_personnes_dragons = document.getElementById('nb_personnes_dragons');

    const checkboxGladiateur = document.getElementById('gladiateur');
    const nb_personnes_gladiateur = document.getElementById('nb_personnes_gladiateur');
    
    const checkboxMarche = document.getElementById('marche');
    const nb_personnes_marche = document.getElementById('nb_personnes_marche');

    const activityInputs = [
        nb_personnes_combat,
        nb_personnes_chasse,
        nb_personnes_mur,
        nb_personnes_tournoi,
        nb_personnes_trone,
        nb_personnes_fleuve,
        nb_personnes_dragons,
        nb_personnes_gladiateur,
        nb_personnes_marche
    ];

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

    if (nb_pers_Voyage) {
        nb_pers_Voyage.addEventListener('input', updateMaxPersonnes);
        updateMaxPersonnes(); 
    }
    activityInputs.forEach(input => {
        if (input) {
            input.addEventListener('input', function() {
                const maxPersonnes = parseInt(nb_pers_Voyage.value);
                if (parseInt(this.value) > maxPersonnes) {
                    this.value = maxPersonnes;
                }
                updatePrice();
            });
        }
    });
  
    function updatePrice() {
      let nbVoyage = nb_pers_Voyage ? parseInt(nb_pers_Voyage.value) : 1;
      let travelCost = basePrice * nbVoyage;
      
      let totalActivityCost = 0;
      if (checkboxCombat && checkboxCombat.checked) {
        let nbCombat = nb_personnes_combat ? parseInt(nb_personnes_combat.value) : 0;
        totalActivityCost += nbCombat * activitePrix.combat;
      }
      if (checkboxChasse && checkboxChasse.checked) {
        let nbChasse = nb_personnes_chasse ? parseInt(nb_personnes_chasse.value) : 0;
        totalActivityCost += nbChasse * activitePrix.chasse;
      }
      if (checkboxMur && checkboxMur.checked) {
        let nbMur = nb_personnes_mur ? parseInt(nb_personnes_mur.value) : 0;
        totalActivityCost += nbMur * activitePrix.mur;
      }
      
      if (checkboxTournoi && checkboxTournoi.checked) {
        let nbTournoi = nb_personnes_tournoi ? parseInt(nb_personnes_tournoi.value) : 0;
        totalActivityCost += nbTournoi * activitePrix.tournoi;
      }
      if (checkboxTrone && checkboxTrone.checked) {
        let nbTrone = nb_personnes_trone ? parseInt(nb_personnes_trone.value) : 0;
        totalActivityCost += nbTrone * activitePrix.trone;
      }
      if (checkboxFleuve && checkboxFleuve.checked) {
        let nbFleuve = nb_personnes_fleuve ? parseInt(nb_personnes_fleuve.value) : 0;
        totalActivityCost += nbFleuve * activitePrix.fleuve;
      }
      
      if (checkboxDragons && checkboxDragons.checked) {
        let nbDragons = nb_personnes_dragons ? parseInt(nb_personnes_dragons.value) : 0;
        totalActivityCost += nbDragons * activitePrix.dragons;
      }
      if (checkboxGladiateur && checkboxGladiateur.checked) {
        let nbGladiateur = nb_personnes_gladiateur ? parseInt(nb_personnes_gladiateur.value) : 0;
        totalActivityCost += nbGladiateur * activitePrix.gladiateur;
      }
      if (checkboxMarche && checkboxMarche.checked) {
        let nbMarche = nb_personnes_marche ? parseInt(nb_personnes_marche.value) : 0;
        totalActivityCost += nbMarche * activitePrix.marche;
      }
      
      let totalPrice = travelCost + totalActivityCost;
      priceDynamicElement.textContent = "Prix estimé : " + new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalPrice) + "€";
    }
    
    if (nb_pers_Voyage) nb_pers_Voyage.addEventListener('input', updatePrice);
    
    if (checkboxCombat) checkboxCombat.addEventListener('change', updatePrice);
    if (nb_personnes_combat) nb_personnes_combat.addEventListener('change', updatePrice);
    
    if (checkboxChasse) checkboxChasse.addEventListener('change', updatePrice);
    if (nb_personnes_chasse) nb_personnes_chasse.addEventListener('change', updatePrice);
    
    if (checkboxMur) checkboxMur.addEventListener('change', updatePrice);
    if (nb_personnes_mur) nb_personnes_mur.addEventListener('change', updatePrice);
  
    if (checkboxTournoi) checkboxTournoi.addEventListener('change', updatePrice);
    if (nb_personnes_tournoi) nb_personnes_tournoi.addEventListener('change', updatePrice);
    
    if (checkboxTrone) checkboxTrone.addEventListener('change', updatePrice);
    if (nb_personnes_trone) nb_personnes_trone.addEventListener('change', updatePrice);
    
    if (checkboxFleuve) checkboxFleuve.addEventListener('change', updatePrice);
    if (nb_personnes_fleuve) nb_personnes_fleuve.addEventListener('change', updatePrice);

    if (checkboxDragons) checkboxDragons.addEventListener('change', updatePrice);
    if (nb_personnes_dragons) nb_personnes_dragons.addEventListener('change', updatePrice);
    
    if (checkboxGladiateur) checkboxGladiateur.addEventListener('change', updatePrice);
    if (nb_personnes_gladiateur) nb_personnes_gladiateur.addEventListener('change', updatePrice);
    
    if (checkboxMarche) checkboxMarche.addEventListener('change', updatePrice);
    if (nb_personnes_marche) nb_personnes_marche.addEventListener('change', updatePrice);
    
    updatePrice();
  });