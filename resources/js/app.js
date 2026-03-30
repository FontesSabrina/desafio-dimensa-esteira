import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

window.updateFileName = function(input) {
    const label = document.getElementById('file-label');
    if (label && input && input.files.length > 0) {
        label.innerText = "Arquivo selecionado: " + input.files[0].name;
        label.classList.add('text-indigo-600', 'font-bold');
    }
};

document.addEventListener('DOMContentLoaded', () => {

    const cpfInputs = document.querySelectorAll('.mask-cpf');
    cpfInputs.forEach(input => {
        input.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');

            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            }

            e.target.value = value.substring(0, 14);
        });
    });
});
