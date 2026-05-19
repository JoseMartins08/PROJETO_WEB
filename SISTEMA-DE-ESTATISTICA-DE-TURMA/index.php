<?php
    $quantidade = 0;
    $nomeTurma = "";

    if (isset($_POST['quantidade']) && $_POST['quantidade'] > 0) {
        $quantidade = (int) $_POST['quantidade'];
        $nomeTurma = $_POST['nome_turma'];
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sistema de notas</title>
        <link rel="stylesheet" href="CSS/style.css">
    </head>

    <body>
        <div class="container">

            <h1>Sistema de Análise Estatística de Turma</h1>
            <p class="substituto">Preencha os dados abaixo para gerar o relatório.</p>

            <h2>Dados da turma</h2>
            <form method="POST" action="index.php">

                <label for="nome_turma">Turma:</label>
                <input type="text" id="nome_turma" name="nome_turma" value="<?php echo htmlspecialchars($nomeTurma);?>">

                <label for="quantidade">Quantidade de alunos:</label>
                <input type="number" id="quantidade" name="quantidade" value="<?php echo $quantidade > 0 ? $quantidade : '';?>" min="0">

                <button type="submit">Gerar Campos dos alunos</button>

            </form>

            <?php if ($quantidade > 0): ?>
                <hr>
                <h2>Notas dos Alunos (Turma: <?php echo htmlspecialchars($nomeTurma); ?>)</h2>

                <form method="POST" action="resultado.php">

                    <input type="hidden" name="nome_turma" value="<?php echo htmlspecialchars($nomeTurma); ?>">
                    <input type="hidden" name="quantidade" value="<?php echo $quantidade; ?>">

                    <?php for ($i = 1; $i <= $quantidade; $i++): ?>

                        <div class="bloco-aluno">
                            <h3>Aluno <?php echo $i; ?></h3>

                            <label for="nome_<?php echo $i; ?>">Nome do Aluno:</label>
                            <input type="text" name="nome[]" id="nome_<?php echo $i; ?>">

                            <div class="grupo-notas">      
                                <div>
                                    <label for="nota1_<?php echo $i; ?>">Nota - Prova 1:</label>
                                    <input type="number" name="nota1[]" id="nota1_<?php echo $i; ?>" min="0" max="10">
                                </div>

                                <div>
                                    <label for="nota2_<?php echo $i; ?>">Nota - Prova 2:</label>
                                    <input type="number" name="nota2[]" id="nota2_<?php echo $i; ?>" min="0" max="10">
                                </div>

                                <div>
                                    <label for="trabalho_<?php echo $i; ?>">Nota - Trabalho:</label>
                                    <input type="number" name="trabalho[]" id="trabalho_<?php echo $i; ?>" min="0" max="10">
                                </div>
                            </div>

                        </div>
                    
                    <?php endfor; ?>

                    <a href="index.php" class="btn-secundario">Recomeçar</a>

                    <input type="submit" value="Gerar Relatório">

                </form>
            <?php endif; ?>
            
        </div>
    </body>
</html>