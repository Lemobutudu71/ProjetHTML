document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
      let errorContainer = document.getElementById('loginErrorContainer');
        if (!errorContainer) {
            errorContainer = document.createElement("div");
            errorContainer.id = "loginErrorContainer";
            loginForm.parentNode.insertBefore(errorContainer, loginForm);
        }
      
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault(); 
            errorContainer.innerHTML = '';
            let isValid = true;
            let errors = [];

            const emailField = loginForm.querySelector('input[name="email"]');
            const passwordField = loginForm.querySelector('input[name="password"]');
            const email = emailField.value.trim();
            const password = passwordField.value;

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