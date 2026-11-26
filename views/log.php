<?php
require_once __DIR__ . '/../config.php';
session_start();

if (empty($_SESSION['logado']) || empty($_SESSION['id_usr'])) {
    header('Location: login.php');
    exit;
}

$pdo = connect();

$usuario = new Usuario();
$usuario->setId((int)$_SESSION['id_usr']);
$usuario->load();

/*
|------------------------------------------------------------------
| Busca os logs do usuário logado.
| A tabela 'log' tem (pelo que vi no selecao_controller.php):
|   id_log, nome_usr, nome_inim, id_inim, ganhou_perdeu
| Se existir 'data_log', mostra data. Se não, cai no catch.
|------------------------------------------------------------------
*/
$temData = false;
try {
    $sql = "SELECT id_log, nome_usr, nome_inim, ganhou_perdeu, data_log
            FROM log
            WHERE nome_usr = :nome
            ORDER BY id_log DESC
            LIMIT 300";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nome' => $usuario->getNome()]);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $temData = true;
} catch (PDOException $e) {
    $sql = "SELECT id_log, nome_usr, nome_inim, ganhou_perdeu
            FROM log
            WHERE nome_usr = :nome
            ORDER BY id_log DESC
            LIMIT 300";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':nome' => $usuario->getNome()]);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ---------- Estatísticas ----------
$totalVitorias = 0;
$totalDerrotas = 0;
foreach ($logs as $l) {
    if ((int)($l['ganhou_perdeu'] ?? 0) === 1) {
        $totalVitorias++;
    } else {
        $totalDerrotas++;
    }
}
$total        = count($logs);
$taxaVitoria  = $total > 0 ? (int) round(($totalVitorias / $total) * 100) : 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log de Ações</title>
    <link rel="stylesheet" href="log.css">
</head>
<body>
    <audio src="../Música/Laura Shigihara - Zen Garden.mp3" autoplay loop></audio>

    <div class="log-page">
        <header class="log-header">
            <h1>📜 Histórico de Ações</h1>
            <p>Tudo que você já fez nas batalhas</p>
        </header>

        <!-- Cartões de estatística -->
        <section class="stats-row">
            <div class="stat-card stat-vitoria">
                <span class="stat-num"><?= $totalVitorias ?></span>
                <span class="stat-label">Vitórias</span>
            </div>
            <div class="stat-card stat-derrota">
                <span class="stat-num"><?= $totalDerrotas ?></span>
                <span class="stat-label">Derrotas</span>
            </div>
            <div class="stat-card stat-taxa">
                <span class="stat-num"><?= $taxaVitoria ?>%</span>
                <span class="stat-label">Taxa de vitória</span>
            </div>
            <div class="stat-card stat-total">
                <span class="stat-num"><?= $total ?></span>
                <span class="stat-label">Total de batalhas</span>
            </div>
        </section>

        <!-- Lista de logs -->
        <section class="log-list">
            <?php if (empty($logs)): ?>
                <p class="log-vazio">Nenhuma batalha registrada ainda. Vá batalhar! ⚔️</p>
            <?php else: ?>
                <?php foreach ($logs as $log):
                    $venceu = (int)($log['ganhou_perdeu'] ?? 0) === 1;
                    $data   = $temData && !empty($log['data_log'])
                            ? date('d/m/Y H:i', strtotime($log['data_log']))
                            : null;
                ?>
                    <div class="log-entry <?= $venceu ? 'log-win' : 'log-loss' ?>">
                        <div class="log-icon"><?= $venceu ? '🏆' : '💀' ?></div>
                        <div class="log-body">
                            <div class="log-title">
                                <?= $venceu ? 'Vitória contra' : 'Derrota para' ?>
                                <strong><?= htmlspecialchars($log['nome_inim'] ?? 'Inimigo', ENT_QUOTES, 'UTF-8') ?></strong>
                            </div>
                            <div class="log-meta">
                                <span>👤 <?= htmlspecialchars($log['nome_usr'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                                <?php if ($data): ?>
                                    <span>🕒 <?= htmlspecialchars($data, ENT_QUOTES, 'UTF-8') ?></span>
                                <?php endif; ?>
                                <span>#<?= (int)$log['id_log'] ?></span>
                            </div>
                        </div>
                        <div class="log-result"><?= $venceu ? 'VENCEU' : 'PERDEU' ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <div class="log-actions">
            <a href="louja/selecao.php"><button type="button">⬅ Voltar à seleção</button></a>
            <a href="index.php"><button type="button">🏠 Início</button></a>
        </div>
    </div>
</body>
</html>