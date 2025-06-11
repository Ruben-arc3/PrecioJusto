// Agregar animación al hacer clic en favoritos
document.addEventListener('DOMContentLoaded', function() {
    const favButtons = document.querySelectorAll('.product-favorite');
    
    favButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (this.getAttribute('href') !== '#') {
                const icon = this.querySelector('i');
                icon.classList.add('heart-animation');
                
                setTimeout(() => {
                    icon.classList.remove('heart-animation');
                }, 500);
            }
        });
    });
});