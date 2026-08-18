<?php
require_once '../config/database.php';

$mensagem = '';
$tipo_mensagem = '';
$edicao = null;

if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);

    $check = $conn->prepare("SELECT COUNT(*) as total FROM cidades WHERE pais_id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $total = $check->get_result()->fetch_assoc()['total'];
    $check->close();

    if ($total > 0) {
        $mensagem = "Não é possível excluir: existem cidades associadas a este país.";
        $tipo_mensagem = "erro";
    } else {
        $stmt = $conn->prepare("DELETE FROM paises WHERE id_pais = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $mensagem = "País excluído com sucesso.";
        $tipo_mensagem = "sucesso";
    }
}

if (isset($_GET['editar'])) {
    $id = intval($_GET['editar']);
    $stmt = $conn->prepare("SELECT * FROM paises WHERE id_pais = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edicao = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $continente_id = $_POST['continente_id'] !== '' ? intval($_POST['continente_id']) : null;
    $populacao = $_POST['populacao'] !== '' ? intval($_POST['populacao']) : null;
    $area = $_POST['area'] !== '' ? floatval($_POST['area']) : null;
    $idioma = trim($_POST['idioma']);
    $clima = trim($_POST['clima']);
    $regime_politico = trim($_POST['regime_politico']);
    $moeda = trim($_POST['moeda']);
    $governante_id = $_POST['governante_id'] !== '' ? intval($_POST['governante_id']) : null;
    $id_pais = $_POST['id_pais'];

    if ($nome === '' || !$continente_id) {
        $mensagem = "Nome e continente são obrigatórios.";
        $tipo_mensagem = "erro";
    } elseif ($id_pais) {
        $stmt = $conn->prepare("UPDATE paises SET nome=?, continente_id=?, populacao=?, area=?, idioma=?, clima=?, regime_politico=?, moeda=?, governante_id=? WHERE id_pais=?");
        $stmt->bind_param("siddssssii", $nome, $continente_id, $populacao, $area, $idioma, $clima, $regime_politico, $moeda, $governante_id, $id_pais);
        $stmt->execute();
        $stmt->close();
        $mensagem = "País atualizado com sucesso.";
        $tipo_mensagem = "sucesso";
    } else {
        $stmt = $conn->prepare("INSERT INTO paises (nome, continente_id, populacao, area, idioma, clima, regime_politico, moeda, governante_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siddssssi", $nome, $continente_id, $populacao, $area, $idioma, $clima, $regime_politico, $moeda, $governante_id);
        $stmt->execute();
        $stmt->close();
        $mensagem = "País cadastrado com sucesso.";
        $tipo_mensagem = "sucesso";
    }
}

$continentes = $conn->query("SELECT * FROM continentes ORDER BY nome");
$governantes = $conn->query("SELECT * FROM governantes ORDER BY nome");
$resultado = $conn->query("SELECT p.*, c.nome AS continente_nome, g.nome AS governante_nome
                            FROM paises p
                            LEFT JOIN continentes c ON p.continente_id = c.id_continente
                            LEFT JOIN governantes g ON p.governante_id = g.id_governante
                            ORDER BY p.nome");

$base = '../';
include '../includes/header.php';
?>

<h2>Países</h2>

<?php if ($mensagem): ?>
    <div class="mensagem <?= $tipo_mensagem ?>"><?= htmlspecialchars($mensagem) ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="paises.php">
        <input type="hidden" name="id_pais" value="<?= $edicao['id_pais'] ?? '' ?>">
        <div class="form-grid">
            <div class="campo">
                <label for="nome">Nome *</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($edicao['nome'] ?? '') ?>" required>
            </div>
            <div class="campo">
                <label for="continente_id">Continente *</label>
                <select id="continente_id" name="continente_id" required>
                    <option value="">Selecione...</option>
                    <?php $continentes->data_seek(0); while ($c = $continentes->fetch_assoc()): ?>
                        <option value="<?= $c['id_continente'] ?>" <?= (isset($edicao['continente_id']) && $edicao['continente_id'] == $c['id_continente']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['nome']) ?>
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
                <label for="idioma">Idioma</label>
                <input type="text" id="idioma" name="idioma" value="<?= htmlspecialchars($edicao['idioma'] ?? '') ?>">
            </div>
            <div class="campo">
                <label for="clima">Clima</label>
                <input type="text" id="clima" name="clima" value="<?= htmlspecialchars($edicao['clima'] ?? '') ?>">
            </div>
            <div class="campo">
                <label for="regime_politico">Regime político</label>
                <input type="text" id="regime_politico" name="regime_politico" value="<?= htmlspecialchars($edicao['regime_politico'] ?? '') ?>">
            </div>
            <div class="campo">
                <label for="moeda">Moeda</label>
                <input type="text" id="moeda" name="moeda" value="<?= htmlspecialchars($edicao['moeda'] ?? '') ?>">
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
            <a href="paises.php" class="btn-link btn-cancelar">Cancelar</a>
        </div>
    </form>
</div>

<div class="busca">
    <input type="text" id="busca" placeholder="Buscar país pelo nome...">
</div>

<table>
    <thead>
        <tr><th>Nome</th><th>Continente</th><th>População</th><th>Idioma</th><th>Governante</th><th>Ações</th></tr>
    </thead>
    <tbody>
        <?php while ($row = $resultado->fetch_assoc()): ?>
        <tr>
            <td data-label="Nome"><?= htmlspecialchars($row['nome']) ?></td>
            <td data-label="Continente"><?= htmlspecialchars($row['continente_nome'] ?? '-') ?></td>
            <td data-label="População"><?= $row['populacao'] ?? '-' ?></td>
            <td data-label="Idioma"><?= htmlspecialchars($row['idioma'] ?? '-') ?></td>
            <td data-label="Governante"><?= htmlspecialchars($row['governante_nome'] ?? '-') ?></td>
            <td data-label="Ações">
                <a href="paises.php?editar=<?= $row['id_pais'] ?>" class="btn-link btn-editar">Editar</a>
                <a href="paises.php?excluir=<?= $row['id_pais'] ?>" class="btn-link btn-excluir">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>