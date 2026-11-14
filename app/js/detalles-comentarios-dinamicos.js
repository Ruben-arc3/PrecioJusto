// Función para cargar comentarios
function cargarComentarios() {
    $.ajax({
        url: 'obtener_comentarios.php',
        method: 'GET',
        data: { id_producto: Number(document.body.dataset.productId) },
        success: function(response) {
            $('#comentarios-container').html(response);
            inicializarEventosComentarios();
        }
    });
}

// Inicializar eventos para editar/eliminar comentarios
function inicializarEventosComentarios() {
    // Botón editar comentario
    $('.btn-editar-comentario').click(function() {
        const idComentario = $(this).data('id');
        const comentarioBox = $(this).closest('.comentario-box');
        const contenido = comentarioBox.find('p').text().trim();
        
        // Crear formulario de edición
        const formEdicion = `
            <form class="form-edicion-comentario mt-3" data-id="${idComentario}">
                <textarea class="form-control mb-2" rows="3">${contenido}</textarea>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                    <button type="button" class="btn btn-secondary btn-sm cancelar-edicion">Cancelar</button>
                </div>
            </form>
        `;
        
        // Ocultar contenido y botones, mostrar formulario
        comentarioBox.find('p, .btn-group').hide();
        comentarioBox.append(formEdicion);
    });
    
    // Cancelar edición
    $(document).on('click', '.cancelar-edicion', function() {
        const form = $(this).closest('.form-edicion-comentario');
        form.remove();
        form.closest('.comentario-box').find('p, .btn-group').show();
    });
    
    // Enviar edición
    $(document).on('submit', '.form-edicion-comentario', function(e) {
        e.preventDefault();
        const form = $(this);
        const idComentario = form.data('id');
        const nuevoContenido = form.find('textarea').val().trim();
        
        $.ajax({
            url: 'editar_comentario.php',
            method: 'POST',
            data: {
                id_comentario: idComentario,
                contenido: nuevoContenido
            },
            success: function(response) {
                if (response === 'ok') {
                    cargarComentarios();
                } else {
                    alert('Error al editar el comentario');
                }
            }
        });
    });
    
    // Eliminar comentario
    $('.btn-eliminar-comentario').click(function() {
        if (confirm('¿Estás seguro de eliminar este comentario?')) {
            const idComentario = $(this).data('id');
            
            $.ajax({
                url: 'eliminar_comentario.php',
                method: 'POST',
                data: { id_comentario: idComentario },
                success: function(response) {
                    if (response === 'ok') {
                        cargarComentarios();
                    } else {
                        alert('Error al eliminar el comentario');
                    }
                }
            });
        }
    });
}

// Cargar comentarios al inicio
$(document).ready(function() {
    cargarComentarios();
    
    // Enviar nuevo comentario
    $('#form-comentario').submit(function(e) {
        e.preventDefault();
        
        $.ajax({
            url: 'guardar_comentario.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response === 'ok') {
                    cargarComentarios();
                    $('#form-comentario textarea').val('');
                } else {
                    alert('Error al guardar el comentario');
                }
            }
        });
    });
});
