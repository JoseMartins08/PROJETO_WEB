<?php
require_once 'config/database.php';
require_once 'config/auth.php';
verificarLogin();

$base = '';
include 'includes/header.php';
?>

<h2>Bem-vindo, <?= htmlspecialchars($_SESSION['nome']) ?></h2>
<p>Use o menu acima para gerenciar as informações geográficas do sistema.</p>

<?php if ($_SESSION['tipo'] !== 'A'): ?>
    <p style="color: var(--texto-suave); margin-top: 0.5rem;">Seu acesso é de <strong>usuário comum</strong>: você pode apenas consultar os dados.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>