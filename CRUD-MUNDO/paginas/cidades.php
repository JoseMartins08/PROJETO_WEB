<?php
require_once '../config/database.php';

$mensagem = '';
$tipo_mensagem = '';
$edicao = null;

if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);
    $stmt = $conn->prepare("DELETE FROM cidades WHERE id_cidade = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $mensagem = "Cidade excluída com sucesso.";
    $tipo_mensagem = "sucesso";
}

if (isset($_GET['editar'])) {
    $id = intval($_GET['editar']);
    $stmt = $conn->prepare("SELECT * FROM cidades WHERE id_cidade = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edicao = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $pais_id = $_POST['pais_id'] !== '' ? intval($_POST['pais_id']) : null;
    $populacao = $_POST['populacao'] !== '' ? intval($_POST['populacao']) : null;
    $area = $_POST['area'] !== '' ? floatval($_POST['area']) : null;
    $clima = trim($_POST['clima']);
    $data_fundacao = $_POST['data_fundacao'] !== '' ? $_POST['data_fundacao'] : null;
    $governante_id = $_POST['governante_id'] !== '' ? intval($_POST['governante_id']) : null;
    $id_cidade = $_POST['id_cidade'];

    if ($nome === '' || !$pais_id) {
        $mensagem = "Nome e país são obrigatórios.";
        $tipo_mensagem = "erro";
    } elseif ($id_cidade) {
        $stmt = $conn->prepare("UPDATE cidades SET nome=?, pais_id=?, populacao=?, area=?, clima=?, data_fundacao=?, governante_id=? WHERE id_cidade=?");
        $stmt->bind_param("siddssii", $nome, $pais_id, $populacao, $area, $clima, $data_fundacao, $governante_id, $id_cidade);
        $stmt->execute();
        $stmt->close();
        $mensagem = "Cidade atualizada com sucesso.";
        $tipo_mensagem = "sucesso";
    } else {
        $stmt = $conn->prepare("INSERT INTO cidades (nome, pais_id, populacao, area, clima, data_fundacao, governante_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siddssi", $nome, $pais_id, $populacao, $area, $clima, $data_fundacao, $governante_id);
        $stmt->execute();
        $stmt->close();
        $mensagem = "Cidade cadastrada com sucesso.";
        $tipo_mensagem = "sucesso";
    }
}

$paises = $conn->query("SELECT * FROM paises ORDER BY nome");
$governantes = $conn->query("SELECT * FROM governantes ORDER BY nome");
$resultado = $conn->query("SELECT c.*, p.nome AS pais_nome, g.nome AS governante_nome
                            FROM cidades c
                            LEFT JOIN paises p ON c.pais_id = p.id_pais
                            LEFT JOIN governantes g ON c.governante_id = g.id_governante
                            ORDER BY c.nome");

$base = '../';
include '../includes/header.php';
?>

<h2>Cidades</h2>

<?php if ($mensagem): ?>
    <div class="mensagem <?= $tipo_mensagem ?>"><?= htmlspecialchars($mensagem) ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="cidades.php">
        <input type="hidden" name="id_cidade" value="<?= $edicao['id_cidade'] ?? '' ?>">
        <div class="form-grid">
            <div class="campo">
                <label for="nome">Nome *</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($edicao['nome'] ?? '') ?>" required>
            </div>
            <div class="campo">
                <label for="pais_id">País *</label>
                <select id="pais_id" name="pais_id" required>
                    <option value="">Selecione...</option>
                    <?php $paises->data_seek(0); while ($p = $paises->fetch_assoc()): ?>
                        <option value="<?= $p['id_pais'] ?>" <?= (isset($edicao['pais_id']) && $edicao['pais_id'] == $p['id_pais']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['nome']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="campo">
                <label for="populacao">População</label>
                <input type="number" id="populacao" name="populacao" min="0" value="<?= $edicao['populacao'] ?? '' ?>">
            </div>
            <div class="campo">
                <label for="area">Área (km²)</label>
                <input type="number" id="area" name="area" min="0" step="0.01" value="<?= $edicao['area'] ?? '' ?>">
            </div>
            <div class="campo">
                <label for="clima">Clima</label>
                <input type="text" id="clima" name="clima" value="<?= htmlspecialchars($edicao['clima'] ?? '') ?>">
            </div>
            <div class="campo">
                <label for="data_fundacao">Data de fundação</label>
                <input type="date" id="data_fundacao" name="data_fundacao" value="<?= $edicao['data_fundacao'] ?? '' ?>">
            </div>
            <div class="campo">
                <label for="governante_id">Governante</label>
                <select id="governante_id" name="governante_id">
                    <option value="">Selecione...</option>
                    <?php $governantes->data_seek(0); while ($g = $governantes->fetch_assoc()): ?>
                        <option value="<?= $g['id_governante'] ?>" <?= (isset($edicao['governante_id']) && $edicao['governante_id'] == $g['id_governante']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($g['nome']) ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="acoes-form">
            <button type="submit" class="btn-salvar">Salvar</button>
            <a href="cidades.php" class="btn-link btn-cancelar">Cancelar</a>
        </div>
    </form>
</div>

<div class="busca">
    <input type="text" id="busca" placeholder="Buscar cidade pelo nome...">
</div>

<table>
    <thead>
        <tr><th>Nome</th><th>País</th><th>População</th><th>Clima</th><th>Fundação</th><th>Governante</th><th>Ações</th></tr>
    </thead>
    <tbody>
        <?php while ($row = $resultado->fetch_assoc()): ?>
        <tr>
            <td data-label="Nome"><?= htmlspecialchars($row['nome']) ?></td>
            <td data-label="País"><?= htmlspecialchars($row['pais_nome'] ?? '-') ?></td>
            <td data-label="População"><?= $row['populacao'] ?? '-' ?></td>
            <td data-label="Clima"><?= htmlspecialchars($row['clima'] ?? '-') ?></td>
            <td data-label="Fundação"><?= $row['data_fundacao'] ?? '-' ?></td>
            <td data-label="Governante"><?= htmlspecialchars($row['governante_nome'] ?? '-') ?></td>
            <td data-label="Ações">
                <a href="cidades.php?editar=<?= $row['id_cidade'] ?>" class="btn-link btn-editar">Editar</a>
                <a href="cidades.php?excluir=<?= $row['id_cidade'] ?>" class="btn-link btn-excluir">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>