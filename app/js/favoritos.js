$(document).ready(function() {
        // Eliminar de favoritos
        $('.btn-eliminar-favorito').click(function(e) {
            e.preventDefault();
            const productId = $(this).data('id');
            const card = $(this).closest('.col');
            
            if (confirm('¿Eliminar este producto de tus favoritos?')) {
                $.post('eliminar_favorito.php', {
                    id_producto: productId
                }, function(response) {
                    if (response === 'ok') {
                        card.fadeOut(300, function() {
                            $(this).remove();
                            // Actualizar contador
                            const count = $('.col').length;
                            const countText = count + ' producto' + (count !== 1 ? 's' : '') + ' guardado' + (count !== 1 ? 's' : '');
                            $('.favorites-count').text(countText);
                            
                            if (count === 0) {
                                location.reload(); // Recargar si no hay más favoritos
                            }
                        });
                    } else {
                        alert('Error al eliminar de favoritos');
                    }
                }).fail(function() {
                    alert('Error de conexión');
                });
            }
        });
    });
