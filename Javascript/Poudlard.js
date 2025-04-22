document.addEventListener('DOMContentLoaded', function(){
    const basePrice = 3666;
    const activitePrix = {
      sorts: 25,
      quidditch: 35,
      hyppogriffe: 40,
      zonko: 15,
      degustation: 20,
      honeydukes: 18
    };
  
    const nb_pers_Voyage = document.getElementById('nb_personnes_voyage');
  

    const checkboxSorts = document.getElementById('sorts');
    const selectNbSort = document.getElementById('nb_personnes_sort');
    const checkboxQuidditch = document.getElementById('quidditch');
    const selectNbQuid = document.getElementById('nb_personnes_quid');
    const checkboxHyppogriffe = document.getElementById('hyppogriffe');
    const selectNbHy = document.getElementById('nb_personnes_hy');
    const checkboxZonko = document.getElementById('zonko');
    const selectNbZonko = document.getElementById('nb_personnes_visite');
    const checkboxDegustation = document.getElementById('degustation');
    const selectNbDegustation = document.getElementById('nb_personnes_degustation');
    const checkboxHoneydukes = document.getElementById('honeydukes');
    const selectNbHoneydukes = document.getElementById('nb_personnes_honeydukes');
  
    const priceDynamicElement = document.getElementById('prix-total-dynamique');
  

    function updatePrice() {

      let nbVoyage = nb_pers_Voyage ? parseInt(nb_pers_Voyage.value) : 1;
      let travelCost = basePrice * nbVoyage;

      let totalActivityCost = 0;
      if (checkboxSorts && checkboxSorts.checked) {
        let nbSort = selectNbSort ? parseInt(selectNbSort.value) : 0;
        totalActivityCost += nbSort * activitePrix.sorts;
      }
      if (checkboxQuidditch && checkboxQuidditch.checked) {
        let nbQuid = selectNbQuid ? parseInt(selectNbQuid.value) : 0;
        totalActivityCost += nbQuid * activitePrix.quidditch;
      }
      if (checkboxHyppogriffe && checkboxHyppogriffe.checked) {
        let nbHy = selectNbHy ? parseInt(selectNbHy.value) : 0;
        totalActivityCost += nbHy * activitePrix.hyppogriffe;
      }
      if (checkboxZonko && checkboxZonko.checked) {
        let nbZonko = selectNbZonko ? parseInt(selectNbZonko.value) : 0;
        totalActivityCost += nbZonko * activitePrix.zonko;
      }
      if (checkboxDegustation && checkboxDegustation.checked) {
        let nbDegust = selectNbDegustation ? parseInt(selectNbDegustation.value) : 0;
        totalActivityCost += nbDegust * activitePrix.degustation;
      }
      if (checkboxHoneydukes && checkboxHoneydukes.checked) {
        let nbHoney = selectNbHoneydukes ? parseInt(selectNbHoneydukes.value) : 0;
        totalActivityCost += nbHoney * activitePrix.honeydukes;
      }
  
      let totalPrice = travelCost + totalActivityCost;
      priceDynamicElement.textContent = "Prix estimé : " + new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalPrice) + "€";
    }
    if (nb_pers_Voyage) nb_pers_Voyage.addEventListener('change', updatePrice);
    
    if (checkboxSorts) checkboxSorts.addEventListener('change', updatePrice);
    if (selectNbSort) selectNbSort.addEventListener('change', updatePrice);
  
    if (checkboxQuidditch) checkboxQuidditch.addEventListener('change', updatePrice);
    if (selectNbQuid) selectNbQuid.addEventListener('change', updatePrice);
  
    if (checkboxHyppogriffe) checkboxHyppogriffe.addEventListener('change', updatePrice);
    if (selectNbHy) selectNbHy.addEventListener('change', updatePrice);
  
    if (checkboxZonko) checkboxZonko.addEventListener('change', updatePrice);
    if (selectNbZonko) selectNbZonko.addEventListener('change', updatePrice);
  
    if (checkboxDegustation) checkboxDegustation.addEventListener('change', updatePrice);
    if (selectNbDegustation) selectNbDegustation.addEventListener('change', updatePrice);
  
    if (checkboxHoneydukes) checkboxHoneydukes.addEventListener('change', updatePrice);
    if (selectNbHoneydukes) selectNbHoneydukes.addEventListener('change', updatePrice);
  
    updatePrice();
  });