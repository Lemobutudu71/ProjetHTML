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
      themeLink.href = basePath + "/light.css";
      localStorage.setItem("theme", "light");
    } else {
      themeLink.href = basePath + "/CSS.css";
      localStorage.setItem("theme", "default");
    }
  }
 
  document.getElementById("theme-toggle").addEventListener("click", function() {
    let currentTheme = getCookie("theme");
    if (currentTheme !== "light") {
      currentTheme = "default";
    }
    
    let newTheme = (currentTheme === "default") ? "light" : "default";
    
    applyTheme(newTheme);
    setCookie("theme", newTheme, 30);
  });
  
  document.addEventListener("DOMContentLoaded", function () {
    const departureDateInput = document.getElementById("departure-date");
    if (departureDateInput) {
        const today = new Date().toISOString().split("T")[0]; 
        departureDateInput.setAttribute("min", today);

        departureDateInput.addEventListener("change", function () {
            if (departureDateInput.value < today) {
                alert("Vous ne pouvez pas choisir une date antérieure à aujourd'hui.");
                departureDateInput.value = today; 
            }
        });
    }
});