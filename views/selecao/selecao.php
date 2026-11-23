<?php require __DIR__ . '/../../controllers/selecao_controller.php'; ?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleção de Pokemons</title>
    <link rel="stylesheet" href="../views/selecao/selecao.css">
    
</head>
<body>
    <div class="selection-page">
        <header class="page-header">
            <h1 id="titulo">Selecione seu time!</h1>
            <p id="instrucoes">Escolha 6 Monstrinhos para sua equipe:</p>
        </header>

        <!-- Grade principal: equipe à esquerda, inventário e monstrinhos no centro e navegação à direita. -->
        <main class="game-layout">
            <!-- Os cards selecionados são movidos para esta coluna pelo JavaScript. -->
            <section class="panel team-panel">
                <h2>Equipe Selecionada</h2>
                <div id="equipe-selecionada" class="card-list team-list"></div>
            </section>

            <div class="center-column">
                <!-- Mostra os itens e quantidades carregados do inventário do usuário. -->
                <section class="panel inventory-panel">
                    <h2>Inventário</h2>
                    <div id="inventario" class="inventory-list">
                        <?php if (!empty($inventario)): ?>
                            <?php foreach ($inventario as $item): ?>
                                <div class="item-card">
                                    <h3><?php echo htmlspecialchars($item['nome_item'] ?? 'Item', ENT_QUOTES, 'UTF-8'); ?></h3>
                                    <p>Quantidade: <?php echo htmlspecialchars((string) ($item['quant_item'] ?? 0), ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Nenhum item no inventário.</p>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Cada card mantém o checkbox para seleção e revela os dados ao passar o mouse. -->
                <section class="panel pokemon-panel">
                    <h2>Monstrinhos disponíveis</h2>
                    <div id="pokemons" class="card-list">
                        <?php if (!empty($pokemons)): ?>
                            <?php foreach ($pokemons as $pokemon): ?>
                                <label class="pokemon-card" tipo="<?php echo htmlspecialchars($pokemon['nome_tipo'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="checkbox" class="pokemon-checkbox" aria-label="Selecionar Pokémon">
                                    <img class="pokemon-image" src="<?php echo '../../foto/' . htmlspecialchars($pokemon['foto'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($pokemon['nome_pkm'] ?? 'Pokémon', ENT_QUOTES, 'UTF-8'); ?>">
                                    <span class="pokemon-info">
                                        <strong><?php echo htmlspecialchars($pokemon['nome_pkm'] ?? '', ENT_QUOTES, 'UTF-8'); ?></strong>
                                        <span>Espécie: <?php echo htmlspecialchars($pokemon['nome_esp'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span>Vida: <?php echo htmlspecialchars((string) ($pokemon['vida_restante_pkm'] ?? 0), ENT_QUOTES, 'UTF-8'); ?>/<?php echo htmlspecialchars((string) ($pokemon['vida_total_pkm'] ?? 0), ENT_QUOTES, 'UTF-8'); ?></span>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Nenhum monstrinho encontrado na sessão.</p>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

            <!-- Atalhos de navegação ficam agrupados na coluna direita. -->
            <nav id="menu" class="panel menu-panel" aria-label="Menu principal">
                <a href="../index.php"><button type="button">Sair</button></a>
                <a href="../log.php"><button type="button">Log</button></a>
                <a href="../centro.php"><button type="button">Centro</button></a>
                <a href="../louja/lojainicial.php"><button type="button">Loja</button></a>
                <a href="../../controllers/batalha_controller.php"><button type="button">Batalhar</button></a>
            </nav>
        </main>
    </div>

    <script>
        const equipeSelecionada = document.getElementById('equipe-selecionada');
        const pokemonsDisponiveis = document.getElementById('pokemons');

        // Ao marcar ou desmarcar, move o card entre a lista disponível e a equipe.
        document.addEventListener('change', (event) => {
            if (!event.target.matches('.pokemon-checkbox')) return;

            const card = event.target.closest('.pokemon-card');
            card.classList.toggle('selected', event.target.checked);
            const destino = event.target.checked ? equipeSelecionada : pokemonsDisponiveis;
            destino.appendChild(card);
        });
    </script>
</body>
</html>
