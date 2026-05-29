export function createToast(title, message) {
    // Create a new toast element
    let toast = document.createElement('div');
    toast.classList.add('toast');
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');

    // Toast header
    let toastHeader = document.createElement('div');
    toastHeader.classList.add('toast-header');

    let img = document.createElement('img');
    img.src = './res/car.png';
    img.classList.add('rounded', 'me-2');
    img.width = 16;
    img.height = 16;
    img.style.background = 'gray';

    let strong = document.createElement('strong');
    strong.classList.add('me-auto');
    strong.textContent = title;

    let small = document.createElement('small');
    small.textContent = 'Maintenant';

    let closeButton = document.createElement('button');
    closeButton.type = 'button';
    closeButton.classList.add('btn-close');
    closeButton.setAttribute('data-bs-dismiss', 'toast');
    closeButton.setAttribute('aria-label', 'Close');

    // Assemble the header
    toastHeader.appendChild(img);
    toastHeader.appendChild(strong);
    toastHeader.appendChild(small);
    toastHeader.appendChild(closeButton);

    // Toast body
    let toastBody = document.createElement('div');
    toastBody.classList.add('toast-body');

    // Test if psql error or php error
    if (message.includes("ERROR:") && message.includes("\n")) {
        const errorRegex = /ERROR: (.+?)\n/;
        var match = message.match(errorRegex);
        var truncatedError = match ? match[1].trim() : "Détails indisponibles";
        toastBody.textContent = truncatedError
    } else {
        toastBody.textContent = message
    }

    // Assemble the toast
    toast.appendChild(toastHeader);
    toast.appendChild(toastBody);

    // Add the toast to the toast container
    let toastContainer = document.getElementById('toast-container');
    toastContainer.appendChild(toast);

    // Initialize the Bootstrap toast
    let bootstrapToast = new bootstrap.Toast(toast);
    bootstrapToast.show();
}