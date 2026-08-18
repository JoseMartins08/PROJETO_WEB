<?php
require_once '../config/database.php';

$mensagem = '';
$tipo_mensagem = '';
$edicao = null;

if (isset($_GET['excluir'])) {
    $id = intval($_GET['excluir']);

    $check = $conn->prepare("SELECT COUNT(*) as total FROM paises WHERE continente_id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $total = $check->get_result()->fetch_assoc()['total'];
    $check->close();

    if ($total > 0) {
        $mensagem = "Não é possível excluir: existem países associados a este continente.";
        $tipo_mensagem = "erro";
    } else {
        $stmt = $conn->prepare("DELETE FROM continentes WHERE id_continente = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        $mensagem = "Continente excluído com sucesso.";
        $tipo_mensagem = "sucesso";
    }
}

if (isset($_GET['editar'])) {
    $id = intval($_GET['editar']);
    $stmt = $conn->prepare("SELECT * FROM continentes WHERE id_continente = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $edicao = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $populacao = $_POST['populacao'] !== '' ? intval($_POST['populacao']) : null;
    $area = $_POST['area'] !== '' ? floatval($_POST['area']) : null;
    $total_paises = $_POST['total_paises'] !== '' ? intval($_POST['total_paises']) : 0;
    $id_continente = $_POST['id_continente'];

    if ($nome === '') {
        $mensagem = "O nome do continente é obrigatório.";
        $tipo_mensagem = "erro";
    } elseif ($id_continente) {
        $stmt = $conn->prepare("UPDATE continentes SET nome=?, populacao=?, area=?, total_paises=? WHERE id_continente=?");
        $stmt->bind_param("sddii", $nome, $populacao, $area, $total_paises, $id_continente);
        $stmt->execute();
        $stmt->close();
        $mensagem = "Continente atualizado com sucesso.";
        $tipo_mensagem = "sucesso";
    } else {
        $stmt = $conn->prepare("INSERT INTO continentes (nome, populacao, area, total_paises) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sddi", $nome, $populacao, $area, $total_paises);
        $stmt->execute();
        $stmt->close();
        $mensagem = "Continente cadastrado com sucesso.";
        $tipo_mensagem = "sucesso";
    }
}

$resultado = $conn->query("SELECT * FROM continentes ORDER BY nome");

$base = '../';
include '../includes/header.php';
?>

<h2>Continentes</h2>

<?php if ($mensagem): ?>
    <div class="mensagem <?= $tipo_mensagem ?>"><?= htmlspecialchars($mensagem) ?></div>
<?php endif; ?>

<div class="form-container">
    <form method="POST" action="continentes.php">
        <input type="hidden" name="id_continente" value="<?= $edicao['id_continente'] ?? '' ?>">
        <div class="form-grid">
            <div class="campo">
                <label for="nome">Nome *</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($edicao['nome'] ?? '') ?>" required>
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
                <label for="total_paises">Total de países</label>
                <input type="number" id="total_paises" name="total_paises" min="0" value="<?= $edicao['total_paises'] ?? '' ?>">
            </div>
        </div>
        <div class="acoes-form">
            <button type="submit" class="btn-salvar">Salvar</button>
            <a href="continentes.php" class="btn-link btn-cancelar">Cancelar</a>
        </div>
    </form>
</div>

<div class="busca">
    <input type="text" id="busca" placeholder="Buscar continente pelo nome...">
</div>

<table>
    <thead>
        <tr><th>Nome</th><th>População</th><th>Área (km²)</th><th>Total de países</th><th>Ações</th></tr>
    </thead>
    <tbody>
        <?php while ($row = $resultado->fetch_assoc()): ?>
        <tr>
            <td data-label="Nome"><?= htmlspecialchars($row['nome']) ?></td>
            <td data-label="População"><?= $row['populacao'] ?? '-' ?></td>
            <td data-label="Área (km²)"><?= $row['area'] ?? '-' ?></td>
            <td data-label="Total de países"><?= $row['total_paises'] ?></td>
            <td data-label="Ações">
                <a href="continentes.php?editar=<?= $row['id_continente'] ?>" class="btn-link btn-editar">Editar</a>
                <a href="continentes.php?excluir=<?= $row['id_continente'] ?>" class="btn-link btn-excluir">Excluir</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>