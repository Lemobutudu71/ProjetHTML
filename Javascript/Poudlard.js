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
  
    const isVip = document.querySelector('.Page-Accueil2-text').getAttribute('data-vip') === 'true';
    const vipReduction = isVip ? 0.9 : 1;
    
    // Appliquer la réduction VIP aux prix si nécessaire
    if (isVip) {
        basePrice = basePrice * vipReduction;
        for (let key in activitePrix) {
            activitePrix[key] = activitePrix[key] * vipReduction;
        }
    }
    
    const nb_pers_Voyage = document.getElementById('nb_personnes_voyage');
    const priceDynamicElement = document.getElementById('prix-total-dynamique');

    const checkboxSorts = document.getElementById('sorts');
    const nb_personnes_sort = document.getElementById('nb_personnes_sort');

    const checkboxQuidditch = document.getElementById('quidditch');
    const nb_personnes_quid = document.getElementById('nb_personnes_quid');

    const checkboxHyppogriffe = document.getElementById('hyppogriffe');
    const nb_personnes_hy = document.getElementById('nb_personnes_hy');

    const checkboxZonko = document.getElementById('zonko');
    const nb_personnes_visite = document.getElementById('nb_personnes_visite');

    const checkboxDegustation = document.getElementById('degustation');
    const nb_personnes_degustation = document.getElementById('nb_personnes_degustation');
    
    const checkboxHoneydukes = document.getElementById('honeydukes');
    const nb_personnes_honeydukes = document.getElementById('nb_personnes_honeydukes');

    const activityInputs = [
        nb_personnes_sort,
        nb_personnes_quid,
        nb_personnes_hy,
        nb_personnes_visite,
        nb_personnes_degustation,
        nb_personnes_honeydukes
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
        if (checkboxSorts && checkboxSorts.checked) {
            let nbSort = nb_personnes_sort ? parseInt(nb_personnes_sort.value) : 0;
            totalActivityCost += nbSort * activitePrix.sorts;
        }
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
        
        let totalPrice = (travelCost + totalActivityCost) * vipReduction;
        
        let priceText = "Prix estimé : " + new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalPrice) + "€";
        
        priceDynamicElement.textContent = priceText;
    }
    
    if (nb_pers_Voyage) nb_pers_Voyage.addEventListener('input', updatePrice);
    
    if (checkboxSorts) checkboxSorts.addEventListener('change', updatePrice);
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
    
    updatePrice();
});