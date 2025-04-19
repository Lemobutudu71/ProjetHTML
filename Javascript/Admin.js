document.addEventListener('DOMContentLoaded', () => {
    const button = document.querySelectorAll('.Oui-btn, .Non-btn');
  
    button.forEach(btn => {
      btn.addEventListener('click', () => {
        btn.disabled = true;
        btn.style.opacity = '0.5';
  
        const originalText = btn.textContent;
        btn.textContent = 'En cours...';
  
        
     setTimeout(() => {
          if (originalText.trim() === 'Oui') {
            btn.textContent = 'Non';
            btn.classList.remove('Oui-btn');
            btn.classList.add('Non-btn');
          } 
          else {
            btn.textContent = 'Oui';
            btn.classList.remove('Non-btn');
            btn.classList.add('Oui-btn');
          }
          
          btn.disabled = false;
          btn.style.opacity = '1';
        }, 3000);

      });
    });


  });