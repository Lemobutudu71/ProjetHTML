// Définit une fonction nommée togglePassword qui prend un argument : inputId.
// inputId est l'identifiant de l'élément de champ de saisie du mot de passe.
function togglePassword(inputId) {
    // Récupère l'élément de champ de saisie (input) en utilisant son identifiant.
    const input = document.getElementById(inputId);
    // Récupère l'élément icône associé à ce champ de saisie.
    // L'icône est identifiée par un attribut data-for qui correspond à l'inputId.
    const icon = document.querySelector(`[data-for="${inputId}"]`);
    
    // Vérifie si le type actuel du champ de saisie est 'password'.
    if (input.type === 'password') {
        // Si c'est un champ de mot de passe, change son type en 'text' pour le rendre visible.
        input.type = 'text';
        // Modifie l'icône pour indiquer que le mot de passe est visible (par exemple, un œil barré devient un œil).
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        // Si ce n'est pas un champ de mot de passe (donc, il est de type 'text'),
        // change son type en 'password' pour le masquer.
        input.type = 'password';
        // Modifie l'icône pour indiquer que le mot de passe est masqué.
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
