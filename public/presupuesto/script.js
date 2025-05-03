// Esperamos a que el documento esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Buscamos y reemplazamos el texto
    const textToReplace = 'El cumpleaños de Laura';
    const newText = 'El cumpleaños de Pedro';
    
    // Recorremos todos los elementos del documento
    const elements = document.body.getElementsByTagName('*');
    
    // Para cada elemento, verificamos si contiene el texto
    for (let i = 0; i < elements.length; i++) {
        const element = elements[i];
        // Solo procesamos elementos que pueden contener texto
        if (element.nodeType === Node.ELEMENT_NODE && element.textContent) {
            if (element.textContent.includes(textToReplace)) {
                element.textContent = element.textContent.replace(textToReplace, newText);
            }
        }
    }
});
