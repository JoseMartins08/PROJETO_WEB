<?php
    // Calcula a média aritmética das 3 notas
    function calcularMedia($nota1, $nota2, $trabalho) {
    return ($nota1 + $nota2 + $trabalho) / 3;
    }

    // Retorna a situação do aluno com base na média
    function classificarAluno($media) {
        if ($media >= 7.0) {
            return "Aprovado";
        } elseif ($media >= 5.0) {
            return "Recuperação";
        } else {
            return "Reprovado";
        }
    }

    // Retorna a classe CSS para colorir a situação na tabela
    function classeCSS($situacao) {
        if ($situacao === "Aprovado") {
            return "aprovado";
        } elseif ($situacao === "Recuperação") {
            return "recuperacao";
        } else {
            return "reprovado";
        }
    }

    // Gera a mensagem de desempenho geral da turma
    function mensagemDesempenho($percentual) {
        if ($percentual >= 70) {
            return [
                "texto" => "✅ Excelente desempenho! Mais de 70% da turma foi aprovada.",
                "classe" => "desempenho-otimo"
            ];
        } elseif ($percentual >= 50) {
            return [
                "texto" => "⚠️ Desempenho regular. Entre 50% e 69% da turma foi aprovada.",
                "classe" => "desempenho-bom"
            ];
        } else {
            return [
                "texto" => "❌ Desempenho preocupante. Menos de 50% da turma foi aprovada.",
                "classe" => "desempenho-ruim"
            ];
        }
    }

    // Verifica se os dados foram enviados corretamente
    if (!isset($_POST['nome_turma']) || !isset($_POST['quantidade'])) {
        // Se acessar direto sem vir do formulário, redireciona
        header("Location: index.php");
        exit;
    }

    $nomeTurma  = $_POST['nome_turma'];
    $quantidade = (int) $_POST['quantidade'];

    // Arrays com as notas enviadas pelo formulário
    $nomes     = $_POST['nome'];
    $notas1    = $_POST['nota1'];
    $notas2    = $_POST['nota2'];
    $trabalhos = $_POST['trabalho'];

    // Array que vai guardar os dados processados de cada aluno
    $alunos = [];

    // Variáveis para os cálculos da turma
    $somaMedias      = 0;
    $somaTodasNotas  = 0;
    $maiorMedia      = null;
    $menorMedia      = null;
    $qtdAprovados    = 0;
    $qtdRecuperacao  = 0;
    $qtdReprovados   = 0;

    // Loop que processa cada aluno
    for ($i = 0; $i < $quantidade; $i++) {

        // Converte as notas para número decimal
        $n1       = (float) $notas1[$i];
        $n2       = (float) $notas2[$i];
        $trabalho = (float) $trabalhos[$i];

        // Cálculos individuais usando as funções criadas e nativas do PHP
        $media       = calcularMedia($n1, $n2, $trabalho);
        $raizSoma    = sqrt($n1 + $n2 + $trabalho);          // função nativa: sqrt()
        $difAbsoluta = abs(max($n1, $n2, $trabalho) - min($n1, $n2, $trabalho)); // abs()
        $situacao    = classificarAluno($media);

        // Guarda os dados do aluno no array
        $alunos[] = [
            "nome"        => $nomes[$i],
            "nota1"       => $n1,
            "nota2"       => $n2,
            "trabalho"    => $trabalho,
            "media"       => $media,
            "raizSoma"    => $raizSoma,
            "difAbsoluta" => $difAbsoluta,
            "situacao"    => $situacao,
        ];

        // Acumula para os cálculos da turma
        $somaMedias     += $media;
        $somaTodasNotas += $n1 + $n2 + $trabalho;

        // Verifica maior e menor média
        if ($maiorMedia === null || $media > $maiorMedia) {
            $maiorMedia = $media;
        }
        if ($menorMedia === null || $media < $menorMedia) {
            $menorMedia = $media;
        }

        // Contagem por situação
        if ($situacao === "Aprovado") {
            $qtdAprovados++;
        } elseif ($situacao === "Recuperação") {
            $qtdRecuperacao++;
        } else {
            $qtdReprovados++;
        }
    }

    // Cálculos finais da turma
    $mediaGeral  = $somaMedias / $quantidade;
    $percentual  = ($qtdAprovados / $quantidade) * 100;
    $desempenho  = mensagemDesempenho($percentual);

?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Relatório da Turma – <?php echo htmlspecialchars($nomeTurma); ?></title>
        <link rel="stylesheet" href="CSS/style.css">
    </head>
    <body>

    <div class="container">

        <h1>Relatório Estatístico da Turma</h1>
        <p class="subtitulo">Turma: <strong><?php echo htmlspecialchars($nomeTurma); ?></strong> &nbsp;|&nbsp; Total de alunos: <strong><?php echo $quantidade; ?></strong></p>

        <h2>Resultados Individuais</h2>

        <table>
            <thead>
                <tr>
                    <th>Aluno</th>
                    <th>Prova 1</th>
                    <th>Prova 2</th>
                    <th>Trabalho</th>
                    <th>Média</th>
                    <th>√ Soma</th>
                    <th>Dif. Abs.</th>
                    <th>Situação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alunos as $aluno): ?>
                <tr>
                    <td><?php echo htmlspecialchars($aluno['nome']); ?></td>
                    <td><?php echo number_format($aluno['nota1'], 1); ?></td>
                    <td><?php echo number_format($aluno['nota2'], 1); ?></td>
                    <td><?php echo number_format($aluno['trabalho'], 1); ?></td>
                    <td><?php echo number_format($aluno['media'], 2); ?></td>
                    <td><?php echo number_format($aluno['raizSoma'], 2); ?></td>
                    <td><?php echo number_format($aluno['difAbsoluta'], 2); ?></td>
                    <td class="<?php echo classeCSS($aluno['situacao']); ?>">
                        <?php echo $aluno['situacao']; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <hr>

        <h2>Estatísticas da Turma</h2>

        <div class="estatisticas">
            <p>📊 Média geral da turma: <span><?php echo number_format($mediaGeral, 2); ?></span></p>
            <p>🏆 Maior média: <span><?php echo number_format($maiorMedia, 2); ?></span></p>
            <p>📉 Menor média: <span><?php echo number_format($menorMedia, 2); ?></span></p>
            <p>✅ Aprovados: <span><?php echo $qtdAprovados; ?></span></p>
            <p>⚠️ Em recuperação: <span><?php echo $qtdRecuperacao; ?></span></p>
            <p>❌ Reprovados: <span><?php echo $qtdReprovados; ?></span></p>
            <p>📈 Percentual de aprovação: <span><?php echo number_format($percentual, 1); ?>%</span></p>
            <p>➕ Soma total de todas as notas: <span><?php echo number_format($somaTodasNotas, 2); ?></span></p>
        </div>

        <div class="mensagem-desempenho <?php echo $desempenho['classe']; ?>">
            <?php echo $desempenho['texto']; ?>
        </div>

        <!-- Botão para voltar e lançar nova turma -->
        <a href="index.php" class="link-voltar">← Voltar e lançar nova turma</a>

    </div>

    </body>
</html>
