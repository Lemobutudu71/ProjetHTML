document.addEventListener('DOMContentLoaded', () => {
  const buttons = document.querySelectorAll('.Oui-btn, .Non-btn');
  const profilButtons = document.querySelectorAll('.profil-btn');

  // Gestion des boutons VIP et Bloqué
  buttons.forEach(btn => {
      btn.addEventListener('click', async () => {
          const userId = btn.dataset.userId;
          const field = btn.dataset.field;
          const currentValue = btn.textContent.trim();
          const newValue = currentValue === 'Oui' ? 'Non' : 'Oui';

          try {
              const response = await fetch('ModifierAdmin.php', {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json' },
                  body: JSON.stringify({
                      userId: userId,
                      field: field,
                      value: newValue
                  })
              });

              const data = await response.json();

              if (data.success) {
                  btn.textContent = newValue;
                  btn.className = newValue === 'Oui' ? 'Oui-btn' : 'Non-btn';
              } else {
                  throw new Error(data.error || 'Erreur inconnue');
              }
          } catch (error) {
              console.error('Erreur:', error);
              alert('Erreur lors de la mise à jour: ' + error.message);
          }
      });
  });

  // Gestion des boutons Voir profil
  profilButtons.forEach(btn => {
      btn.addEventListener('click', () => {
          const userId = btn.dataset.userId;
          window.location.href = `voirProfil.php?id=${userId}`;
      });
  });
});