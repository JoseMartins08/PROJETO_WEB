const produtos = [
    {nome: "Placa de Vídeo", preco: 1500.00, imagem: "IMAGENS/gpu.jpg"},
    {nome: "Processador", preco: 600.00, imagem: "IMAGENS/cpu.jpg"},
    {nome: "Water Cooler", preco: 500.00 , imagem: "watercooler.jpg"}
];

function listarProdutos() {
    const lista = document.querySelector("#lista-produtos");
    lista.innerHTML = "";

    produtos.forEach(function(produto) {
        const card = document.createElement("div");
        card.className = "card-produto";

        card.innerHTML = `
            <img src="${produto.imagem}" alt="${produto.nome}">
            <div class="info-produto">
                <span class="nome-produto">${produto.nome}</span>
                <span class="preco-produto">R$ ${produto.preco.toFixed(2)}</span>
            </div>
            <button class="btn-adicionar">Adicionar</button>
        `;

        lista.appendChild(card);
    });
}

document.addEventListener("DOMContentLoaded", function() {
    listarProdutos();
})