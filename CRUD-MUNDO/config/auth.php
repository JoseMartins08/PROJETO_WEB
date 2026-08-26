<?php
    session_start();

    function verificarLogin() {
        if (!isset($_SESSION['username'])) {
            header("Location: " . caminhoLogin());
            exit();
        }
    }

    function verificarAdmin() {
        if ($_SESSION['tipo'] !== 'A') {
            die("Acesso negado: esta ação é exclusiva do administrador.")
        }
    }

    function caminhoLogin() {
        return (strpos($_SERVER['PHP_SELF'], '/paginas/') !== false) ? '../login.php' : 'login.php';
    }

    function registrarLog($conn, $username, $descricao) {
        $stmt = $conn->prepare("INSERT INTO logs (descricao, username) VALUES (?, ?)");
        $stmt->bind_param("ss", $descricao, $username);
        $stmt->execute();
        $stmt->close();
    }
?>