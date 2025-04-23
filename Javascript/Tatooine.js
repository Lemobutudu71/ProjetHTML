document.addEventListener('DOMContentLoaded', function(){
  
    const basePrice = 3578;
   
    const activitePrix = {
      jedi: 45,
      speeder: 55,
      palais_jabba: 20,
      tie_fighter: 70,
      tir: 20,
      sith: 18
    };
  
  
    const nb_pers_Voyage = document.getElementById('nb_personnes_voyage');
    const priceDynamicElement = document.getElementById('prix-total-dynamique');
  
   
    const checkboxJedi = document.getElementById('jedi');
    const nb_personnes_jedi = document.getElementById('nb_personnes_jedi');
    
    const checkboxSpeeder = document.getElementById('speeder');
    const nb_personnes_speeder = document.getElementById('nb_personnes_speeder');
    
    const checkboxPalaisJabba = document.getElementById('palais_jabba');
    const nb_personnes_palaisjabba = document.getElementById('nb_personnes_palaisjabba');

    const checkboxTieFighter = document.getElementById('tie_fighter');
    const nb_personnes_tie = document.getElementById('nb_personnes_tie');
    
    const checkboxTir = document.getElementById('tir');
    const nb_personnes_tir = document.getElementById('nb_personnes_tir');
    
    const checkboxSith = document.getElementById('sith');
    const nb_personnes_sith = document.getElementById('nb_personnes_sith');
  
    function updatePrice() {
      
      let nbVoyage = nb_pers_Voyage ? parseInt(nb_pers_Voyage.value) : 1;
      let travelCost = basePrice * nbVoyage;
      
      let totalActivityCost = 0;
    
      if (checkboxJedi && checkboxJedi.checked) {
        let nbJedi = nb_personnes_jedi ? parseInt(nb_personnes_jedi.value) : 0;
        totalActivityCost += nbJedi * activitePrix.jedi;
      }
      if (checkboxSpeeder && checkboxSpeeder.checked) {
        let nbSpeeder = nb_personnes_speeder ? parseInt(nb_personnes_speeder.value) : 0;
        totalActivityCost += nbSpeeder * activitePrix.speeder;
      }
      if (checkboxPalaisJabba && checkboxPalaisJabba.checked) {
        let nbPalais = nb_personnes_palaisjabba ? parseInt(nb_personnes_palaisjabba.value) : 0;
        totalActivityCost += nbPalais * activitePrix.palais_jabba;
      }
      if (checkboxTieFighter && checkboxTieFighter.checked) {
        let nbTie = nb_personnes_tie ? parseInt(nb_personnes_tie.value) : 0;
        totalActivityCost += nbTie * activitePrix.tie_fighter;
      }
      if (checkboxTir && checkboxTir.checked) {
        let nbTir = nb_personnes_tir ? parseInt(nb_personnes_tir.value) : 0;
        totalActivityCost += nbTir * activitePrix.tir;
      }
      if (checkboxSith && checkboxSith.checked) {
        let nbSith = nb_personnes_sith ? parseInt(nb_personnes_sith.value) : 0;
        totalActivityCost += nbSith * activitePrix.sith;
      }
      
      let totalPrice = travelCost + totalActivityCost;
      priceDynamicElement.textContent = "Prix estimé : " + new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalPrice) + "€";
    }

    if (nb_pers_Voyage) nb_pers_Voyage.addEventListener('change', updatePrice);
    
    if (checkboxJedi) checkboxJedi.addEventListener('change', updatePrice);
    if (nb_personnes_jedi) nb_personnes_jedi.addEventListener('change', updatePrice);
  
    if (checkboxSpeeder) checkboxSpeeder.addEventListener('change', updatePrice);
    if (nb_personnes_speeder) nb_personnes_speeder.addEventListener('change', updatePrice);
  
    if (checkboxPalaisJabba) checkboxPalaisJabba.addEventListener('change', updatePrice);
    if (nb_personnes_palaisjabba) nb_personnes_palaisjabba.addEventListener('change', updatePrice);
  
    if (checkboxTieFighter) checkboxTieFighter.addEventListener('change', updatePrice);
    if (nb_personnes_tie) nb_personnes_tie.addEventListener('change', updatePrice);
  
    if (checkboxTir) checkboxTir.addEventListener('change', updatePrice);
    if (nb_personnes_tir) nb_personnes_tir.addEventListener('change', updatePrice);
  
    if (checkboxSith) checkboxSith.addEventListener('change', updatePrice);
    if (nb_personnes_sith) nb_personnes_sith.addEventListener('change', updatePrice);
  
    updatePrice();
  });