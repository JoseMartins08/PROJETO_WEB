<?php
require_once 'config/database.php';
require_once 'config/auth.php';
verificarLogin();

$erro = '';
$sucesso = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $senha_atual = $_POST['senha_atual'];
    $nova_senha = $_POST['nova_senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    $stmt = $conn->prepare("SELECT senha FROM usuarios WHERE username = ?");
    $stmt->bind_param("s", $_SESSION['username']);
    $stmt->execute();
    $usuario = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!password_verify($senha_atual, $usuario['senha'])) {
        $erro = "Senha atual incorreta.";
    } elseif (strlen($nova_senha) < 6) {
        $erro = "A nova senha deve ter pelo menos 6 caracteres.";
    } elseif ($nova_senha !== $confirmar_senha) {
        $erro = "A confirmação não confere com a nova senha.";
    } else {
        $nova_senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE usuarios SET senha = ?, primeiro_acesso = 'N' WHERE username = ?");
        $stmt->bind_param("ss", $nova_senha_hash, $_SESSION['username']);
        $stmt->execute();
        $stmt->close();

        registrarLog($conn, $_SESSION['username'], "Senha alterada pelo usuário.");
        $sucesso = "Senha alterada com sucesso!";
    }
}

$base = '';
include 'includes/header.php';
?>

<h2>Trocar senha</h2>

<?php if ($erro): ?>
    <div class="mensagem erro"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>
<?php if ($sucesso): ?>
    <div class="mensagem sucesso"><?= htmlspecialchars($sucesso) ?> <a href="index.php">Continuar</a></div>
<?php endif; ?>

<div class="form-container" style="max-width: 400px;">
    <form method="POST">
        <div class="campo" style="margin-bottom: 1rem;">
            <label for="senha_atual">Senha atual</label>
            <input type="password" id="senha_atual" name="senha_atual" required>
        </div>
        <div class="campo" style="margin-bottom: 1rem;">
            <label for="nova_senha">Nova senha</label>
            <input type="password" id="nova_senha" name="nova_senha" required minlength="6">
        </div>
        <div class="campo" style="margin-bottom: 1rem;">
            <label for="confirmar_senha">Confirmar nova senha</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" required minlength="6">
        </div>
        <button type="submit" class="btn-salvar">Salvar nova senha</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>