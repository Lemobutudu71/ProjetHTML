// Attend que le contenu HTML de la page soit complètement chargé avant d'exécuter le script.
document.addEventListener('DOMContentLoaded', function () {
    // Récupération des éléments du DOM utiles pour la modification de profil.
    const postalCodeInput = document.getElementById('postal_code');
    const cityInput = document.getElementById('city');
    const addressContainer = document.getElementById('address-container');
    const ถนนInput = document.getElementById('road'); // Nom de variable à vérifier, potentiel mélange de langues (ถนน signifie route/rue en thaï).
    // const form = document.querySelector('form'); // Commenté pour l'instant, sera traité dans un chunk suivant.

    // Si les champs de code postal et de ville existent, active la recherche d'adresse.
    if (postalCodeInput && cityInput) {
        // Ajoute un écouteur d'événement sur le champ du code postal lors de la perte de focus (blur).
        postalCodeInput.addEventListener('blur', async function () {
            const postalCode = this.value;
            // Vérifie si le code postal a une longueur de 5 chiffres (format français typique).
            if (postalCode.length === 5) {
                try {
                    // Interroge une API pour obtenir les informations de la ville basées sur le code postal.
                    // L'API utilisée ici est api.gouv.fr/geo.
                    const response = await fetch(`https://geo.api.gouv.fr/communes?codePostal=${postalCode}&fields=nom,codeDepartement&format=json&geometry=centre`);
                    const data = await response.json();
                    // Si des données sont retournées et qu'il y a au moins une commune trouvée.
                    if (data && data.length > 0) {
                        // Met à jour le champ de la ville avec le nom de la première commune trouvée.
                        cityInput.value = data[0].nom;
                        // Si un conteneur pour l'adresse et un champ pour la rue existent, les affiche.
                        // Cela suggère que ces champs pourraient être initialement cachés.
                        if (addressContainer) addressContainer.style.display = 'block';
                        if (ถนนInput) ถนนInput.focus(); // Donne le focus au champ de la rue.
                    } else {
                        // Si aucune commune n'est trouvée, affiche une alerte.
                        alert('Code postal non trouvé.');
                        // Réinitialise potentiellement le champ de la ville ou le cache.
                        cityInput.value = '';
                        if (addressContainer) addressContainer.style.display = 'none';
                    }
                } catch (error) {
                    // Gère les erreurs lors de la requête à l'API.
                    console.error('Erreur lors de la récupération de la ville:', error);
                    alert('Impossible de vérifier le code postal pour le moment.');
                    if (addressContainer) addressContainer.style.display = 'none';
                }
            }
        });
    }

    // Gestion de la modification du mot de passe.
    // Récupère la case à cocher qui permet à l'utilisateur d'indiquer s'il souhaite changer son mot de passe.
    const changePasswordCheckbox = document.getElementById('change_password_checkbox');
    // Récupère le conteneur (div) qui englobe les champs pour le nouveau mot de passe.
    const passwordFieldsDiv = document.getElementById('password_fields');
    // Récupère le champ de saisie pour le nouveau mot de passe.
    const newPasswordInput = document.getElementById('new_password');
    // Récupère le champ de saisie pour la confirmation du nouveau mot de passe.
    const confirmPasswordInput = document.getElementById('confirm_password');

    // Si la case à cocher pour changer le mot de passe existe sur la page.
    if (changePasswordCheckbox) {
        // Ajoute un écouteur d'événement qui se déclenche lorsque l'état de la case change (cochée/décochée).
        changePasswordCheckbox.addEventListener('change', function () {
            // Si la case est cochée (l'utilisateur veut changer son mot de passe).
            if (this.checked) {
                // Affiche le conteneur des champs de mot de passe.
                passwordFieldsDiv.style.display = 'block';
                // Rend les champs de nouveau mot de passe et de confirmation obligatoires.
                // L'attribut 'required' empêche la soumission du formulaire si ces champs sont vides.
                if (newPasswordInput) newPasswordInput.required = true;
                if (confirmPasswordInput) confirmPasswordInput.required = true;
            } else {
                // Si la case est décochée (l'utilisateur ne veut plus changer son mot de passe).
                // Masque le conteneur des champs de mot de passe.
                passwordFieldsDiv.style.display = 'none';
                // Rend les champs de mot de passe non obligatoires.
                if (newPasswordInput) newPasswordInput.required = false;
                if (confirmPasswordInput) confirmPasswordInput.required = false;
            }
        });
    }

    // Validation de la confirmation du mot de passe.
    // S'assure que les deux champs de saisie de mot de passe (nouveau et confirmation) existent.
    if (newPasswordInput && confirmPasswordInput) {
        // Définit une fonction pour valider que les deux mots de passe saisis sont identiques.
        const validatePasswordConfirmation = () => {
            // Compare les valeurs des deux champs.
            if (newPasswordInput.value !== confirmPasswordInput.value) {
                // S'ils sont différents, utilise l'API de validation de contrainte HTML5 pour définir un message d'erreur personnalisé.
                // Ce message s'affichera généralement lorsque l'utilisateur essaie de soumettre le formulaire.
                confirmPasswordInput.setCustomValidity("Les mots de passe ne correspondent pas.");
            } else {
                // S'ils sont identiques, efface tout message d'erreur personnalisé précédent.
                confirmPasswordInput.setCustomValidity("");
            }
        };
        // Ajoute des écouteurs d'événements pour déclencher la validation à différents moments :
        // - 'change' sur le champ du nouveau mot de passe : lorsque sa valeur change et qu'il perd le focus.
        newPasswordInput.addEventListener('change', validatePasswordConfirmation);
        // - 'keyup' sur le champ de confirmation : à chaque fois qu'une touche est relâchée (validation en temps réel).
        confirmPasswordInput.addEventListener('keyup', validatePasswordConfirmation);
        // - 'blur' sur le champ de confirmation : lorsqu'il perd le focus.
        confirmPasswordInput.addEventListener('blur', validatePasswordConfirmation);
    }

    // Gestion de la prévisualisation de l'image de profil.
    // Récupère l'élément input de type 'file' pour le choix de l'image de profil.
    const profileImageInput = document.getElementById('profile_image_input');
    // Récupère l'élément <img> où la prévisualisation de la nouvelle image sera affichée.
    const imagePreview = document.getElementById('image_preview');
    // Récupère l'élément <img> qui affiche l'image de profil actuelle (si elle existe).
    const existingImage = document.getElementById('existing_image'); 
    // Récupère le bouton permettant de supprimer l'image actuelle ou d'annuler le changement.
    const removeImageButton = document.getElementById('remove_image_button');
    // Récupère un champ input caché (type 'hidden') utilisé pour signaler au serveur si l'image doit être supprimée.
    const removeImageInput = document.getElementById('remove_image_input'); 

    // Si l'input de fichier et l'élément de prévisualisation d'image existent.
    if (profileImageInput && imagePreview) {
        // Ajoute un écouteur d'événement qui se déclenche lorsque l'utilisateur sélectionne un fichier.
        profileImageInput.addEventListener('change', function (event) {
            // Récupère le premier fichier sélectionné (normalement, un seul fichier pour un input d'image).
            const file = event.target.files[0];
            // Si un fichier a bien été sélectionné.
            if (file) {
                // Crée un objet FileReader pour lire le contenu du fichier.
                const reader = new FileReader();
                // Définit ce qui se passe une fois que le FileReader a terminé de lire le fichier.
                reader.onload = function (e) {
                    // Met à jour l'attribut 'src' de l'élément <img> de prévisualisation avec les données de l'image lue.
                    // e.target.result contient les données de l'image sous forme d'URL de données (Data URL).
                    imagePreview.src = e.target.result;
                    // Affiche l'élément de prévisualisation.
                    imagePreview.style.display = 'block'; 
                    // Masque l'image existante, car une nouvelle va être prévisualisée.
                    if (existingImage) existingImage.style.display = 'none'; 
                    // Réinitialise la valeur du champ caché 'remove_image_input' à "0" (ou une autre valeur signifiant "ne pas supprimer").
                    // Cela est utile si l'utilisateur avait précédemment cliqué sur "Supprimer l'image" puis choisi une nouvelle image.
                    if (removeImageInput) removeImageInput.value = "0"; 
                    // Change le texte du bouton pour refléter qu'on peut annuler le *changement* d'image (et non plus la suppression).
                    if (removeImageButton) removeImageButton.textContent = "Annuler le changement"; 
                }
                // Commence la lecture du fichier. Le résultat sera une URL de données.
                reader.readAsDataURL(file);
            }
        });
    }

    // Si le bouton de suppression/annulation et le champ caché associé existent.
    if (removeImageButton && removeImageInput) {
        // Ajoute un écouteur d'événement au clic sur le bouton.
        removeImageButton.addEventListener('click', function() {
            // Vérifie la valeur actuelle du champ caché pour savoir si l'image est actuellement marquée pour suppression.
            if (removeImageInput.value === "1") { // "1" signifie que l'image est marquée pour suppression.
                // L'utilisateur clique pour annuler la suppression.
                removeImageInput.value = "0"; // Change la valeur pour "ne pas supprimer".
                this.textContent = "Supprimer l'image actuelle"; // Réinitialise le texte du bouton.
                
                // Réaffiche l'image appropriée :
                // Si une nouvelle image avait été sélectionnée dans l'input file (et donc prévisualisée).
                if (profileImageInput.files.length > 0 && imagePreview) {
                    imagePreview.style.display = 'block'; // Affiche la prévisualisation de la nouvelle image.
                    if (existingImage) existingImage.style.display = 'none'; // S'assure que l'ancienne est masquée.
                } else if (existingImage) {
                    // Sinon (aucune nouvelle image sélectionnée), réaffiche l'image existante.
                    existingImage.style.display = 'block';
                    if (imagePreview) imagePreview.style.display = 'none'; // Masque la zone de prévisualisation vide.
                }
            } else { // L'image n'est pas (ou plus) marquée pour suppression, ou une nouvelle image est en cours de prévisualisation.
                // L'utilisateur clique pour : 
                // 1. Marquer l'image actuelle pour suppression (si aucune nouvelle image n'est prévisualisée).
                // 2. Annuler la sélection d'une nouvelle image et revenir à l'état "pas d'image" (ou prêt à supprimer l'existante).
                removeImageInput.value = "1"; // Marque pour suppression (ou confirme l'intention si une nouvelle était prévisualisée).
                this.textContent = "Annuler la suppression"; // Change le texte du bouton.
                
                // Masque à la fois la prévisualisation et l'image existante.
                if (imagePreview) imagePreview.style.display = 'none'; 
                if (existingImage) existingImage.style.display = 'none'; 
                // Réinitialise le champ de sélection de fichier. Ceci annule la sélection d'un nouveau fichier.
                profileImageInput.value = ''; 
            }
        });
    }

    // Soumission du formulaire.
    // Récupère l'élément formulaire principal de la page.
    // Il est préférable d'utiliser un ID plus spécifique si plusieurs formulaires existent, 
    // mais document.querySelector('form') sélectionnera le premier trouvé.
    const form = document.querySelector('form'); 

    // Si un formulaire est trouvé sur la page.
    if (form) {
        // Ajoute un écouteur d'événement qui se déclenche lorsque l'utilisateur tente de soumettre le formulaire.
        form.addEventListener('submit', function (event) {
            // Vérifie si la case "changer le mot de passe" est cochée et si les éléments correspondants existent.
            if (changePasswordCheckbox && changePasswordCheckbox.checked) {
                // Vérifie une dernière fois que les mots de passe saisis correspondent.
                // Bien que la validation 'onkeyup' et 'onblur' existe, cette vérification à la soumission est une sécurité supplémentaire.
                if (newPasswordInput.value !== confirmPasswordInput.value) {
                    // Affiche une alerte à l'utilisateur.
                    alert("Les nouveaux mots de passe ne correspondent pas.");
                    // Empêche la soumission du formulaire.
                    event.preventDefault(); 
                    // Donne le focus au champ de confirmation du mot de passe pour faciliter la correction.
                    confirmPasswordInput.focus(); 
                    return; // Arrête l'exécution de cette fonction pour éviter d'autres validations ou la soumission.
                }
                // Vérifie si le nouveau mot de passe respecte une politique de complexité minimale (exemple simple).
                // REMARQUE IMPORTANTE : Une validation de complexité de mot de passe robuste DOIT également être effectuée côté serveur.
                // La validation côté client est une aide pour l'utilisateur mais peut être contournée.
                if (newPasswordInput.value.length < 6) { // Exemple : longueur minimale de 6 caractères.
                    alert("Le nouveau mot de passe doit contenir au moins 6 caractères.");
                    event.preventDefault(); // Empêche la soumission.
                    newPasswordInput.focus(); // Donne le focus au champ du nouveau mot de passe.
                    return; // Arrête l'exécution.
                }
            }

            // D'autres validations spécifiques au formulaire pourraient être ajoutées ici avant la soumission.
            // Par exemple, vérifier les formats de date, s'assurer que des champs obligatoires (non gérés par l'attribut `required`)
            // sont bien remplis, etc.

            // Si toutes les validations personnalisées passent (c'est-à-dire si event.preventDefault() n'a pas été appelé),
            // le formulaire sera soumis normalement au serveur.
        });
    }
});