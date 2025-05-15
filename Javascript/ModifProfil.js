document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('profile-form');
    const submitGlobal = document.getElementById('submit-changes');
    let hasChange = false;
    
    if (!form || !submitGlobal) {
        console.error("Le formulaire ou le bouton global n'a pas été trouvé.");
        return;
    }
    
    form.querySelectorAll('.field-wrapper').forEach(wrapper => {
        const input = wrapper.querySelector('input');
        const btnEdit = wrapper.querySelector('.edit-btn');
        const btnSave = wrapper.querySelector('.save-btn');
        const btnCancel = wrapper.querySelector('.cancel-btn');
        
        if (!input || !btnEdit || !btnSave || !btnCancel) {
            console.error("Éléments manquants dans un field-wrapper :", wrapper);
            return;
        }
        
        const original = input.dataset.original || input.value;
   
        btnEdit.addEventListener('click', () => {
            input.disabled = false;
            input.focus();
            btnEdit.classList.add('hidden');
            btnSave.classList.remove('hidden');
            btnCancel.classList.remove('hidden');
        });
        
        btnSave.addEventListener('click', () => {
            const newValue = input.value.trim();
            if (newValue !== original) {
                hasChange = true;
                input.dataset.modified = "true";  
                submitGlobal.style.display = 'block'; 
            }
            input.disabled = true;
            btnSave.classList.add('hidden');
            btnCancel.classList.add('hidden');
            btnEdit.classList.remove('hidden');
        });

        btnCancel.addEventListener('click', () => {
            input.value = original;
            input.disabled = true;
            btnSave.classList.add('hidden');
            btnCancel.classList.add('hidden');
            btnEdit.classList.remove('hidden');
        });
    });
    
 
    form.addEventListener('submit', async (event) => {
        event.preventDefault(); // Always prevent default for AJAX

        if (!hasChange) {
            alert("Aucune modification n'a été effectuée.");
            return;
        } 

        // Temporarily re-enable inputs to gather their values for FormData
        const disabledInputs = [];
        form.querySelectorAll('input:disabled').forEach(input => {
            input.disabled = false;
            disabledInputs.push(input);
        });

        const formData = new FormData(form);
        formData.append('submit_changes', 'true');

        // Re-disable inputs that were originally disabled if needed for UI consistency immediately after grabbing data
        // Or, more simply, the success/error handlers will reset the UI state anyway.
        disabledInputs.forEach(input => input.disabled = true);

        // Add a loading indicator here if desired
        // e.g., submitGlobal.textContent = 'Sauvegarde en cours...'; submitGlobal.disabled = true;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Standard header to identify AJAX requests
                },
                body: formData
            });

            if (!response.ok) {
                // Try to get more error info if response is not ok
                let errorText = `HTTP error! status: ${response.status}`;
                try {
                    const errorData = await response.text(); // Get response body as text
                    console.error("Server error response (not OK):", errorData);
                    // You might try to parse as JSON if you expect structured errors even for non-200 responses
                    // For example, if your server sends { "message": "..." } with a 400/500 status
                } catch (e) {
                    // Ignore if can't read body
                }
                throw new Error(errorText);
            }

            const result = await response.json();

            // Log server-side debug messages if present
            if (result.debug) {
                console.log("Server Debug Messages:", result.debug);
            }

            if (result.success) {
                // alert("Profil mis à jour avec succès !"); // Or a more subtle notification -- Removed this line
                form.querySelectorAll('.field-wrapper').forEach(wrapper => {
                    const input = wrapper.querySelector('input');
                    if (result.newData && result.newData[input.name]) { // Check if newData and specific field exist
                        input.value = result.newData[input.name];
                        input.dataset.original = result.newData[input.name]; // Update original value
                    }
                    input.disabled = true; // Disable after update
                    input.dataset.modified = "false"; // Reset modified flag
                    wrapper.querySelector('.save-btn').classList.add('hidden');
                    wrapper.querySelector('.cancel-btn').classList.add('hidden');
                    wrapper.querySelector('.edit-btn').classList.remove('hidden');
                });
                hasChange = false;
                submitGlobal.style.display = 'none';
            } else {
                alert(`Erreur: ${result.message || 'Une erreur est survenue.'}`);
                // Revert changes on error by resetting to original values
                form.querySelectorAll('.field-wrapper input[data-modified="true"]').forEach(input => {
                    input.value = input.dataset.original;
                    input.disabled = true; // Ensure it's disabled
                    // Also reset buttons for this field
                    const wrapper = input.closest('.field-wrapper');
                    if (wrapper) {
                         wrapper.querySelector('.save-btn').classList.add('hidden');
                         wrapper.querySelector('.cancel-btn').classList.add('hidden');
                         wrapper.querySelector('.edit-btn').classList.remove('hidden');
                    }
                    input.dataset.modified = "false";
                });
                // Check if any other field still has pending changes, otherwise hide global save
                let stillHasUnsavedChanges = false;
                form.querySelectorAll('.field-wrapper input').forEach(input => {
                    if (input.value !== input.dataset.original && !input.disabled) {
                         // This condition might be tricky if we just disabled them all.
                         // Simpler: if server errored, it's safest to assume we want to hide the global save until user explicitly changes something again.
                    }
                });
                // For simplicity on error, we assume all local edits are now suspect or user should re-initiate.
                // To be more precise, one would need to track which specific field failed if the error was field-specific.
                hasChange = false; // Reset hasChange as the attempt failed or succeeded.
                submitGlobal.style.display = 'none'; // Hide global save button on error, user must re-edit.

            }

        } catch (error) {
            console.error('Erreur lors de la soumission du formulaire:', error);
            alert('Une erreur technique est survenue. Veuillez réessayer.');
            // Potentially revert all fields to original and reset UI
             form.querySelectorAll('.field-wrapper input').forEach(input => {
                input.value = input.dataset.original;
                input.disabled = true;
                const wrapper = input.closest('.field-wrapper');
                if (wrapper) {
                    wrapper.querySelector('.save-btn').classList.add('hidden');
                    wrapper.querySelector('.cancel-btn').classList.add('hidden');
                    wrapper.querySelector('.edit-btn').classList.remove('hidden');
                }
                input.dataset.modified = "false";
            });
            hasChange = false;
            submitGlobal.style.display = 'none';
        }
        // finally {
            // Remove loading indicator here if added
            // e.g., submitGlobal.textContent = 'Modifier'; submitGlobal.disabled = false;
        // }
    });
});