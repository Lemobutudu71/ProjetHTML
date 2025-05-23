// Attend que le contenu HTML de la page soit complètement chargé avant d'exécuter le script.
document.addEventListener('DOMContentLoaded', function() {
    // Récupère l'élément select pour le filtre de prix.
    const prixFiltre = document.getElementById('prix-filtre');
    // Récupère le conteneur où les photos des voyages seront affichées.
    const listePhotos = document.querySelector('.ListePhotos');
    // Récupère le conteneur pour les contrôles de pagination.
    const paginationContainer = document.querySelector('.pagination');
    
    // Définit le nombre de voyages à afficher par page.
    let voyagesParPage = 4;
    // Initialise la page actuelle à 1 (première page).
    let pageActuelle = 1;
    // Stocke le dernier critère de tri appliqué (ascendant 'asc' ou descendant 'desc'). Initialisé à vide.
    let dernierTri = ''; 


    // Vérifie si la variable globale `allVoyages` est définie. Cette variable doit contenir la liste de tous les voyages.
    if (typeof allVoyages === 'undefined') {
        // Affiche une erreur dans la console si `allVoyages` n'est pas définie, car le script ne peut pas fonctionner sans.
        console.error('La variable allVoyages n\'est pas définie');
        return; // Arrête l'exécution du script.
    }

    // Initialise `voyagesFiltres` comme une copie de `allVoyages`. 
    // Cette variable contiendra les voyages après application des filtres et du tri.
    let voyagesFiltres = [...allVoyages]; 

    
    // Fonction pour appliquer les filtres de transport, logement et monde.
    const appliquerFiltres = () => {
        // Récupère les valeurs sélectionnées pour les filtres de transport, logement et monde.
        const transport = document.getElementById('transport').value;
        const logement = document.getElementById('logement').value;
        const monde = document.getElementById('monde').value;

        // Filtre le tableau `allVoyages`.
        voyagesFiltres = allVoyages.filter(voyage => {
            // Retourne true si le voyage correspond à tous les filtres sélectionnés.
            // Si un filtre n'est pas sélectionné (valeur vide), il n'est pas pris en compte.
            return (
                (!transport || voyage.transport.includes(transport)) &&
                (!logement || voyage.logement.includes(logement)) &&
                (!monde || voyage.monde.includes(monde))
            );
        });

        // Applique à nouveau le dernier tri de prix si un tri a été effectué précédemment.
        if (dernierTri === 'asc') {
            voyagesFiltres.sort((a, b) => a.prix - b.prix); // Trie par prix croissant.
        } 
        else if (dernierTri === 'desc') {
            voyagesFiltres.sort((a, b) => b.prix - a.prix); // Trie par prix décroissant.
        }

        pageActuelle = 1;  // Réinitialise la page actuelle à la première page après avoir appliqué les filtres.
        afficherVoyages(voyagesFiltres); // Met à jour l'affichage des voyages.
        genererPagination(voyagesFiltres); // Met à jour les contrôles de pagination.
    };

    
    // Ajoute un écouteur d'événement 'change' à chaque menu déroulant (select) dans le conteneur des filtres.
    document.querySelectorAll('.filters-container select').forEach(select => {
        select.addEventListener('change', function() {
            // Lorsque la valeur d'un filtre change, réapplique tous les filtres.
            appliquerFiltres();
        });
    });

  
    // Ajoute un écouteur d'événement 'change' au filtre de prix.
    prixFiltre.addEventListener('change', function() {
        // Récupère l'ordre de tri sélectionné ('asc' ou 'desc').
        const ordre = this.value;
        
        dernierTri = ordre; // Met à jour le dernier critère de tri.
        if (ordre === 'asc') {
            voyagesFiltres.sort((a, b) => a.prix - b.prix);  // Trie par prix croissant.
        } else if (ordre === 'desc') {
            voyagesFiltres.sort((a, b) => b.prix - a.prix);  // Trie par prix décroissant.
        }
        pageActuelle = 1; // Réinitialise à la première page.
        afficherVoyages(voyagesFiltres); // Met à jour l'affichage.
        genererPagination(voyagesFiltres); // Met à jour la pagination.
    });

    // Fonction pour afficher les voyages de la page actuelle.
    // voyages: Le tableau des voyages (filtrés et triés) à paginer.
    function afficherVoyages(voyages) {
        // Calcule l'indice de début et de fin pour les voyages de la page actuelle.
        const debut = (pageActuelle - 1) * voyagesParPage;
        const fin = debut + voyagesParPage;
        // Extrait les voyages pour la page actuelle.
        const voyagesPage = voyages.slice(debut, fin);
        
      
        listePhotos.innerHTML = ''; // Vide le conteneur des photos avant d'ajouter les nouveaux éléments.
        
       
        // Parcourt les voyages de la page actuelle et crée le HTML pour chacun.
        voyagesPage.forEach(voyage => {
            // Formatte le prix du voyage avec deux décimales et le symbole euro.
            const prix = new Intl.NumberFormat('fr-FR', { 
                minimumFractionDigits: 2, 
                maximumFractionDigits: 2 
            }).format(voyage.prix);
            
            // Crée le HTML pour un voyage.
            const voyageHTML = `
                <div class="gallerie-img">
                    <a href="${voyage.lien}">
                        <img src="${voyage.image}" alt="${voyage.nom}">
                        <div class="Lieux"><p>${voyage.nom}</p></div>
                        <div class="Prix"><p>À partir de ${prix}€</p></div>
                    </a>
                </div>
            `;
            // Ajoute le HTML du voyage à la fin du conteneur `listePhotos`.
            listePhotos.insertAdjacentHTML('beforeend', voyageHTML);
        });
    }

    // Fonction pour générer les contrôles de pagination.
    // voyages: Le tableau total des voyages (filtrés et triés) pour calculer le nombre de pages.
    function genererPagination(voyages) {
        // Calcule le nombre total de pages nécessaires.
        const totalPages = Math.ceil(voyages.length / voyagesParPage);
        paginationContainer.innerHTML = ''; // Vide le conteneur de pagination.
        
        // Si la page actuelle n'est pas la première, ajoute un bouton "Précédent".
        if (pageActuelle > 1) {
            const prevButton = document.createElement('a');
            prevButton.href = '#'; // Lien factice, géré par JavaScript.
            prevButton.textContent = 'Précédent';
            prevButton.addEventListener('click', (e) => {
                e.preventDefault(); // Empêche le comportement par défaut du lien.
                pageActuelle--; // Passe à la page précédente.
                afficherVoyages(voyages); // Met à jour l'affichage.
                genererPagination(voyages); // Regénère la pagination (pour l'état actif du bouton).
            });
            paginationContainer.appendChild(prevButton);
        }

        // Crée un lien pour chaque page.
        for (let i = 1; i <= totalPages; i++) {
            const pageLink = document.createElement('a');
            pageLink.href = '#';
            pageLink.textContent = i; // Numéro de la page.
            // Si le numéro de page correspond à la page actuelle, ajoute la classe 'active'.
            if (i === pageActuelle) {
                pageLink.classList.add('active');
            }
            pageLink.addEventListener('click', (e) => {
                e.preventDefault();
                pageActuelle = i; // Met à jour la page actuelle.
                afficherVoyages(voyages);
                genererPagination(voyages);
            });
            paginationContainer.appendChild(pageLink);
        }

        // Si la page actuelle n'est pas la dernière, ajoute un bouton "Suivant".
        if (pageActuelle < totalPages) {
            const nextButton = document.createElement('a');
            nextButton.href = '#';
            nextButton.textContent = 'Suivant';
            nextButton.addEventListener('click', (e) => {
                e.preventDefault();
                pageActuelle++; // Passe à la page suivante.
                afficherVoyages(voyages);
                genererPagination(voyages);
            });
            paginationContainer.appendChild(nextButton);
        }
    }

    // Affiche initialement les voyages (tous, car aucun filtre n'est appliqué au début).
    afficherVoyages(voyagesFiltres);
    // Génère initialement la pagination.
    genererPagination(voyagesFiltres);
});
