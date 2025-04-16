document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        // Créer un conteneur d'erreurs s'il n'existe pas déjà
        let errorContainer = document.getElementById('loginErrorContainer');
        if (!errorContainer) {
            errorContainer = document.createElement("div");
            errorContainer.id = "loginErrorContainer";
            // Insérer le conteneur d'erreurs avant le formulaire
            loginForm.parentNode.insertBefore(errorContainer, loginForm);
        }
      
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Empêche l'envoi par défaut

            // Effacer les messages d'erreur existants
            errorContainer.innerHTML = '';
            let isValid = true;
            let errors = [];

            // Récupérer les données saisies dans le formulaire de connexion
            const emailField = loginForm.querySelector('input[name="email"]');
            const passwordField = loginForm.querySelector('input[name="password"]');
            const email = emailField.value.trim();
            const password = passwordField.value;

            // Vérifier que le champ email est rempli et correspond à un format valide
            if (!email) {
                errors.push("L'email est requis.");
                isValid = false;
            } else {
                const emailRegex = /^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/;
                if (!emailRegex.test(email)) {
                    errors.push("Le format de l'email est invalide.");
                    isValid = false;
                }
            }

            // Vérifier que le champ mot de passe est rempli
            if (!password) {
                errors.push("Le mot de passe est requis.");
                isValid = false;
            }

            
            if (!isValid) {
                errors.forEach(function(message) {
                    const p = document.createElement('p');
                    p.textContent = message;
                    p.style.color = 'red';
                    p.style.margin = '5px 0';
                    errorContainer.appendChild(p);
                });
            } else {
                
                loginForm.submit();
            }
        });
    }
});