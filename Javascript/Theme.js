
// Fonction pour créer ou mettre à jour un cookie
function setCookie(name, value, days) {
    let expires = "";
    if (days) {
      let date = new Date(); //Création de l'objet Date
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
  }
  
  // Fonction pour récupérer la valeur d'un cookie à partir de son nom
  function getCookie(name) {
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
      let c = ca[i].trim();
      if (c.indexOf(nameEQ) === 0)
        return c.substring(nameEQ.length);
    }
    return null;
}
  
  
  // Fonction pour appliquer le thème en changeant le fichier CSS
  function applyTheme(theme) {
    const themeLink = document.getElementById("theme");
  
    if (theme === "light") {
      themeLink.href = "/test/Projet/light.css";
    } else {
      // Par défaut, on charge le CSS par défaut
      themeLink.href = "/test/Projet/CSS.css";
    }
  }
  
  // Au chargement de la page, on vérifie la présence du cookie "theme"
  window.onload = function() {
    const savedTheme = getCookie("theme");
    if (savedTheme === "light" || savedTheme === "default") {
        applyTheme(savedTheme);
        // Si le thème est light, on coche le bouton, sinon on le décoche
        document.getElementById("theme-toggle").checked = (savedTheme === "light");
    } else {
        applyTheme("default");
        document.getElementById("theme-toggle").checked = false;
    }
};
  // Gestion du clic sur le bouton pour changer de thème
  document.getElementById("theme-toggle").addEventListener("click", function() {
    let currentTheme = getCookie("theme");
    if (currentTheme !== "light") {
      currentTheme = "default";
    }
    
    let newTheme = (currentTheme === "default") ? "light" : "default";
    
    applyTheme(newTheme);
    setCookie("theme", newTheme, 30);
  });
  