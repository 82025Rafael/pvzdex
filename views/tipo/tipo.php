<?php
require_once __DIR__ . '/../../config.php';
session_start();

if (empty($_SESSION['logado']) || empty($_SESSION['id_usr'])) {
    header('Location: ../login.php');
    exit;
}

$pdo = connect();
$stmt = $pdo->query('SELECT id_tipo, nome_tipo, f1, f2, w1, w2 FROM tipo ORDER BY id_tipo');
$tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query(
    'SELECT a.id_atk, a.nome_atk, a.desc_atk, t.id_tipo, t.nome_tipo
     FROM ataque a
     INNER JOIN tipo t ON a.id_tipo = t.id_tipo
     ORDER BY t.id_tipo, a.id_atk'
);
$ataques = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forças e Fraquezas dos Tipos</title>
    <link rel="stylesheet" href="tipo.css">
</head>
<body>
    <main class="type-page">
        <header class="page-header">
            <div>
                <h1>Forças e Fraquezas</h1>
                <p>Consulte como cada tipo se sai contra os outros.</p>
            </div>
            <a class="back-link" href="../selecao/selecao.php">Voltar à seleção</a>
        </header>

        <section class="chart-panel" aria-label="Tabela de forças e fraquezas dos tipos">
            <div class="legend" aria-label="Legenda">
                <span><span class="legend-mark strong" aria-hidden="true">2x</span> Forte</span>
                <span><span class="legend-mark weak" aria-hidden="true">½x</span> Fraco</span>
                <span><span class="legend-mark neutral" aria-hidden="true">1x</span> Neutro</span>
            </div>

            <div class="table-scroll">
                <table class="type-chart">
                    <caption>O tipo na linha ataca o tipo na coluna.</caption>
                    <thead>
                        <tr>
                            <th class="axis-label" scope="col">Ataque ↓<br>Defesa →</th>
                            <?php foreach ($tipos as $tipo): ?>
                                <?php $nomeTipo = htmlspecialchars($tipo['nome_tipo'], ENT_QUOTES, 'UTF-8'); ?>
                                <th scope="col" data-tipo="<?= $nomeTipo ?>"><?= $nomeTipo ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tipos as $tipoAtaque): ?>
                            <?php
                            $nomeAtaqueOriginal = (string) $tipoAtaque['nome_tipo'];
                            $nomeAtaque = htmlspecialchars($nomeAtaqueOriginal, ENT_QUOTES, 'UTF-8');
                            ?>
                            <tr>
                                <th scope="row" data-tipo="<?= $nomeAtaque ?>"><?= $nomeAtaque ?></th>
                                <?php foreach ($tipos as $tipoDefesa): ?>
                                    <?php
                                    $idDefesa = (int) $tipoDefesa['id_tipo'];
                                    $tiposFortes = [(int) $tipoAtaque['f1'], (int) $tipoAtaque['f2']];
                                    $tiposFracos = [(int) $tipoAtaque['w1'], (int) $tipoAtaque['w2']];
                                    $forte = in_array($idDefesa, $tiposFortes, true);
                                    $fraco = !$forte && in_array($idDefesa, $tiposFracos, true);
                                    $classe = $forte ? 'strong' : ($fraco ? 'weak' : 'neutral');
                                    $multiplicador = $forte ? '2x' : ($fraco ? '½x' : '1x');
                                    $descricao = htmlspecialchars(
                                        $nomeAtaqueOriginal . ' contra ' . $tipoDefesa['nome_tipo'] . ': ' . $multiplicador,
                                        ENT_QUOTES,
                                        'UTF-8'
                                    );
                                    ?>
                                    <td class="matchup <?= $classe ?>" aria-label="<?= $descricao ?>">
                                        <?= $multiplicador ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($tipos)): ?>
                            <tr>
                                <td class="empty-state" colspan="1">Nenhum tipo encontrado no banco de dados.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="attack-panel" aria-labelledby="attack-heading">
            <h2 id="attack-heading">Ataques por tipo</h2>
            <div class="attack-filter">
                <label for="attack-type-filter">Filtrar por tipo:</label>
                <select id="attack-type-filter">
                    <option value="all">Todos os tipos</option>
                    <?php foreach ($tipos as $tipo): ?>
                        <option value="<?= (int) $tipo['id_tipo'] ?>">
                            <?= htmlspecialchars($tipo['nome_tipo'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="attack-list" id="attack-list">
                <?php foreach ($ataques as $ataque): ?>
                    <?php
                    $nomeAtaque = htmlspecialchars($ataque['nome_atk'], ENT_QUOTES, 'UTF-8');
                    $descricaoAtaque = htmlspecialchars($ataque['desc_atk'], ENT_QUOTES, 'UTF-8');
                    $nomeTipoAtaque = htmlspecialchars($ataque['nome_tipo'], ENT_QUOTES, 'UTF-8');
                    ?>
                    <article
                        class="attack-card"
                        data-tipo-id="<?= (int) $ataque['id_tipo'] ?>"
                        data-tipo="<?= $nomeTipoAtaque ?>"
                    >
                        <div class="attack-card-heading">
                            <h3><?= $nomeAtaque ?></h3>
                            <span class="type-chip" data-tipo="<?= $nomeTipoAtaque ?>"><?= $nomeTipoAtaque ?></span>
                        </div>
                        <p><?= $descricaoAtaque ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
            <p class="empty-state" id="empty-attacks" hidden>Nenhum ataque encontrado para este tipo.</p>
        </section>
    </main>
    <script>
        const typeFilter = document.getElementById('attack-type-filter');
        const attackCards = Array.from(document.querySelectorAll('.attack-card'));
        const emptyAttacks = document.getElementById('empty-attacks');

        function filterAttacks() {
            let visibleCount = 0;

            attackCards.forEach((card) => {
                const visible = typeFilter.value === 'all' || card.dataset.tipoId === typeFilter.value;
                card.hidden = !visible;
                if (visible) visibleCount++;
            });

            emptyAttacks.hidden = visibleCount > 0;
        }

        typeFilter.addEventListener('change', filterAttacks);
        filterAttacks();
    </script>
</body>
</html>
