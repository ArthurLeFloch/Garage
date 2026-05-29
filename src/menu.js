import { loadContent } from './utils/hydration.js';
import { updateNavigation } from './utils/navigation.js';

// MENU -----------------------------------------------------------------------

let nav = null;
const path = [];

let id = 0;

function addMenu(menu) {
    path.push({
        id: menu.id,
        page: menu.page,
        name: menu.name,
        button: menu.button || null,
        load: menu.load,
        data: menu.data
    });
    updateNavigation(path);
    loadContent(menu.page, menu.data);
}

function findMenu(menu) {
    const result = path.findIndex((element) => element.id === menu.id);
    if (result === -1) {
        throw new Error('Menu not found');
    }
    return result;
}

// SET MENU -------------------------------------------------------------------

export function menuBuildRoot(page, name) {
    if (nav === null) {
        nav = document.getElementById('navbar');
    }

    const node = document.createElement('li');
    node.classList.add('nav-item');

    const button = document.createElement('a');
    button.classList.add('nav-link');
    button.href = '#';
    button.textContent = name;

    button.id = nameToId(name);

    node.appendChild(button);
    nav.appendChild(node);

    const menu = {
        id: id,
        page: page,
        name: name,
        button: button,
        data: {},
        load: () => menuSetRoot(menu)
    };
    id++;

    button.addEventListener('click', menu.load);
    return menu;
}

export function menuBuildChild(parentMenu, page, name) {
    const menu = {
        id: id,
        parent: parentMenu,
        page: page,
        name: name,
        data: {},
        load: () => menuSetChild(menu)
    };
    id++;
    return menu;
}

export function menuSetRoot(menu) {
    path.splice(0);
    addMenu(menu, true);
}

function nameToId(name) {
    let id = '';
    for (const char of name.toLowerCase()) {
        if (char >= 'a' && char <= 'z') {
            id += char;
        } else if (char === ' ') {
            id += '-';
        }
    }
    return id;
}

export function menuInflate(menu) {
    menuSetRoot(menu);
    document.getElementById('back-button').addEventListener('click', menuBack);
}

export function menuSetChild(menu) {
    const parentIndex = findMenu(menu.parent);
    path.splice(parentIndex + 1);

    // Child inherits parent data, can overwrite it
    for (const key in path[parentIndex].data) {
        menu.data[key] = path[parentIndex].data[key];
    }
    addMenu(menu);
}

export function menuReload() {
    path[path.length - 1].load();
}

export function menuBack() {
    path.pop();
    path[path.length - 1].load();
}

// SET DATA -------------------------------------------------------------------

export function setData(key, value) {
    const index = path.length - 1;
    path[index].data[key] = value;
}

export function getData(key) {
    const index = path.length - 1;
    return path[index].data[key];
}
