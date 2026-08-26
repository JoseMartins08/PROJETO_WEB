<?php
require_once 'config/database.php';
require_once 'config/auth.php';

// Se já está logado, manda direto pro sistema
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$usuario) {
        $erro = "Usuário ou senha inválidos.";
    } elseif ($usuario['status'] == 'B') {
        $erro = "Este usuário está bloqueado por excesso de tentativas incorretas. Procure o administrador.";
    } elseif ($usuario['status'] == 'I') {
        $erro = "Este usuário está inativo. Procure o administrador.";
    } elseif (!password_verify($senha, $usuario['senha'])) {
        // Senha errada: soma tentativa e bloqueia na 3ª consecutiva
        $tentativas = $usuario['qtde_acesso'] + 1;

        if ($tentativas >= 3) {
            $stmt = $conn->prepare("UPDATE usuarios SET qtde_acesso = ?, status = 'B' WHERE username = ?");
            $stmt->bind_param("is", $tentativas, $username);
            $stmt->execute();
            $stmt->close();
            registrarLog($conn, $username, "Usuário bloqueado após 3 tentativas incorretas consecutivas.");
            $erro = "Senha incorreta. Usuário bloqueado após 3 tentativas. Procure o administrador.";
        } else {
            $stmt = $conn->prepare("UPDATE usuarios SET qtde_acesso = ? WHERE username = ?");
            $stmt->bind_param("is", $tentativas, $username);
            $stmt->execute();
            $stmt->close();
            registrarLog($conn, $username, "Tentativa de login com senha incorreta.");
            $erro = "Senha incorreta. Tentativa $tentativas de 3.";
        }
    } else {
        // Login correto: zera tentativas e inicia sessão
        $stmt = $conn->prepare("UPDATE usuarios SET qtde_acesso = 0 WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->close();

        $_SESSION['username'] = $usuario['username'];
        $_SESSION['nome'] = $usuario['nome'];
        $_SESSION['tipo'] = $usuario['tipo'];

        registrarLog($conn, $username, "Login realizado com sucesso.");

        if ($usuario['primeiro_acesso'] == 'S') {
            header("Location: trocar_senha.php");
        } else {
            header("Location: index.php");
        }
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - CRUD Mundo</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="login-container">
    <div class="login-box">
        <h1>🌍 CRUD Mundo</h1>
        <p class="login-subtitulo">Faça login para continuar</p>

        <?php if ($erro): ?>
            <div class="mensagem erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="campo">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            <div class="campo">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <button type="submit" class="btn-salvar btn-login">OK</button>
        </form>
    </div>
</div>
</body>
</html>