document.addEventListener("DOMContentLoaded", function() {
    const buscador = document.getElementById('buscadorProductos');
    const datalist = document.getElementById('sugerenciasProductos');
    let timeoutId;

    // Función para buscar sugerencias
    function buscarSugerencias(termino) {
        if (termino.length < 2) {
            datalist.innerHTML = '';
            return;
        }

        fetch(`buscar_sugerencias.php?termino=${encodeURIComponent(termino)}`)
            .then(response => response.json())
            .then(data => {
                datalist.innerHTML = '';
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.nombre;
                    datalist.appendChild(option);
                });
            })
            .catch(error => console.error('Error:', error));
    }

    // Evento de entrada con debounce
    buscador.addEventListener('input', function() {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            buscarSugerencias(this.value);
        }, 300);
    });

    // Opcional: precargar sugerencias populares al enfocar el campo
    buscador.addEventListener('focus', function() {
        if (this.value.length < 2) {
            buscarSugerencias('');
        }
    });
});
