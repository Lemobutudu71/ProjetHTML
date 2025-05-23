// Fonction pour créer ou mettre à jour un cookie.
// name: Nom du cookie.
// value: Valeur du cookie.
// days: Nombre de jours avant l'expiration du cookie (optionnel).
function setCookie(name, value, days) {
    let expires = ""; // Initialise la chaîne d'expiration.
    // Si le nombre de jours est spécifié.
    if (days) {
      let date = new Date(); // Crée un nouvel objet Date.
      // Calcule la date d'expiration en millisecondes.
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      // Formatte la date d'expiration en chaîne UTC.
      expires = "; expires=" + date.toUTCString();
    }
    // Crée ou met à jour le cookie avec le nom, la valeur, l'expiration et le chemin (racine du site).
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
  }

  // Fonction pour récupérer la valeur d'un cookie.
  // name: Nom du cookie à récupérer.
  function getCookie(name) {
    let nameEQ = name + "="; // Prépare la chaîne de recherche (nom=).
    let ca = document.cookie.split(';'); // Divise la chaîne de tous les cookies en un tableau.
    // Parcourt le tableau des cookies.
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i].trim(); // Récupère un cookie individuel et supprime les espaces.
      // Si le cookie commence par la chaîne de recherche (nameEQ).
      if (c.indexOf(nameEQ) === 0)
        // Retourne la valeur du cookie (substring après nameEQ).
        return c.substring(nameEQ.length);
    }
    return null; // Retourne null si le cookie n'est pas trouvé.
}
  
  // Fonction pour appliquer un thème (clair ou par défaut).
  // theme: Le nom du thème à appliquer ("light" ou "default").
  function applyTheme(theme) {
    // Récupère l'élément <link> qui contient la feuille de style du thème.
    const themeLink = document.getElementById("theme");
  
    // Si le thème demandé est "light".
    if (theme === "light") {
      // Change le href de l'élément <link> pour pointer vers le fichier CSS du thème clair.
      themeLink.href = basePath + "/light.css";
      // Sauvegarde la préférence de thème "light" dans le localStorage.
      localStorage.setItem("theme", "light");
    } else {
      // Sinon (thème par défaut).
      // Change le href pour pointer vers le fichier CSS du thème par défaut.
      themeLink.href = basePath + "/CSS.css";
      // Sauvegarde la préférence de thème "default" dans le localStorage.
      localStorage.setItem("theme", "default");
    }
  }
 
  // Ajoute un écouteur d'événement au clic sur le bouton de basculement de thème.
  document.getElementById("theme-toggle").addEventListener("click", function() {
    // Récupère le thème actuel à partir du cookie.
    let currentTheme = getCookie("theme");
    // Si le cookie de thème n'est pas "light" (ou n'existe pas), considère le thème actuel comme "default".
    if (currentTheme !== "light") {
      currentTheme = "default";
    }
    
    // Détermine le nouveau thème : si actuel est "default", nouveau est "light", sinon "default".
    let newTheme = (currentTheme === "default") ? "light" : "default";
    
    // Applique le nouveau thème.
    applyTheme(newTheme);
    // Sauvegarde le nouveau thème dans un cookie pour 30 jours.
    setCookie("theme", newTheme, 30);
  });
  
  // Attend que le contenu HTML de la page soit complètement chargé.
  document.addEventListener("DOMContentLoaded", function () {
    // Récupère l'élément de saisie de la date de départ.
    const departureDateInput = document.getElementById("departure-date");
    // Si l'élément existe.
    if (departureDateInput) {
        // Récupère la date d'aujourd'hui au format YYYY-MM-DD.
        const today = new Date().toISOString().split("T")[0]; 
        // Définit l'attribut "min" de l'input de date à aujourd'hui, empêchant la sélection de dates antérieures.
        departureDateInput.setAttribute("min", today);

        // Ajoute un écouteur d'événement pour détecter les changements sur l'input de date.
        departureDateInput.addEventListener("change", function () {
            // Si la date sélectionnée est antérieure à aujourd'hui.
            if (departureDateInput.value < today) {
                // Affiche une alerte à l'utilisateur.
                alert("Vous ne pouvez pas choisir une date antérieure à aujourd'hui.");
                // Réinitialise la valeur de l'input à la date d'aujourd'hui.
                departureDateInput.value = today; 
            }
        });
    }
});