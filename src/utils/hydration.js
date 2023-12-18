function buildUrl(url, params) {
    const urlParams = new URLSearchParams(params);
    return `${url}?${urlParams.toString()}`;
}

// Adapted from :
// https://stackoverflow.com/questions/2592092/executing-script-elements-inserted-with-innerhtml
export function loadContent(url, params) {
    const content = document.getElementById('content-container');
    const spinner = document.getElementById('spinner');
    spinner.classList.remove('d-none');
    fetch(buildUrl(url, params))
        .then(response => response.text())
        .then(data => {
            content.innerHTML = data;
            content.querySelectorAll('script').forEach(currentScript => {
                const script = document.createElement('script');
                for (let attributes of currentScript.attributes) {
                    script.setAttribute(attributes.name, attributes.value);
                }
                script.appendChild(document.createTextNode(currentScript.innerHTML));
                currentScript.parentNode.replaceChild(script, currentScript);
            });
            spinner.classList.add('d-none');
        })
        .catch(error => console.error('Error fetching content:', error));
}
