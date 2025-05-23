// Attend que le contenu HTML de la page soit complètement chargé avant d'exécuter le script.
document.addEventListener('DOMContentLoaded', () => {
  // Sélectionne tous les boutons qui ont la classe .Oui-btn ou .Non-btn.
  // Ces boutons sont probablement utilisés pour basculer des états (par exemple, VIP Oui/Non).
  const buttons = document.querySelectorAll('.Oui-btn, .Non-btn');
  // Sélectionne tous les boutons qui ont la classe .profil-btn.
  // Ces boutons sont utilisés pour afficher le profil d'un utilisateur.
  const profilButtons = document.querySelectorAll('.profil-btn');

  // Gestion des boutons VIP et Bloqué
  // Ajoute un écouteur d'événement 'click' à chaque bouton de la collection 'buttons'.
  buttons.forEach(btn => {
      btn.addEventListener('click', async () => {
          // Récupère l'ID de l'utilisateur à partir de l'attribut data-user-id du bouton.
          const userId = btn.dataset.userId;
          // Récupère le champ à modifier (par exemple, 'vip' ou 'bloque') à partir de l'attribut data-field.
          const field = btn.dataset.field;
          // Récupère la valeur actuelle du bouton (Oui ou Non) en supprimant les espaces inutiles.
          const currentValue = btn.textContent.trim();
          // Détermine la nouvelle valeur : si la valeur actuelle est 'Oui', la nouvelle sera 'Non', et vice-versa.
          const newValue = currentValue === 'Oui' ? 'Non' : 'Oui';

          // Bloc try...catch pour gérer les erreurs potentielles lors de la requête.
          try {
              // Effectue une requête asynchrone (fetch) vers 'ModifierAdmin.php'.
              const response = await fetch('ModifierAdmin.php', {
                  method: 'POST', // Utilise la méthode POST pour envoyer des données.
                  headers: { 'Content-Type': 'application/json' }, // Définit l'en-tête Content-Type pour indiquer que le corps est en JSON.
                  // Corps de la requête : un objet JSON contenant l'ID de l'utilisateur, le champ à modifier et la nouvelle valeur.
                  body: JSON.stringify({
                      userId: userId,
                      field: field,
                      value: newValue
                  })
              });

              // Attend la réponse du serveur et la convertit en JSON.
              const data = await response.json();

              // Vérifie si la mise à jour a réussi (propriété success dans la réponse JSON).
              if (data.success) {
                  // Si la mise à jour a réussi, met à jour le texte du bouton avec la nouvelle valeur.
                  btn.textContent = newValue;
                  // Met à jour la classe du bouton pour refléter le nouvel état (Oui-btn ou Non-btn).
                  btn.className = newValue === 'Oui' ? 'Oui-btn' : 'Non-btn';
              } else {
                  // Si la mise à jour a échoué, lance une erreur avec le message d'erreur du serveur ou un message par défaut.
                  throw new Error(data.error || 'Erreur inconnue');
              }
          } catch (error) {
              // En cas d'erreur (échec de la requête fetch, erreur JSON, ou erreur lancée manuellement), affiche l'erreur dans la console.
              console.error('Erreur:', error);
              // Affiche une alerte à l'utilisateur avec le message d'erreur.
              alert('Erreur lors de la mise à jour: ' + error.message);
          }
      });
  });

  // Gestion des boutons Voir profil
  // Ajoute un écouteur d'événement 'click' à chaque bouton de la collection 'profilButtons'.
  profilButtons.forEach(btn => {
      btn.addEventListener('click', () => {
          // Récupère l'ID de l'utilisateur à partir de l'attribut data-user-id du bouton.
          const userId = btn.dataset.userId;
          // Redirige l'utilisateur vers la page voirProfil.php en passant l'ID de l'utilisateur comme paramètre d'URL.
          window.location.href = `voirProfil.php?id=${userId}`;
      });
  });
});