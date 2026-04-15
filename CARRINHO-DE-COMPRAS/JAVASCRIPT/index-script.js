const produtos = [
    {nome: "RX6700 XT", preco: 1500.00, imagem: "IMAGENS/gpu1.jpg"},
    {nome: "AMD Ryzen 5 5500", preco: 580.89, imagem: "IMAGENS/ryzen5.jpg"},
    {nome: "SSD Husky 512GB NVMe", preco: 699.00 , imagem: "IMAGENS/ssd-nvme.jpg"},
    {nome: "ASUS VivoBook Go 15", preco: 2899.80, imagem: "IMAGENS/asusvivobook.jpg"},
    {nome: "RAM Kingston Fury 16GB, 3200MHz DDR4", preco: 849.00, imagem: "IMAGENS/memoria-ram.jpg"},
    {nome: "Intel Ultra 5 245k 4.2GHz", preco: 1889.00, imagem: "IMAGENS/intel-ultra5.jpg"},
    {nome: "Water Cooler Aorus 360mm", preco: 567.90, imagem: "IMAGENS/watercooleraorus.jpg"},
    {nome: "RTX 3060, 12GB GDDR6", preco: 1999.00, imagem:"IMAGENS/rtx3060.jpg"},
    {nome: "Gabinete Rise Mode Galaxy Glass", preco: 184.99, imagem:"IMAGENS/gabineterisemode.jpg"},
    {nome: "Fonte Corsair 750W", preco: 519.00, imagem:"IMAGENS/fontecorsair.jpg"},
    {nome: "SSD Kingston 960GB 2.5' SATA", preco: 899.99, imagem:"IMAGENS/ssdsata.jpg"},
    {nome: "Air Cooler Rise Mode Storm 8", preco: 199.99, imagem:"IMAGENS/aircooler.jpg"},
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
            <button class="btn-adicionar">+ Adicionar</button>
        `;

        const botao = card.querySelector(".btn-adicionar");

        botao.addEventListener("click", function(){
            addAoCarrinho(produto);
        })

        lista.appendChild(card);
    });
};

document.addEventListener("DOMContentLoaded", function() {
    listarProdutos();
});

function addAoCarrinho(produto) {
    let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];

    carrinho.push(produto);

    localStorage.setItem("carrinho", JSON.stringify(carrinho));

    mostrarCarrinho();
}

function mostrarCarrinho() {
    const lista = document.querySelector("#lista-carrinho");
    const totalTexto = document.querySelector("#total-compra");

    let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];

    lista.innerHTML = "";

    if (carrinho.lenght === 0) {
        lista.innerHTML = "<p>Carrinho vazio.</p>";
        totalTexto.innerHTML = "<strong>Total: R$ 0,00</strong>";

        return
    }

    let total = 0;

    carrinho.forEach(function(produto, index) {
        const item = document.createElement("p");

        item.innerHTML =
            `${produto.nome} - R$ ${produto.preco.toFixed(2)}
            <button class="btn-remover" onclick="removerItem(${index})">-</button`;

        lista.appendChild(item);

        total += produto.preco;
    });

    totalTexto.innerHTML = `<strong>Total: R$ ${total.toFixed(2)}</strong>`;
}

function removerItem(index) {
    let carrinho = JSON.parse(localStorage.getItem("carrinho")) || [];

    carrinho.splice(index, 1);

    localStorage.setItem("carrinho", JSON.stringify(carrinho));

    mostrarCarrinho();
}

document.querySelector("#btn-limpar").addEventListener("click", function() {
    localStorage.removeItem("carrinho");

    mostrarCarrinho();
})

document.addEventListener("DOMContentLoaded", function() {
    listarProdutos();

    mostrarCarrinho();
})