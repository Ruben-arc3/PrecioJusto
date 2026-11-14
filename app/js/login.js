document.querySelectorAll('.toggle-password').forEach((button) => {
    button.addEventListener('click', function () {
        const input = this.parentElement.querySelector('input');
        const icon = this.querySelector('i');
        const showing = input.type === 'password';
        input.type = showing ? 'text' : 'password';
        icon.classList.toggle('fa-eye', !showing);
        icon.classList.toggle('fa-eye-slash', showing);
    });
});

if (document.body.dataset.showRegister === '1') {
    const registerTab = document.getElementById('register-tab');
    if (registerTab) new bootstrap.Tab(registerTab).show();
}
