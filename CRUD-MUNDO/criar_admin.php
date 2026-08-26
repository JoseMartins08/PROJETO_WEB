<?php
    require_once 'config/database.php';

    $mensagem = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = trim($_POST['username']);
        $senha = $_POST['senha'];
        $nome = trim($_POST['nome'])

        if ($username === '' || $senha === '' || $nome === '') {
            $mensagem = "Preencha todos os campos.";
        } else {
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO usuarios (username, senha, nome, status, tipo, qtde_acesso, primeiro_acesso) VALUES (?, ?, ?, 'A', 'A', 0, 'N')");

            $stmt->bind_param("sss", $username, $senha_hash, $nome);

            if ($stmt->execute()) {
                $mensagem = "Administrador '$username' criado com sucesso! Agora apague este arquivo (criar_admin.php) por segurança.";
            } else {
                $mensagem = "Erro: já existe um usuário com esse username, ou" . $stmt->error;
            }
            $stmt->close;
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Criar administrador</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <main>
            <h2>Criar usuário Administrador</h2>
            <?php if ($mensagem): ?>
                <div class="mensagem sucesso"><?= htmlspecialchars($mensagem) ?></div>
            <? endif; ?>

            <div class="form-container">
                <form method="post">
                    <div class="campo" style="margin-bottom: 1rem;">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required>
                    </div>

                    <div class="campo" style="margin-bottom: 1rem;">
                        <label for="nome">Nome completo</label>
                        <input type="text" id="nome" name="nome" required>
                    </div>

                    <div class="campo" style="margin-bottom: 1rem;">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" required>
                    </div>

                    <button type="submit" class="btn-salvar">Criar Administrador</button>
                </form>
            </div>
        </main>
    </body>
</html>