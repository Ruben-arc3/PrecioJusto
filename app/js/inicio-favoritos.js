document.addEventListener("DOMContentLoaded", function () {
    // Toast para usuarios no autenticados en favoritos
    document.querySelectorAll('.product-favorite.unauthenticated').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const toast = new bootstrap.Toast(document.getElementById('toastLogin'));
            toast.show();
        });
    });

    // AJAX para favoritos
    document.querySelectorAll('.product-favorite:not(.unauthenticated)').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const productoId = this.dataset.id;
            const accion = this.dataset.accion;
            const icon = this.querySelector('i');
            const self = this;

            fetch(`favoritos_ajax.php?accion=${accion}&producto_id=${productoId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (data.action === 'agregado') {
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                            self.dataset.accion = 'quitar';
                            self.setAttribute('title', 'Quitar de favoritos');
                        } else {
                            icon.classList.remove('fas');
                            icon.classList.add('far');
                            self.dataset.accion = 'agregar';
                            self.setAttribute('title', 'Agregar a favoritos');
                        }
                    }
                })
                .catch(err => console.error('Error:', err));
        });
    });

    // Función para mostrar toast genérico (valoraciones)
    function showToast(tipo, mensaje) {
        const toastElem = document.getElementById('toastMensaje');
        const toastBody = toastElem.querySelector('.toast-body');

        // Remover clases de color previas
        toastElem.classList.remove('text-bg-success', 'text-bg-info', 'text-bg-danger');
        toastElem.classList.add('toast', 'align-items-center', 'border-0', 'show');
        if (tipo === 'success') {
            toastElem.classList.add('text-bg-success');
        } else if (tipo === 'info') {
            toastElem.classList.add('text-bg-info');
        } else if (tipo === 'danger') {
            toastElem.classList.add('text-bg-danger');
        }

        toastBody.innerHTML = mensaje;

        const bsToast = new bootstrap.Toast(toastElem);
        bsToast.show();
    }

    // AJAX para valoraciones (estrellas)
    document.querySelectorAll('.rating-stars i').forEach(star => {
        star.addEventListener('click', function () {
            const productoId = this.parentElement.getAttribute('data-producto-id');
            const puntuacion = this.getAttribute('data-value');

            fetch(window.location.href, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    accion: 'valorar',
                    producto_id: productoId,
                    puntuacion: puntuacion
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Actualizar visualmente las estrellas para ese producto
                    const estrellasContenedor = document.querySelector(`.rating-stars[data-producto-id="${productoId}"]`);
                    if (estrellasContenedor) {
                        estrellasContenedor.querySelectorAll('i').forEach((starIcon, idx) => {
                            if (idx < puntuacion) {
                                starIcon.classList.remove('far');
                                starIcon.classList.add('fas');
                                starIcon.classList.remove('fa-star-half-alt');
                            } else {
                                starIcon.classList.remove('fas');
                                starIcon.classList.add('far');
                                starIcon.classList.remove('fa-star-half-alt');
                            }
                        });
                    }
                    showToast('info', data.message || 'Error al valorar');
                }
            })
            .catch(() => {
                showToast('danger', 'Error en la conexión');
            });
        });
    });

});
