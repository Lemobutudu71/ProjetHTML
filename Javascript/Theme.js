

function setCookie(name, value, days) {
    let expires = "";
    if (days) {
      let date = new Date(); 
      date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
      expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
  }

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
  
  function applyTheme(theme) {
    const themeLink = document.getElementById("theme");
  
    if (theme === "light") {
      themeLink.href = "/test/Projet/light.css";
    } else {

      themeLink.href = "/test/Projet/CSS.css";
    }
  }
 
  window.onload = function() {
    const savedTheme = getCookie("theme");
    if (savedTheme === "light" || savedTheme === "default") {
        applyTheme(savedTheme);
        document.getElementById("theme-toggle").checked = (savedTheme === "light");
    } else {
        applyTheme("default");
        document.getElementById("theme-toggle").checked = false;
    }
};

  document.getElementById("theme-toggle").addEventListener("click", function() {
    let currentTheme = getCookie("theme");
    if (currentTheme !== "light") {
      currentTheme = "default";
    }
    
    let newTheme = (currentTheme === "default") ? "light" : "default";
    
    applyTheme(newTheme);
    setCookie("theme", newTheme, 30);
  });
  