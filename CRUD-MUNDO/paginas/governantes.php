<?php
require_once '../config/database.php';

$mensagem = '';
$tipo_mensagem = '';
$edicao = null;

if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    $stmt = $conn->prepare("DELETE FROM governantes WHERE id_governante = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $mensagem = "Governante excluído com sucesso.";
    $tipo_mensagem = "sucesso";
}

if (isset($_GET['editar'])) {
    $id = intval($_GET['editar']);
    $stmt = $conn->prepare("SELECT * FROM governantes WHERE id_governante = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edicao = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $partido_politico = trim($_POST['partido_politico']);
    $data_nascimento = $_POST['data_nascimento'] !== '' ? $_POST['data_nascimento'] : null;
    $idade = $_POST['idade'] !== '' ? intval($_POST['idade']) : null;
    $data_inicio_mandato = $_POST['data_inicio_mandato'] !== '' ? $_POST['data_inicio_mandato'] : null;
    $data_fim_mandato = $_POST['data_fim_mandato'] !== '' ? $_POST['data_fim_mandato'] : null;
    $id_governante = $_POST['id_governante'];

    if ($nome === '') {
        $mensagem = "O nome do governante é obrigatório.";
        $tipo_mensagem = "erro";
    } elseif ($id_governante) {
        $stmt = $conn->prepare("UPDATE governantes SET nome=?, partido_politico=?, data_nascimento=?, idade=?, data_inicio_mandato=?, data_fim_mandato=? WHERE id_governante=?");
        $stmt->bind_param("sssissi", $nome, $partido_politico, $data_nascimento, $idade, $data_inicio_mandato, $data_fim_mandato, $id_governante);
        $stmt->execute();
        $stmt->close();
        $mensagem = "Governante atualizado com sucesso.";
        $tipo_mensagem = "sucesso";
    } else {
        $stmt = $conn->prepare("INSERT INTO governantes (nome, partido_politico, data_nascimento, idade, data_inicio_mandato, data_fim_mandato) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiss", $nome, $partido_politico, $data_nascimento, $idade, $data_inicio_mandato, $data_fim_mandato);
        $stmt->execute();
        $stmt->close();
        $mensagem = "Governante cadastrado com sucesso.";
        $tipo_mensagem = "sucesso";
    }
}

$resultado = $conn->query("SELECT * FROM governantes ORDER BY nome");

$base = '../';
include '../includes/header.php';
?>

<h2>Governantes</h2>

<?php if ($mensagem): ?>
    <div class="mensagem <?= $tipo_mensagem ?>"><?= htmlspecialchars($mensagem) ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="governantes.php">
        <input type="hidden" name="id_governante" value="<?= $edicao['id_governante'] ?? '' ?>">
        <div class="form-grid">
            <div class="campo">
                <label for="nome">Nome *</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($edicao['nome'] ?? '') ?>" required>
            </div>
            <div class="campo">
                <label for="partido_politico">Partido político</label>
                <input type="text" id="partido_politico" name="partido_politico" value="<?= htmlspecialchars($edicao['partido_politico'] ?? '') ?>">
            </div>
            <div class="campo">
                <label for="data_nascimento">Data de nascimento</label>
                <input type="date" id="data_nascimento" name="data_nascimento" value="<?= $edicao['data_nascimento'] ?? '' ?>">
            </div>
            <div class="campo">
                <label for="idade">Idade</label>
                <input type="number" id="idade" name="idade" min="0" value="<?= $edicao['idade'] ?? '' ?>">
            </div>
            <div class="campo">
                <label for="data_inicio_mandato">Início do mandato</label>
                <input type="date" id="data_inicio_mandato" name="data_inicio_mandato" value="<?= $edicao['data_inicio_mandato'] ?? '' ?>">
            </div>
            <div class="campo">
                <label for="data_fim_mandato">Fim do mandato</label>
                <input type="date" id="data_fim_mandato" name="data_fim_mandato" value="<?= $edicao['data_fim_mandato'] ?? '' ?>">
            </div>
        </div>
        <div class="acoes-form">
            <button type="submit" class="btn-salvar">Salvar</button>
            <a href="governantes.php" class="btn-link btn-cancelar">Cancelar</a>
        </div>
    </form>
</div>

<div class="busca">
    <input type="text" id="busca" placeholder="Buscar governante pelo nome...">
</div>

<table>
    <thead>
        <tr><th>Nome</th><th>Partido</th><th>Início mandato</th><th>Fim mandato</th><th>Ações</th></tr>
    </thead>
    <tbody>
        <?php while ($row = $resultado->fetch_assoc()): ?>
        <tr>
            <td data-label="Nome"><?= htmlspecialchars($row['nome']) ?></td>
            <td data-label="Partido"><?= htmlspecialchars($row['partido_politico'] ?? '-') ?></td>
            <td data-label="Início mandato"><?= $row['data_inicio_mandato'] ?? '-' ?></td>
            <td data-label="Fim mandato"><?= $row['data_fim_mandato'] ?? '-' ?></td>
            <td data-label="Ações">
                <a href="governantes.php?editar=<?= $row['id_governante'] ?>" class="btn-link btn-editar">Editar</a>
                <a href="governantes.php?excluir=<?= $row['id_governante'] ?>" class="btn-link btn-excluir">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>