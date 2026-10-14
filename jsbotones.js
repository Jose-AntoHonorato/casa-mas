// Comprobar el estado de inicio de sesión al cargar la página
document.addEventListener("DOMContentLoaded", function() {
    actualizarEstadoSesion();
});

// Evento para el enlace de "Publicación de Casa"
document.getElementById("enlacePublicacion").addEventListener("click", function(event) {
    if (!estaSesionado()) {
        event.preventDefault(); // Evita que el enlace navegue
        // Mostrar un cuadro de diálogo informando que se debe iniciar sesión
        mostrarDialogoSesion();
    }
});

// Comprobar si el usuario ha iniciado sesión
function estaSesionado() {
    return localStorage.getItem("usuarioLogueado") === "true";
}
// Función para mostrar un cuadro de diálogo informando que se debe iniciar sesión
function mostrarDialogoSesion() {
    alert("Debes iniciar sesión para poder publicar una casa.");
}
// Función para manejar el inicio de sesión
document.getElementById("iniciarSesion").addEventListener("click", function(event) {
    // Simulamos un inicio de sesión. En un caso real, se autenticaría con un backend.
    localStorage.setItem("usuarioLogueado", "true");
    actualizarEstadoSesion();
});

// Función para manejar el cierre de sesión
document.getElementById("cerrarSesion").addEventListener("click", function() {
    localStorage.setItem("usuarioLogueado", "false");
    actualizarEstadoSesion();
});

// Función para mostrar un cuadro de diálogo informando que se debe iniciar sesión
function mostrarDialogoSesion() {
    alert("Debes iniciar sesión para poder publicar una casa.");
}

// Función para actualizar el estado de la sesión
function actualizarEstadoSesion() {
    const botonIniciarSesion = document.getElementById("iniciarSesion");
    const botonCerrarSesion = document.getElementById("cerrarSesion");
    const enlacePublicacion = document.getElementById("enlacePublicacion");

    if (estaSesionado()) {
        // Si el usuario está logueado, muestra el botón de cerrar sesión y oculta el de iniciar sesión
        botonIniciarSesion.style.display = "none";
        botonCerrarSesion.style.display = "block";
        enlacePublicacion.style.pointerEvents = "auto"; // Habilita el enlace de publicación
    } else {
        // Si el usuario no está logueado, muestra el botón de iniciar sesión y oculta el de cerrar sesión
        botonIniciarSesion.style.display = "block";
        botonCerrarSesion.style.display = "none";
        enlacePublicacion.style.pointerEvents = "block"; // Desactiva el enlace de publicación
    }
}
