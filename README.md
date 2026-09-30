# Projeto Web

## Sobre o projeto
Este repositório reúne os projetos práticos desenvolvidos ao longo do curso Técnico em Desenvolvimento de Sistemas, na Etec. Cada pasta representa um projeto independente, feito em um momento diferente da formação, abordando desde front-end simples (HTML, CSS e JavaScript) até uma aplicação completa com back-end em PHP e banco de dados MySQL.

## Projetos incluídos
- [Carrinho de Compras](#carrinho-de-compras)
- [CRUD Mundo](#crud-mundo)
- [Sistema de Estatística de Turma](#sistema-de-estatística-de-turma)
- [Site Pessoal](#site-pessoal)
- [To-Do List](#to-do-list)

---

## Carrinho de Compras

### Sobre o projeto
Página de e-commerce simulando uma loja de produtos de tecnologia (peças de computador, celulares e notebooks), com carrinho de compras funcional no front-end.

### Funcionalidades
- Listagem de produtos com imagem, nome e preço
- Adicionar produtos ao carrinho
- Cálculo do total da compra
- Interação dinâmica via JavaScript

### Tecnologias utilizadas
- HTML
- CSS
- JavaScript

### Como executar
1. Acesse a pasta `CARRINHO-DE-COMPRAS`.
2. Abra o arquivo `index.html` diretamente no navegador.

---

## CRUD Mundo

### Sobre o projeto
Aplicação web completa para gerenciamento de informações geográficas (continentes, países, cidades e governantes), com sistema de login, controle de permissões por tipo de usuário e registro de logs de acesso.

### Funcionalidades
- Cadastro, listagem, edição e exclusão de continentes, países, cidades e governantes
- Login com autenticação de usuário e senha
- Bloqueio automático de acesso após tentativas de login incorretas
- Troca de senha obrigatória no primeiro acesso
- Controle de permissões: administrador (acesso total) e usuário comum (apenas consulta)
- Registro de logs das ações realizadas no sistema
- Integridade referencial no banco de dados (bloqueia exclusões que quebrariam relacionamentos)
- Busca dinâmica por nome
- Confirmação de exclusão via JavaScript

### Tecnologias utilizadas
- PHP
- MySQL
- CSS
- JavaScript

### Estrutura do projeto

```

CRUD-MUNDO/
├── index.php → página inicial
├── login.php → autenticação de usuário
├── logout.php → encerramento de sessão
├── trocar_senha.php → troca de senha do usuário
├── criar_admin.php → cria o primeiro usuário administrador
├── config/ → conexão com o banco de dados e controle de sessão
├── includes/ → cabeçalho e rodapé reutilizados nas páginas
├── css/ → estilos
├── js/ → validações e interações
├── database/ → script de criação do banco de dados
└── paginas/ → CRUD de continentes, países, cidades e governantes

```


### Requisitos
- PHP
- MySQL
- Servidor local (XAMPP, WAMP ou similar)

### Como executar
1. Importe o arquivo `database/bd_mundo.sql` no MySQL.
2. Coloque a pasta `CRUD-MUNDO` dentro do `htdocs` (XAMPP) ou `www` (WAMP).
3. Ajuste usuário e senha do banco em `config/database.php`, se necessário.
4. Acesse `criar_admin.php` para criar o primeiro usuário administrador.
5. Apague o arquivo `criar_admin.php` depois de criado o administrador.
6. Acesse `login.php` para entrar no sistema.

---

## Sistema de Estatística de Turma

### Sobre o projeto
Aplicação em PHP que recebe dados informados pelo usuário e retorna estatísticas relacionadas à turma.

### Tecnologias utilizadas
- PHP
- CSS

### Como executar
1. Coloque a pasta `SISTEMA-DE-ESTATISTICA-DE-TURMA` em um servidor local com suporte a PHP.
2. Acesse o arquivo `index.php` pelo navegador.

---

## Site Pessoal

### Sobre o projeto
Site estático de apresentação pessoal, dividido em páginas de conteúdo pessoal e acadêmico.

### Funcionalidades
- Página inicial de apresentação
- Página com informações acadêmicas
- Página com informações pessoais

### Tecnologias utilizadas
- HTML
- CSS

### Como executar
1. Acesse a pasta `SITE-PESSOAL`.
2. Abra o arquivo `index.html` diretamente no navegador.

---

## To-Do List

### Sobre o projeto
Interface de lista de tarefas desenvolvida com HTML e CSS.

### Tecnologias utilizadas
- HTML
- CSS

### Como executar
1. Acesse a pasta `TO-DO LIST`.
2. Abra o arquivo `index.html` diretamente no navegador.

---

## Licença
Este repositório está sob a licença descrita no arquivo `LICENSE`.

## Autor
José Eduardo Elhage Martins