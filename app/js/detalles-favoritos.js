document.querySelectorAll('.rating-stars').forEach(stars => {
    const input = document.getElementById('inputPuntuacion-' + stars.dataset.productoId);
    stars.querySelectorAll('i').forEach(star => {
        star.addEventListener('click', () => {
            const val = star.getAttribute('data-value');
            input.value = val;
            stars.querySelectorAll('i').forEach(s => {
                s.classList.remove('fas', 'far');
                s.classList.add(s.getAttribute('data-value') <= val ? 'fas' : 'far');
            });
        });
    });
});
