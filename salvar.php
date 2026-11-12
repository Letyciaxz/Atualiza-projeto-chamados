<?php
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
    exit;
}

$solicitante = trim((string)($_POST['solicitante'] ?? ''));
$equipamento = trim((string)($_POST['equipamento'] ?? ''));
$prioridade = trim((string)($_POST['prioridade'] ?? ''));
$descricao = trim((string)($_POST['descricao'] ?? ''));

$equipamentosValidos = ['Computador', 'Projetor', 'Internet', 'Impressora'];
$prioridadesValidas = ['Baixa', 'Média', 'Alta'];
$erros = [];

if ($solicitante === '') {
    $erros[] = 'Informe seu nome.';
} elseif (mb_strlen($solicitante) < 2) {
    $erros[] = 'O nome deve ter pelo menos 2 caracteres.';
}
if (!in_array($equipamento, $equipamentosValidos, true)) {
    $erros[] = 'Escolha um equipamento válido.';
}
if (!in_array($prioridade, $prioridadesValidas, true)) {
    $erros[] = 'Escolha uma prioridade válida.';
}
if (mb_strlen($descricao) < 10) {
    $erros[] = 'Descreva o problema com pelo menos 10 caracteres.';
}

function e(string $texto): string {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Resultado do chamado</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<main>
<?php if ($erros): ?>
    <h1>Não foi possível enviar</h1>
    <ul class="erros">
        <?php foreach ($erros as $erro): ?>
            <li><?= e($erro) ?></li>
        <?php endforeach; ?>
    </ul>
    <a class="botao-secundario" href="index.html">Voltar ao formulário</a>
<?php else: ?>
    <?php
    $registro = [
        uniqid('CH-', true),
        date('d/m/Y H:i'),
        $solicitante,
        $equipamento,
        $prioridade,
        $descricao,
        'Aberto'
    ];
    $arquivo = fopen(__DIR__ . '/chamados.csv', 'a');
    if ($arquivo === false) {
        http_response_code(500);
        echo '<h1>Erro interno</h1><p>Não foi possível gravar o chamado.</p>';
        exit;
    }
    fputcsv($arquivo, $registro);
    fclose($arquivo);
    ?>
    <h1>Chamado registrado com sucesso!</h1>
    <p>Código: <strong><?= e($registro[0]) ?></strong></p>
    <p><a href="lista.php">Ver chamados</a> | <a href="index.html">Abrir outro chamado</a></p>
<?php endif; ?>
</main>
</body>
</html>
