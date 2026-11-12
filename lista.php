<?php
declare(strict_types=1);

$chamados = [];
$arquivoCsv = __DIR__ . '/chamados.csv';

if (file_exists($arquivoCsv)) {
    $arquivo = fopen($arquivoCsv, 'r');
    if ($arquivo !== false) {
        while (($linha = fgetcsv($arquivo)) !== false) {
            if (count($linha) >= 7) {
                $chamados[] = $linha;
            }
        }
        fclose($arquivo);
    }
}

$filtro = trim((string)($_GET['prioridade'] ?? ''));
if (in_array($filtro, ['Baixa', 'Média', 'Alta'], true)) {
    $chamados = array_values(array_filter($chamados, fn(array $c): bool => $c[4] === $filtro));
}

function e(string $texto): string {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}
function classePrioridade(string $prioridade): string {
    return match ($prioridade) {
        'Alta' => 'prioridade-alta',
        'Média' => 'prioridade-media',
        default => 'prioridade-baixa'
    };
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chamados registrados</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
<main>
    <h1>Chamados Registrados (<?= count($chamados) ?>)</h1>
    <nav class="filtros">
        <a href="lista.php">Todos</a>
        <a href="lista.php?prioridade=Alta">Alta</a>
        <a href="lista.php?prioridade=Média">Média</a>
        <a href="lista.php?prioridade=Baixa">Baixa</a>
    </nav>

    <?php if (!$chamados): ?>
        <p>Nenhum chamado registrado<?= $filtro ? ' com essa prioridade' : '' ?>.</p>
    <?php else: ?>
        <div class="tabela-container">
        <table>
            <thead><tr>
                <th>Código</th><th>Data</th><th>Solicitante</th>
                <th>Equipamento</th><th>Prioridade</th><th>Descrição</th><th>Status</th>
            </tr></thead>
            <tbody>
            <?php foreach ($chamados as $c): ?>
                <tr class="<?= e(classePrioridade($c[4])) ?>">
                    <?php foreach ($c as $campo): ?><td><?= e($campo) ?></td><?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    <?php endif; ?>
    <p><a href="index.html">Abrir novo chamado</a></p>
</main>
</body>
</html>
