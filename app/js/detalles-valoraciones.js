document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.product-favorite').forEach(btn => {
        // Elimina posibles duplicaciones de eventos
        btn.replaceWith(btn.cloneNode(true));
    });

    document.querySelectorAll('.product-favorite').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault(); // Prevenir cualquier envío si accidentalmente hay un <form>

            const productoId = this.dataset.id;
            const accion = this.dataset.accion;
            const icon = this.querySelector('i');
            const span = this.querySelector('span');
            const self = this;

            fetch(`?accion=${accion}&producto_id=${productoId}&ajax=1`)
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (data.action === 'agregado') {
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                            span.textContent = 'Quitar de favoritos';
                            self.dataset.accion = 'quitar';
                            self.setAttribute('title', 'Quitar de favoritos');
                        } else if (data.action === 'quitado') {
                            icon.classList.remove('fas');
                            icon.classList.add('far');
                            span.textContent = 'Agregar a favoritos';
                            self.dataset.accion = 'agregar';
                            self.setAttribute('title', 'Agregar a favoritos');
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    alert('Error de conexión');
                });
        });
    });
});
