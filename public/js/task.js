document.addEventListener('DOMContentLoaded', () => {

    const modal = document.getElementById('taskModal');
    const form = document.getElementById('taskForm');
    const columnInput = document.getElementById('columnId');

    // OUVERTURE MODAL (event delegation)
    document.addEventListener('click', (e) => {

        const btn = e.target.closest('.add-task-btn');
        if (!btn) return;

        const columnId = btn.dataset.columnId;

        console.log('Open modal for column:', columnId);

        modal.classList.add('active');
        columnInput.value = columnId;
    });

    // FERMETURE MODAL (clic sur fond noir)
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });

    // SUBMIT AJAX
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const columnId = columnInput.value;

        const response = await fetch(`/task/create/${columnId}`, {
            method: 'POST',
            body: new FormData(form)
        });

        const data = await response.json();

        if (!response.ok) {
            console.error('Erreur backend:', data);
            return;
        }

        // 👉 On cible la colonne proprement
        const column = document.querySelector(`[data-column="${columnId}"]`);

        if (!column) {
            console.error('Colonne introuvable:', columnId);
            return;
        }

        const tasksContainer = column.querySelector('.tasks');

        if (!tasksContainer) {
            console.error('Container .tasks introuvable dans colonne:', columnId);
            return;
        }

        // Création carte
        const card = document.createElement('div');
        card.classList.add('task-card');

        card.textContent = data.important
            ? "⭐ " + data.title
            : data.title;

        tasksContainer.appendChild(card);

        // reset + fermeture modal
        form.reset();
        modal.classList.remove('active');
    });

});