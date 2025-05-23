document.addEventListener('DOMContentLoaded', () => {
    if (typeof tripDataForJS === 'undefined') {
        console.error('tripDataForJS is not defined. Ensure PHP is passing the data correctly.');
        return;
    }

    const { destination, etapes, etapesOptions, currentTripOptions, nbPersonnesVoyage } = tripDataForJS;

    if (!etapes || etapes.length === 0) {
        console.warn('No etapes defined for this trip.');
        return;
    }

    etapes.forEach(etapeName => {
        const cleanEtapeName = etapeName.toLowerCase().replace(/\s+/g, '_').replace(/[^a-z0-9_]/g, '');
        const containerId = `activites-container-${cleanEtapeName}`;
        const container = document.getElementById(containerId);

        if (!container) {
            console.warn(`Container with ID ${containerId} not found for etape ${etapeName}.`);
            return;
        }

        // Clear "Loading..." text
        const loadingText = container.querySelector('.loading-text');
        if (loadingText) {
            loadingText.remove();
        }

        const availableOptionsForDestEtape = etapesOptions?.[destination]?.[etapeName]?.activites;
        if (!availableOptionsForDestEtape) {
            container.innerHTML += '<p>Aucune activité disponible pour cette étape.</p>';
            return;
        }

        const currentEtapeActivitiesKey = `activites_${cleanEtapeName}`;
        const alreadySelectedActivities = currentTripOptions?.[currentEtapeActivitiesKey] || [];
        const activityPrices = currentTripOptions?.activite_prix || {};

        let activitiesHtml = '';
        let hasAvailableNewActivities = false;

        for (const activityCode in availableOptionsForDestEtape) {
            if (availableOptionsForDestEtape.hasOwnProperty(activityCode)) {
                if (!alreadySelectedActivities.includes(activityCode)) {
                    hasAvailableNewActivities = true;
                    const activityDisplayName = availableOptionsForDestEtape[activityCode];
                    const activityPrice = activityPrices[activityCode] || 0;
                    const priceFormatted = parseFloat(activityPrice).toLocaleString('fr-FR', { style: 'currency', currency: 'EUR' });

                    activitiesHtml += `
                        <div class="options-group">
                            <label>
                                <input type="checkbox" name="activites_${cleanEtapeName}[]" value="${activityCode}">
                                ${escapeHtml(activityDisplayName)} 
                                (${priceFormatted})
                            </label>
                            <input type="number" name="nb_personnes[${activityCode}]" value="1" min="1" max="${nbPersonnesVoyage || 1}" aria-label="Nombre de personnes pour ${escapeHtml(activityDisplayName)}">
                        </div>
                    `;
                }
            }
        }

        if (!hasAvailableNewActivities) {
            activitiesHtml = '<p>Toutes les activités disponibles pour cette étape ont déjà été sélectionnées.</p>';
        }

        container.innerHTML += activitiesHtml; // Append new activities
    });

    // Helper function to escape HTML special characters to prevent XSS
    function escapeHtml(unsafe) {
        if (typeof unsafe !== 'string') return '';
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Récupère les éléments <select> pour la gare de départ et la gare de retour.
    const departureStationSelect = document.getElementById('departure_station');
    const returningStationSelect = document.getElementById('returning_station');

    // Vérifie si les deux éléments <select> existent avant de continuer.
    if (!departureStationSelect || !returningStationSelect) {
        // Si l'un ou l'autre n'existe pas, affiche une erreur dans la console et arrête le script.
        console.error('Les éléments select de gare de départ ou de retour sont introuvables.');
        return;
    }

    // Stocke les options initiales de la gare de retour. 
    // Cela est utilisé pour restaurer toutes les options si nécessaire.
    let initialReturningOptions = [];
    // Parcourt toutes les options initiales du <select> de la gare de retour.
    for (let i = 0; i < returningStationSelect.options.length; i++) {
        // Ajoute chaque option (valeur et texte) au tableau initialReturningOptions.
        initialReturningOptions.push({
            value: returningStationSelect.options[i].value,
            text: returningStationSelect.options[i].text
        });
    }

    // Fonction pour mettre à jour les options de la gare de retour.
    function updateReturningStations() {
        // Récupère la valeur de la gare de départ sélectionnée.
        const selectedDepartureStation = departureStationSelect.value;
        // Mémorise la valeur actuellement sélectionnée pour la gare de retour (si elle existe).
        const currentReturningStationValue = returningStationSelect.value;

        // Vide les options actuelles du <select> de la gare de retour.
        returningStationSelect.innerHTML = '';

        // Filtre les options initiales pour ne garder que celles qui sont différentes de la gare de départ sélectionnée.
        // Ou si l'option a une valeur vide (souvent utilisée pour "Choisissez une option").
        const filteredOptions = initialReturningOptions.filter(option => option.value === "" || option.value !== selectedDepartureStation);

        // Ajoute les options filtrées au <select> de la gare de retour.
        filteredOptions.forEach(optionData => {
            const option = document.createElement('option');
            option.value = optionData.value;
            option.textContent = optionData.text;
            // Si l'option de données correspond à la valeur précédemment sélectionnée pour la gare de retour,
            // et que cette valeur n'est pas la même que la gare de départ nouvellement sélectionnée,
            // alors cette option est marquée comme sélectionnée.
            if (optionData.value === currentReturningStationValue && currentReturningStationValue !== selectedDepartureStation) {
                option.selected = true;
            }
            returningStationSelect.appendChild(option);
        });
        
        // Si, après le filtrage, la gare de retour sélectionnée est la même que la gare de départ,
        // ou si aucune option n'est sélectionnée (et qu'il y a des options disponibles),
        // alors on tente de sélectionner la première option valide.
        if (returningStationSelect.value === selectedDepartureStation || (returningStationSelect.selectedIndex === -1 && returningStationSelect.options.length > 0)) {
            // On cherche la première option qui n'est pas vide et qui n'est pas la gare de départ.
            let newSelectionMade = false;
            for (let i = 0; i < returningStationSelect.options.length; i++) {
                if (returningStationSelect.options[i].value !== "" && returningStationSelect.options[i].value !== selectedDepartureStation) {
                    returningStationSelect.selectedIndex = i;
                    newSelectionMade = true;
                    break;
                }
            }
            // Si aucune option valide n'a été trouvée (par exemple, toutes les options restantes sont vides ou la gare de départ),
            // et si la première option est une option vide "Choisissez...", on la sélectionne.
            if (!newSelectionMade && returningStationSelect.options.length > 0 && returningStationSelect.options[0].value === "") {
                returningStationSelect.selectedIndex = 0;
            }
        }
    }

    // Ajoute un écouteur d'événement 'change' pour appeler updateReturningStations lorsque la sélection de la gare de départ change.
    departureStationSelect.addEventListener('change', updateReturningStations);
    // Appelle la fonction une fois au chargement pour initialiser les options de la gare de retour 
    // en fonction de la sélection initiale de la gare de départ.
    updateReturningStations();
}); 