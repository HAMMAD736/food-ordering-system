document.addEventListener('DOMContentLoaded', () => {
    const confirmationTriggers = document.querySelectorAll('.confirm-action');
    
    confirmationTriggers.forEach(trigger => {
        trigger.addEventListener('click', (event) => {
            const statusConfirmation = confirm("Confirm System Execution Request? This structural modification can't be undone.");
            if (!statusConfirmation) {
                event.preventDefault();
            }
        });
    });
});