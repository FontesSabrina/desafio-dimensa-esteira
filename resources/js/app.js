import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Função para atualizar nome do arquivo no input customizado
window.updateFileName = function(input) {
    const label = document.getElementById('file-label');
    if (input && input.files.length > 0) {
        label.innerText = "Arquivo selecionado: " + input.files[0].name;
        label.classList.add('text-blue-600', 'font-bold');
    }
};

// Máscara de CPF automática para campos com a classe .mask-cpf
document.addEventListener('DOMContentLoaded', () => {
    const cpfInputs = document.querySelectorAll('.mask-cpf');

    cpfInputs.forEach(input => {
        input.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, ''); // Remove tudo que não é número
            if (value.length > 11) value = value.slice(0, 11);

            // Aplica a máscara 000.000.000-00
            value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
            e.target.value = value;
        });
    });
});
