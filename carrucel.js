// Control del carrusel
const prev = document.querySelector('.carousel-control-prev');
const next = document.querySelector('.carousel-control-next');
const carouselInner = document.querySelector('.carousel-inner');
const indicators = document.querySelectorAll('.carousel-indicators button');
let index = 0;

function showSlide(i) {
    const items = document.querySelectorAll('.carousel-item');
    if (i >= items.length) {
        index = 0;
    } else if (i < 0) {
        index = items.length - 1;
    } else {
        index = i;
    }

    // Actualizar la posición del carrusel
    carouselInner.style.transform = `translateX(-${index * 100}%)`;
    
    // Actualizar indicadores
    indicators.forEach((indicator, idx) => {
        if (idx === index) {
            indicator.classList.add('active');
        } else {
            indicator.classList.remove('active');
        }
    });
}

// Eventos del carrusel
if (prev) {
    prev.addEventListener('click', function(e) {
        e.preventDefault();
        showSlide(index - 1);
    });
}

if (next) {
    next.addEventListener('click', function(e) {
        e.preventDefault();
        showSlide(index + 1);
    });
}

// Eventos para los indicadores del carrusel
indicators.forEach((indicator, idx) => {
    indicator.addEventListener('click', () => {
        showSlide(idx);
    });
});

// Inicializar el carrusel
showSlide(0);

// Configurar transición automática del carrusel
setInterval(() => {
    showSlide(index + 1);
}, 5000); // Cambiar imagen cada 5 segundos
