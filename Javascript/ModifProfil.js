document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('profile-form');
    const submitGlobal = document.getElementById('submit-changes');
    let hasChange = false;
    
    if (!form || !submitGlobal) {
        console.error("Le formulaire ou le bouton global n'a pas été trouvé.");
        return;
    }
    
    // Pour chaque champ (dans un conteneur avec la classe "field-wrapper")
    form.querySelectorAll('.field-wrapper').forEach(wrapper => {
        const input = wrapper.querySelector('input');
        const btnEdit = wrapper.querySelector('.edit-btn');
        const btnSave = wrapper.querySelector('.save-btn');
        const btnCancel = wrapper.querySelector('.cancel-btn');
        
        if (!input || !btnEdit || !btnSave || !btnCancel) {
            console.error("Éléments manquants dans un field-wrapper :", wrapper);
            return;
        }
        
        const original = input.dataset.original || input.value;
        
        // Quand on clique sur le crayon, on active l'édition
        btnEdit.addEventListener('click', () => {
            input.disabled = false;
            input.focus();
            btnEdit.classList.add('hidden');
            btnSave.classList.remove('hidden');
            btnCancel.classList.remove('hidden');
        });
        
        // Quand on clique sur la coche, on enregistre la modification
        btnSave.addEventListener('click', () => {
            const newValue = input.value.trim();
            // Si la nouvelle valeur diffère de l'original, on note le changement
            if (newValue !== original) {
                hasChange = true;
                input.dataset.modified = "true";  // Marque le champ comme modifié
                submitGlobal.style.display = 'block'; // Affiche le bouton global
            }
            input.disabled = true;
            btnSave.classList.add('hidden');
            btnCancel.classList.add('hidden');
            btnEdit.classList.remove('hidden');
        });
        
        // Si on annule, on restaure la valeur d'origine
        btnCancel.addEventListener('click', () => {
            input.value = original;
            input.disabled = true;
            btnSave.classList.add('hidden');
            btnCancel.classList.add('hidden');
            btnEdit.classList.remove('hidden');
        });
    });
    
    // Lors de la soumission du formulaire
    form.addEventListener('submit', (event) => {
        if (!hasChange) {
            event.preventDefault();
            alert("Aucune modification n'a été effectuée.");
        } else {
            console.log("Form submitted with changes");
            form.querySelectorAll('input[disabled]').forEach(input => {
                input.disabled = false;
            });
        }
    });
});