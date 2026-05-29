let currentMenu = null;
let backButton = null;
let breadcrumb = null;

function updateNavbar(menu) {
    if (currentMenu !== null) {
        currentMenu.button.classList.remove('active');
        currentMenu.button.style.fontWeight = 'normal';
    }

    menu.button.classList.add('active');
    menu.button.style.fontWeight = 'bold';

    currentMenu = menu;
}

function createNode(menu, isLast) {
    const node = document.createElement('li');
    node.classList.add('breadcrumb-item');
    if (!isLast) {
        const link = document.createElement('a');
        link.href = '#';
        link.innerHTML = menu.name;
        link.onclick = () => {
            menu.load();
        };
        node.appendChild(link);
    } else {
        node.ariaCurrent = 'page';
        node.innerHTML = menu.name;
        node.classList.add('active');
    }
    return node;
}

function updateBreadcrumb(path) {
    if (breadcrumb === null)
        breadcrumb = document.getElementById('breadcrumb');
    if (breadcrumb === null)
        console.error('Cannot find breadcrumb');
    breadcrumb.innerHTML = '';
    for (let i = 0; i < path.length; i++) {
        const node = createNode(path[i], (i === path.length - 1));
        breadcrumb.appendChild(node);
    }
}

function updateBackButton(path) {
    if (backButton === null)
        backButton = document.getElementById('back-button');
    if (backButton === null)
        console.error('Cannot find back button');
    if (path.length > 1)
        backButton.classList.remove('d-none');
    else
        backButton.classList.add('d-none');
}

export function updateNavigation(path) {
    updateNavbar(path[0]);
    updateBreadcrumb(path);
    updateBackButton(path);
}
