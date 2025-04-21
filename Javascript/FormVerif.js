document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(event) {
          event.preventDefault();  
            let isValid = true;
            let errors = [];
            
            const errorContainer = document.getElementById('registerError');
            errorContainer.innerHTML = '';
            
            const nom = registerForm.querySelector('input[name="nom"]').value.trim();
            const prenom = registerForm.querySelector('input[name="prenom"]').value.trim();
            const email = registerForm.querySelector('input[name="email"]').value.trim();
            const password = registerForm.querySelector('input[name="password"]').value;
            
            if (nom.length < 2) {
                errors.push("Le nom doit contenir au moins 2 caractères.");
                isValid = false;
            }
    
            if (prenom.length < 2) {
                errors.push("Le prénom doit contenir au moins 2 caractères.");
                isValid = false;
            }
      
            const emailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
            if (!email.match(emailRegex)) {
                errors.push("Le format de l'email est invalide.");
                isValid = false;
            }
            
            if (password.length < 8) {
                errors.push("Le mot de passe doit contenir au moins 8 caractères.");
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
            } 
            else {
                registerForm.submit();
            }
        });
    }
});


