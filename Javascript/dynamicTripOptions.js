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
}); 