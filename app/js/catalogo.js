$(document).ready(function() {
        // Manejar favoritos
        $('.btn-favorite').click(function(e) {
            e.preventDefault();
            const productId = $(this).data('id');
            const isFavorite = $(this).hasClass('active');
            
            $.post('gestionar_favorito.php', {
                id_producto: productId,
                accion: isFavorite ? 'quitar' : 'agregar'
            }, function(response) {
                if (response === 'ok') {
                    $('.btn-favorite[data-id="' + productId + '"]')
                        .toggleClass('active')
                        .find('i')
                        .toggleClass('fas far');
                } else {
                    alert('Error al actualizar favoritos');
                }
            });
        });

        // Autocompletado del buscador
        const buscador = document.getElementById('buscadorProductos');
        const datalist = document.getElementById('sugerenciasProductos');
        let timeoutId;

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
