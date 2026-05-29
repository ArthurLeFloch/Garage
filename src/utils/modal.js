
function cleanModal() {
    document.body.classList.remove('modal-open');

    document.querySelectorAll('.modal').forEach((element) => {
        element.remove();
    });

    document.querySelectorAll('.modal-backdrop').forEach((element) => {
        element.remove();
    });
}

export function createConfirmModal(title, content, onConfirm) {
    cleanModal();

    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.setAttribute('tabindex', '-1');
    modal.innerHTML = `
        <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">${title}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p>${content}</p>
        </div>
        <div class="modal-footer">
            <a class="btn btn-secondary" data-bs-dismiss="modal" id="cancel">Annuler</a>
            <a class="btn btn-danger" data-bs-dismiss="modal" id="confirm">Confirmer</a>
        </div>
        </div>
        </div>
    `;

    document.body.appendChild(modal);

    const modalInstance = new bootstrap.Modal(modal);
    modalInstance.show();

    modal.querySelector('#cancel').addEventListener('click', () => {
        cleanModal();
    });

    modal.querySelector('#confirm').addEventListener('click', () => {
        cleanModal();
        onConfirm.apply(null);
    });

    modal.addEventListener('hidden.bs.modal', function () {
        cleanModal();
    });
}
