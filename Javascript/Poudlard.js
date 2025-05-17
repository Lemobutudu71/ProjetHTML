document.addEventListener('DOMContentLoaded', function(){
  
    const basePrice = 5000;
    
    const activitePrix = {
      quidditch: 20,
      potions: 35,
      defense: 30,
      divination: 12,
      herbologie: 30,
      histoire: 50,
      astronomie: 100,
      sortileges: 77,
      creatures: 10
    };
  
    const isVip = document.querySelector('.Page-Accueil2-text').getAttribute('data-vip') === 'true';
    const vipReduction = isVip ? 0.9 : 1;
    
    const nb_pers_Voyage = document.getElementById('nb_personnes_voyage');
    const priceDynamicElement = document.getElementById('prix-total-dynamique');

    const checkboxQuidditch = document.getElementById('quidditch');
    const nb_personnes_quidditch = document.getElementById('nb_personnes_quidditch');

    const checkboxPotions = document.getElementById('potions');
    const nb_personnes_potions = document.getElementById('nb_personnes_potions');

    const checkboxDefense = document.getElementById('defense');
    const nb_personnes_defense = document.getElementById('nb_personnes_defense');

    const checkboxDivination = document.getElementById('divination');
    const nb_personnes_divination = document.getElementById('nb_personnes_divination');

    const checkboxHerbologie = document.getElementById('herbologie');
    const nb_personnes_herbologie = document.getElementById('nb_personnes_herbologie');

    const checkboxHistoire = document.getElementById('histoire');
    const nb_personnes_histoire = document.getElementById('nb_personnes_histoire');

    const checkboxAstronomie = document.getElementById('astronomie');
    const nb_personnes_astronomie = document.getElementById('nb_personnes_astronomie');

    const checkboxSortileges = document.getElementById('sortileges');
    const nb_personnes_sortileges = document.getElementById('nb_personnes_sortileges');
    
    const checkboxCreatures = document.getElementById('creatures');
    const nb_personnes_creatures = document.getElementById('nb_personnes_creatures');

    const activityInputs = [
        nb_personnes_quidditch,
        nb_personnes_potions,
        nb_personnes_defense,
        nb_personnes_divination,
        nb_personnes_herbologie,
        nb_personnes_histoire,
        nb_personnes_astronomie,
        nb_personnes_sortileges,
        nb_personnes_creatures
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
        if (checkboxQuidditch && checkboxQuidditch.checked) {
            let nbQuidditch = nb_personnes_quidditch ? parseInt(nb_personnes_quidditch.value) : 0;
            totalActivityCost += nbQuidditch * activitePrix.quidditch;
        }
        if (checkboxPotions && checkboxPotions.checked) {
            let nbPotions = nb_personnes_potions ? parseInt(nb_personnes_potions.value) : 0;
            totalActivityCost += nbPotions * activitePrix.potions;
        }
        if (checkboxDefense && checkboxDefense.checked) {
            let nbDefense = nb_personnes_defense ? parseInt(nb_personnes_defense.value) : 0;
            totalActivityCost += nbDefense * activitePrix.defense;
        }
        
        if (checkboxDivination && checkboxDivination.checked) {
            let nbDivination = nb_personnes_divination ? parseInt(nb_personnes_divination.value) : 0;
            totalActivityCost += nbDivination * activitePrix.divination;
        }
        if (checkboxHerbologie && checkboxHerbologie.checked) {
            let nbHerbologie = nb_personnes_herbologie ? parseInt(nb_personnes_herbologie.value) : 0;
            totalActivityCost += nbHerbologie * activitePrix.herbologie;
        }
        if (checkboxHistoire && checkboxHistoire.checked) {
            let nbHistoire = nb_personnes_histoire ? parseInt(nb_personnes_histoire.value) : 0;
            totalActivityCost += nbHistoire * activitePrix.histoire;
        }
        
        if (checkboxAstronomie && checkboxAstronomie.checked) {
            let nbAstronomie = nb_personnes_astronomie ? parseInt(nb_personnes_astronomie.value) : 0;
            totalActivityCost += nbAstronomie * activitePrix.astronomie;
        }
        if (checkboxSortileges && checkboxSortileges.checked) {
            let nbSortileges = nb_personnes_sortileges ? parseInt(nb_personnes_sortileges.value) : 0;
            totalActivityCost += nbSortileges * activitePrix.sortileges;
        }
        if (checkboxCreatures && checkboxCreatures.checked) {
            let nbCreatures = nb_personnes_creatures ? parseInt(nb_personnes_creatures.value) : 0;
            totalActivityCost += nbCreatures * activitePrix.creatures;
        }
        
        let totalPrice = (travelCost + totalActivityCost) * vipReduction;
        
        let priceText = "Prix estimé : " + new Intl.NumberFormat('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(totalPrice) + "€";
        
        priceDynamicElement.textContent = priceText;
    }
    
    if (nb_pers_Voyage) nb_pers_Voyage.addEventListener('input', updatePrice);
    
    if (checkboxQuidditch) checkboxQuidditch.addEventListener('change', updatePrice);
    if (nb_personnes_quidditch) nb_personnes_quidditch.addEventListener('change', updatePrice);
    
    if (checkboxPotions) checkboxPotions.addEventListener('change', updatePrice);
    if (nb_personnes_potions) nb_personnes_potions.addEventListener('change', updatePrice);
    
    if (checkboxDefense) checkboxDefense.addEventListener('change', updatePrice);
    if (nb_personnes_defense) nb_personnes_defense.addEventListener('change', updatePrice);
  
    if (checkboxDivination) checkboxDivination.addEventListener('change', updatePrice);
    if (nb_personnes_divination) nb_personnes_divination.addEventListener('change', updatePrice);
    
    if (checkboxHerbologie) checkboxHerbologie.addEventListener('change', updatePrice);
    if (nb_personnes_herbologie) nb_personnes_herbologie.addEventListener('change', updatePrice);
    
    if (checkboxHistoire) checkboxHistoire.addEventListener('change', updatePrice);
    if (nb_personnes_histoire) nb_personnes_histoire.addEventListener('change', updatePrice);

    if (checkboxAstronomie) checkboxAstronomie.addEventListener('change', updatePrice);
    if (nb_personnes_astronomie) nb_personnes_astronomie.addEventListener('change', updatePrice);
    
    if (checkboxSortileges) checkboxSortileges.addEventListener('change', updatePrice);
    if (nb_personnes_sortileges) nb_personnes_sortileges.addEventListener('change', updatePrice);
    
    if (checkboxCreatures) checkboxCreatures.addEventListener('change', updatePrice);
    if (nb_personnes_creatures) nb_personnes_creatures.addEventListener('change', updatePrice);
    
    updatePrice();
});