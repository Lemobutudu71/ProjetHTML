// Attend que le contenu HTML de la page soit complètement chargé avant d'exécuter le script.
document.addEventListener('DOMContentLoaded', function() {
    // Récupère l'élément formulaire de connexion par son ID.
    const form = document.getElementById('login-form');
    // Récupère le champ de saisie de l'email par son ID.
    const emailInput = document.getElementById('email');
    // Récupère le champ de saisie du mot de passe par son ID.
    const passwordInput = document.getElementById('password');
    // Récupère l'élément où afficher les messages d'erreur, par son ID.
    const errorMessage = document.getElementById('error-message'); // Cet élément doit exister dans le HTML.

    // Vérifie si le formulaire, les champs et le conteneur de message d'erreur ont bien été trouvés sur la page.
    if (form && emailInput && passwordInput && errorMessage) {
        // Ajoute un écouteur d'événement pour l'événement 'submit' (soumission du formulaire).
        form.addEventListener('submit', function(event) {
            // Par défaut, suppose qu'il n'y a pas d'erreur de validation.
            let hasError = false;
            // Réinitialise le message d'erreur (le vide) et le masque au début de chaque tentative de soumission.
            errorMessage.textContent = '';
            errorMessage.style.display = 'none';

            // Validation du champ email.
            // Vérifie si le champ email est vide après avoir supprimé les espaces au début et à la fin.
            if (emailInput.value.trim() === '') {
                hasError = true;
                errorMessage.textContent = 'Veuillez saisir votre adresse e-mail.';
                emailInput.focus(); // Donne le focus au champ email.
            } 
            // Validation du champ mot de passe (uniquement si l'email n'était pas vide).
            else if (passwordInput.value === '') { 
                hasError = true;
                errorMessage.textContent = 'Veuillez saisir votre mot de passe.';
                passwordInput.focus(); // Donne le focus au champ mot de passe.
            }

            // Si une erreur a été détectée (email ou mot de passe manquant).
            if (hasError) {
                errorMessage.style.display = 'block'; // Affiche le message d'erreur.
                event.preventDefault(); // Empêche la soumission du formulaire au serveur.
            }
            // Si aucune erreur n'est détectée, le formulaire sera soumis normalement (comportement par défaut du navigateur).
        });
    }
});