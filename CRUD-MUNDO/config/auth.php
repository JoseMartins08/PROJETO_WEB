<?php
session_start();

// Bloqueia acesso a quem não estiver logado
function verificarLogin() {
    if (!isset($_SESSION['username'])) {
        header("Location: " . caminhoLogin());
        exit();
    }
}

// Bloqueia páginas/ações restritas ao administrador
function verificarAdmin() {
    if ($_SESSION['tipo'] !== 'A') {
        die("Acesso negado: esta ação é exclusiva do administrador.");
    }
}

// Descobre o caminho relativo do login.php dependendo de onde o script está
function caminhoLogin() {
    return (strpos($_SERVER['PHP_SELF'], '/paginas/') !== false) ? '../login.php' : 'login.php';
}

// Registra uma ação no log
function registrarLog($conn, $username, $descricao) {
    $stmt = $conn->prepare("INSERT INTO logs (descricao, username) VALUES (?, ?)");
    $stmt->bind_param("ss", $descricao, $username);
    $stmt->execute();
    $stmt->close();
}