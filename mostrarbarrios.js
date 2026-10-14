function actualizarBarrios() {
    var municipio = document.getElementById("municipio").value;
    var lugarSelect = document.getElementById("barrio");
    lugarSelect.innerHTML = ""; // Limpiar las opciones actuales
    var barrios = {
        "atizapan": ["lomas verdes", "lomas lindas", "bodegas", "alamedas", "centro"],
        "nicolas": ["Colmena", "Curva", "Progreso", "Olivos", "el vidrios"]
    };

    if (municipio in barrios) {
        barrios[municipio].forEach(function(barrio) {
            var option = document.createElement("option");
            option.value = barrio.toLowerCase();
            option.text = barrio;
            lugarSelect.appendChild(option);
        });
    } else {
        var option = document.createElement("option");
        option.value = "";
        option.text = "Selecciona un municipio primero";
        lugarSelect.appendChild(option);
    }
}