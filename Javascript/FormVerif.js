// Attend que le contenu HTML de la page soit complètement chargé avant d'exécuter le script.
document.addEventListener('DOMContentLoaded', function() {
    // Récupère le premier formulaire de la page. 
    // S'il y a plusieurs formulaires, il serait préférable d'utiliser un ID spécifique pour le cibler (par exemple, document.getElementById('monFormulaire')).
    const form = document.querySelector('form');

    // Si un formulaire est trouvé sur la page.
    if (form) {
        // Ajoute un écouteur d'événement qui se déclenche lors de la tentative de soumission du formulaire.
        form.addEventListener('submit', function(event) {
            // Variable pour suivre si toutes les validations sont passées.
            let isValid = true;
            // Efface les messages d'erreur précédents avant de commencer une nouvelle validation.
            clearErrors(form);

            // --- VALIDATION DES CHAMPS OBLIGATOIRES ---
            // Sélectionne tous les éléments du formulaire qui ont l'attribut 'required'.
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(function(field) {
                // Vérifie si le champ (après avoir retiré les espaces avant/après) est vide.
                if (field.value.trim() === '') {
                    // Affiche un message d'erreur générique pour le champ obligatoire.
                    showError(field, 'Ce champ est obligatoire.');
                    isValid = false; // Marque le formulaire comme invalide.
                }
            });

            // --- VALIDATION SPÉCIFIQUE POUR L'EMAIL ---
            // Sélectionne le champ email (on suppose qu'il y en a un avec type="email").
            const emailField = form.querySelector('input[type="email"]');
            // Si un champ email existe et qu'il n'est pas vide (la validation 'required' s'en occupe s'il l'est).
            if (emailField && emailField.value.trim() !== '') {
                // Expression régulière pour une validation de format d'email de base.
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailField.value.trim())) {
                    showError(emailField, 'Veuillez saisir une adresse e-mail valide.');
                    isValid = false;
                }
            }

            // --- VALIDATION DE LA CONFIRMATION DU MOT DE PASSE ---
            // Suppose que le champ mot de passe a l'ID 'password' et la confirmation l'ID 'confirm_password'.
            // Ces ID devraient être présents dans votre HTML pour que cela fonctionne.
            const passwordField = form.querySelector('#password'); 
            const confirmPasswordField = form.querySelector('#confirm_password');
            // Si les deux champs existent et que le champ du mot de passe n'est pas vide.
            if (passwordField && confirmPasswordField && passwordField.value !== '') {
                if (passwordField.value !== confirmPasswordField.value) {
                    showError(confirmPasswordField, 'Les mots de passe ne correspondent pas.');
                    isValid = false;
                }
            }
            
            // --- VALIDATION DU CODE POSTAL (Exemple pour format français à 5 chiffres) ---
            // Cible le champ par son attribut 'name'. Adaptez si vous utilisez un ID.
            const postalCodeField = form.querySelector('input[name="postal_code"]'); 
            if (postalCodeField && postalCodeField.value.trim() !== '') {
                // Expression régulière pour 5 chiffres exacts.
                const postalRegex = /^\d{5}$/;
                if (!postalRegex.test(postalCodeField.value.trim())) {
                    showError(postalCodeField, 'Le code postal doit contenir 5 chiffres.');
                    isValid = false;
                }
            }

            // --- FIN DES VALIDATIONS ---
            // Si au moins une des validations a échoué (isValid est false).
            if (!isValid) {
                // Empêche le comportement par défaut du navigateur, qui est de soumettre le formulaire.
                event.preventDefault();
            }
            // Si isValid est true, le formulaire sera soumis normalement.
        });
    }

    /**
     * Affiche un message d'erreur sous le champ spécifié.
     * @param {HTMLElement} field - L'élément de formulaire sous lequel afficher l'erreur.
     * @param {string} message - Le message d'erreur à afficher.
     */
    function showError(field, message) {
        // Recherche si un message d'erreur existe déjà pour ce champ pour éviter les doublons.
        let errorElement = field.parentNode.querySelector('.error-message[data-field="' + (field.id || field.name) + '"]');
        if (!errorElement) {
            errorElement = document.createElement('div');
            errorElement.className = 'error-message'; // Classe pour le style CSS.
            errorElement.style.color = 'red';        // Style direct (peut être géré via CSS).
            errorElement.style.fontSize = '0.9em';
            errorElement.style.marginTop = '4px';
            // Ajoute un attribut pour identifier à quel champ l'erreur est liée.
            errorElement.dataset.field = field.id || field.name;
            
            // Insère le message d'erreur après le champ dans le DOM.
            // field.after(errorElement); // Méthode moderne
            field.parentNode.insertBefore(errorElement, field.nextSibling); // Compatibilité plus large
        }
        errorElement.textContent = message;
    }

    /**
     * Supprime tous les messages d'erreur actuellement affichés dans le formulaire.
     * @param {HTMLFormElement} formElement - Le formulaire dont les erreurs doivent être effacées.
     */
    function clearErrors(formElement) {
        const errorMessages = formElement.querySelectorAll('.error-message');
        errorMessages.forEach(function(error) {
            error.remove(); // Supprime l'élément du DOM.
        });
    }
});


