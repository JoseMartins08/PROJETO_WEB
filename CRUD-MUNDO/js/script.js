// Confirmação de Exclusão
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('btn-excluir')) {
        const confirmou = confirm('Tem certeza que deseja excluir este registro?');
        if (!confirmou) {
            e.preventDefault();
        }
    }
});

// Validação simples de campos obrigatórios antes do envio
document.addEventListener('submit', function (e) {
    const obrigatorios = e.target.querySelectorAll('[required]');
    let valido = true;

    obrigatorios.forEach(campo => {
        if (!campo.value.trim()) {
            valido = false;
            campo.style.borderColor = 'red';
        }
        else {
            campo.style.borderColor = '#ccc';
        }
    });

    if (!valido) {
        e.preventDefault();
        alert('Preencha todos os campos obrigatórios.');
    }
});

// Busca dinâminca nas tabelas (filtra pelo texto digitando)
document.addEventListener('DOMContentLoaded', function () {
    const busca = document.getElementById('busca');
    
    if (!busca) return;

    busca.addEventListener('input', function () {
        const termo = busca.value.toLowerCase();
        const linhas = document.querySelectorAll('table tbody tr');

        linhas.forEach(linha => {
            const texto = linha.textContent.toLowerCase();
            linha.style.display = texto.includes(termo) ? '' : 'none';
        });
    });
});