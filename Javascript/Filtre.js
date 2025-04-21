document.addEventListener('DOMContentLoaded', function() {
    const prixFiltre = document.getElementById('prix-filtre');
    const listePhotos = document.querySelector('.ListePhotos');
    const paginationContainer = document.querySelector('.pagination');
    
    let voyagesParPage = 4;
    let pageActuelle = 1;
    let dernierTri = ''; 


    if (typeof allVoyages === 'undefined') {
        console.error('La variable allVoyages n\'est pas définie');
        return;
    }

    let voyagesFiltres = [...allVoyages]; 

    
    const appliquerFiltres = () => {
        const transport = document.getElementById('transport').value;
        const logement = document.getElementById('logement').value;
        const monde = document.getElementById('monde').value;

        voyagesFiltres = allVoyages.filter(voyage => {
            return (
                (!transport || voyage.transport.includes(transport)) &&
                (!logement || voyage.logement.includes(logement)) &&
                (!monde || voyage.monde.includes(monde))
            );
        });

        if (dernierTri === 'asc') {
            voyagesFiltres.sort((a, b) => a.prix - b.prix); 
        } 
        else if (dernierTri === 'desc') {
            voyagesFiltres.sort((a, b) => b.prix - a.prix); 
        }

        pageActuelle = 1;  
        afficherVoyages(voyagesFiltres);
        genererPagination(voyagesFiltres);
    };

    
    document.querySelectorAll('.filters-container select').forEach(select => {
        select.addEventListener('change', function() {
            appliquerFiltres();
        });
    });

  
    prixFiltre.addEventListener('change', function() {
        const ordre = this.value;
        
        dernierTri = ordre;
        if (ordre === 'asc') {
            voyagesFiltres.sort((a, b) => a.prix - b.prix);  
        } else if (ordre === 'desc') {
            voyagesFiltres.sort((a, b) => b.prix - a.prix);  
        }
        pageActuelle = 1;
        afficherVoyages(voyagesFiltres);
        genererPagination(voyagesFiltres);
    });

    function afficherVoyages(voyages) {
        const debut = (pageActuelle - 1) * voyagesParPage;
        const fin = debut + voyagesParPage;
        const voyagesPage = voyages.slice(debut, fin);
        
      
        listePhotos.innerHTML = '';
        
       
        voyagesPage.forEach(voyage => {
            const prix = new Intl.NumberFormat('fr-FR', { 
                minimumFractionDigits: 2, 
                maximumFractionDigits: 2 
            }).format(voyage.prix);
            
            const voyageHTML = `
                <div class="gallerie-img">
                    <a href="${voyage.lien}">
                        <img src="${voyage.image}" alt="${voyage.nom}">
                        <div class="Lieux"><p>${voyage.nom}</p></div>
                        <div class="Prix"><p>À partir de ${prix}€</p></div>
                    </a>
                </div>
            `;
            listePhotos.insertAdjacentHTML('beforeend', voyageHTML);
        });
    }

    function genererPagination(voyages) {
        const totalPages = Math.ceil(voyages.length / voyagesParPage);
        paginationContainer.innerHTML = '';
        
        if (pageActuelle > 1) {
            const prevButton = document.createElement('a');
            prevButton.href = '#';
            prevButton.textContent = 'Précédent';
            prevButton.addEventListener('click', (e) => {
                e.preventDefault();
                pageActuelle--;
                afficherVoyages(voyages);
                genererPagination(voyages);
            });
            paginationContainer.appendChild(prevButton);
        }

        for (let i = 1; i <= totalPages; i++) {
            const pageLink = document.createElement('a');
            pageLink.href = '#';
            pageLink.textContent = i;
            if (i === pageActuelle) {
                pageLink.classList.add('active');
            }
            pageLink.addEventListener('click', (e) => {
                e.preventDefault();
                pageActuelle = i;
                afficherVoyages(voyages);
                genererPagination(voyages);
            });
            paginationContainer.appendChild(pageLink);
        }

        if (pageActuelle < totalPages) {
            const nextButton = document.createElement('a');
            nextButton.href = '#';
            nextButton.textContent = 'Suivant';
            nextButton.addEventListener('click', (e) => {
                e.preventDefault();
                pageActuelle++;
                afficherVoyages(voyages);
                genererPagination(voyages);
            });
            paginationContainer.appendChild(nextButton);
        }
    }

    afficherVoyages(voyagesFiltres);
    genererPagination(voyagesFiltres);
});
