document.addEventListener('DOMContentLoaded', () => {

    const modal = document.getElementById('taskModal');
    const form = document.getElementById('taskForm');
    const columnInput = document.getElementById('columnId');

    let isSubmitting = false;

    // =========================
    // OUVERTURE MODAL
    // =========================
    document.addEventListener('click', (e) => {

        const btn = e.target.closest('.add-task-btn');
        if (!btn) return;

        const columnId = btn.dataset.columnId;

        columnInput.value = columnId;

        modal.classList.add('active');
    });

    // =========================
    // FERMETURE MODAL
    // =========================
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.remove('active');
        }
    });

    // =========================
    // SUBMIT AJAX
    // =========================
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        e.stopPropagation(); // 🔥 important

        if (form.dataset.locked === "1") return;
        form.dataset.locked = "1";

        try {
            const columnId = columnInput.value;

            const response = await fetch(`/task/create/${columnId}`, {
                method: 'POST',
                body: new FormData(form)
            });

            const data = await response.json();

            if (!response.ok) return;

            const column = document.querySelector(`[data-column="${columnId}"] .tasks`);

            const card = document.createElement('div');
            card.classList.add('task-card');

            card.innerHTML = data.important
                ? "⭐ " + data.title
                : data.title;

            column.appendChild(card);

            form.reset();
            modal.classList.remove('active');

        } finally {
            form.dataset.locked = "0";
        }
    });

    // Menu burger pour le aside
    const burgerBtn = document.getElementById('burgerBtn');
    const aside = document.querySelector('.workspace-aside');

    if (burgerBtn && aside) {
        burgerBtn.addEventListener('click', () => {
            aside.classList.toggle('open');
        });

        // bonus : fermer si clic extérieur
        document.addEventListener('click', (e) => {
            const isClickInsideAside = aside.contains(e.target);
            const isClickBurger = burgerBtn.contains(e.target);

            if (!isClickInsideAside && !isClickBurger) {
                aside.classList.remove('open');
            }
        });
    }

    // Ouverture / fermeture des colonnes
    document.addEventListener('click', (e) => {

        const header = e.target.closest('.column-header');
        if (!header) return;

        const column = header.closest('.column');

        document.querySelectorAll('.column').forEach(col => {
            if (col !== column) {
                col.classList.add('closed');
            }
        });

        column.classList.toggle('closed');

    });
});