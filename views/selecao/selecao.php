<?php require __DIR__ . '/../../controllers/selecao_controller.php';  
 if(!isset($_SESSION['logado']) || $_SESSION['logado'] == false)
  { header("location:login.php"); exit; }  
        

  unset($_SESSION['pkm']);
        
        $usuario = new usuario();
        $usuario->setId((int) $_SESSION['id_usr']);
        $usuario->load();
        
        
        
        
        ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleção de Monstrinhos</title>
    <link rel="stylesheet" href="selecao.css">
    
</head>
<body>
 <audio src="../../Música/Laura Shigihara - Zen Garden.mp3" autoplay loop></audio>

    <div class="selection-page">
        <header class="page-header">
            <h1 id="titulo">Selecione seu time!</h1>
            <p id="instrucoes">Escolha 6 Monstrinhos para sua equipe:</p>

            <?php if (!empty($_SESSION['msg_item'])): ?>
                <p class="msg-item"><?= htmlspecialchars($_SESSION['msg_item'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php unset($_SESSION['msg_item']); ?>
            <?php endif; ?>
        </header>

        <!-- Grade principal: equipe à esquerda, inventário e monstrinhos no centro e navegação à direita. -->
        <main class="game-layout">
            <!-- Os cards selecionados são movidos para esta coluna pelo JavaScript. -->
            <section class="panel team-panel">
                <h2 style = "color:oklab(0.85 0.01 0.13);">Equipe Selecionada</h2>
                <div id="equipe-selecionada" class="card-list team-list"></div>
            </section>

            <div class="center-column">
                <!-- Mostra os itens e quantidades carregados do inventário do usuário. -->
                <section class="panel inventory-panel">
                    <h2 style = "color:oklab(0.85 0.01 0.13);" >Inventário</h2>
                    <div id="inventario" class="inventory-list">
                        <?php if (!empty($inventario)): ?>
                            <?php foreach ($inventario as $item): ?>
                                <div class="item-card"
                                     data-item-id="<?= (int)($item['id_item'] ?? 0) ?>"
                                     title="Clique para usar em um monstrinho">
                                    <h3><?php echo htmlspecialchars($item['nome_item'] ?? 'Item', ENT_QUOTES, 'UTF-8'); ?></h3>
                                    <p>Quantidade: <?php echo htmlspecialchars((string) ($item['quant_item'] ?? 0), ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style = "color:white;">Nenhum item no inventário.</p>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Cada card mantém o checkbox para seleção e revela os dados ao passar o mouse. -->
                <section class="panel pokemon-panel">
                    <h2 style = "color:oklab(0.85 0.01 0.13);" >Monstrinhos disponíveis</h2>
                    <div id="pokemons" class="card-list">
                        <?php if (!empty($pokemons)): ?>
                            <?php foreach ($pokemons as $pokemon): ?>
                                <label class="pokemon-card" data-pokemon-id="<?php echo (int) ($pokemon['id_pkm'] ?? 0); ?>" tipo="<?php echo htmlspecialchars($pokemon['nome_tipo'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                                    <input type="checkbox" class="pokemon-checkbox" aria-label="Selecionar Pokémon">
                                    <img class="pokemon-image" src="<?php echo '../../foto/' . htmlspecialchars($pokemon['foto'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($pokemon['nome_pkm'] ?? 'Pokémon', ENT_QUOTES, 'UTF-8'); ?>">
                                    <span class="pokemon-info">
                                        <strong><?php echo htmlspecialchars($pokemon['nome_pkm'] ?? '', ENT_QUOTES, 'UTF-8'); ?></strong>
                                        <span>Espécie: <?php echo htmlspecialchars($pokemon['nome_esp'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                                        <span>Vida: <?php echo htmlspecialchars((string) ($pokemon['vida_restante_pkm'] ?? 0), ENT_QUOTES, 'UTF-8'); ?>/<?php echo htmlspecialchars((string) ($pokemon['vida_total_pkm'] ?? 0), ENT_QUOTES, 'UTF-8'); ?></span>
                                        <strong>Ataques:</strong> 
                                        <span class="ataques" > <?php echo htmlspecialchars($pokemon['atk1'] ?? '', ENT_QUOTES, 'UTF-8'); ?> </span>
                                        <span class="ataques"> <?php echo htmlspecialchars($pokemon['atk2'] ?? '', ENT_QUOTES, 'UTF-8'); ?> </span>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Nenhum monstrinho encontrado na sessão.</p>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

            <!-- Menu de navegação ficam agrupados na coluna direita. -->
            <nav style="display: flex; justify-content:center; align-items:center; "id="menu" class="panel menu-panel" aria-label="Menu principal">

                <img class="fotoPerfil" src="../<?= htmlspecialchars($usuario->getFoto(), ENT_QUOTES, 'UTF-8'); ?>">
                <div class="banco">Dinheiro: $<?= $usuario->getDinheiro() ?></div>           

                <a style = "width:100%;" href="../index.php"><button type="button">Sair</button></a>
                                   

                <?php  if ($venceuJogo): // SE O DESGRAÇADO VENCEU ?>
                     <a style="width:100%;" href="../creditos.php"> <button type="button" style="width:100%;">Créditos</button> </a>
                <?php else: // CASO CONTRARIO ELE SÓ FICA CINZA ?>
                    
                    <button type="button" style="width:100%;" disabled title="Vença o jogo para desbloquear!">Créditos</button>
                <?php endif; ?>
                <a  style = "width:100%;" href="../log.php"><button type="button">Log</button></a>
                <a style = "width:100%;" href="../louja/lojainicial.php"><button type="button">Loja</button></a>
                <form class = "batalha" id="batalha-form" action="../../controllers/batalha_controller.php" method="post">
                    <div id="pokemon-ids"></div>
                    <button style = "width:100%;"  type="submit">Batalhar</button>
                </form>
            </nav>
        </main>
    </div>

    <script>
        const equipeSelecionada = document.getElementById('equipe-selecionada');
        const pokemonsDisponiveis = document.getElementById('pokemons');
        const limiteEquipe = 6;

        function atualizarDisponibilidade() {
            const equipeCheia = equipeSelecionada.querySelectorAll('.pokemon-checkbox:checked').length >= limiteEquipe;
            document.querySelectorAll('.pokemon-checkbox').forEach((checkbox) => {
                checkbox.disabled = equipeCheia && !checkbox.checked;
            });
        }

        // Ao marcar ou desmarcar, move o card entre a lista disponível e a equipe.
        document.addEventListener('change', (event) => {
            if (!(event.target instanceof HTMLInputElement) || !event.target.matches('.pokemon-checkbox')) return;

            const checkbox = event.target;
            const card = checkbox.closest('.pokemon-card');
            if (!card) return;

            if (checkbox.checked && equipeSelecionada.querySelectorAll('.pokemon-checkbox:checked').length > limiteEquipe) {
                checkbox.checked = false;
                atualizarDisponibilidade();
                return;
            }

            card.classList.toggle('selected', checkbox.checked);
            const destino = checkbox.checked ? equipeSelecionada : pokemonsDisponiveis;
            destino.appendChild(card);
            atualizarDisponibilidade();
        });

        document.getElementById('batalha-form').addEventListener('submit', (event) => {
            const idsContainer = document.getElementById('pokemon-ids');
            idsContainer.replaceChildren();

            const idsSelecionados = Array.from(
                equipeSelecionada.querySelectorAll('.pokemon-checkbox:checked')
            ).map((checkbox) => checkbox.closest('.pokemon-card').dataset.pokemonId);

            if (idsSelecionados.length === 0) {
                event.preventDefault();
                alert('Selecione pelo menos um monstrinho.');
                return;
            }

            idsSelecionados.forEach((id) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'pokemon_ids[]';
                input.value = id;
                idsContainer.appendChild(input);
            });
        });

        // ---------- USO DE ITENS ----------
        let itemSelecionado = null;

        document.querySelectorAll('.item-card[data-item-id]').forEach(card => {
            card.addEventListener('click', () => {
                const id = card.dataset.itemId;
                if (!id || id === '0') return;

                if (itemSelecionado === id) {
                    itemSelecionado = null;
                    card.classList.remove('item-selected');
                } else {
                    document.querySelectorAll('.item-card').forEach(c => c.classList.remove('item-selected'));
                    itemSelecionado = id;
                    card.classList.add('item-selected');
                }
                document.body.classList.toggle('item-mode', itemSelecionado !== null);
            });
        });

        // Captura o clique no monstrinho em modo "item selecionado"
        document.addEventListener('click', (event) => {
            if (!itemSelecionado) return;

            const pokemonCard = event.target.closest('.pokemon-card');
            if (!pokemonCard) return;

            // Impede que o checkbox do card seja marcado
            event.preventDefault();
            event.stopPropagation();

            const pkmId = pokemonCard.dataset.pokemonId;
            if (!pkmId) return;

            const nomePkm = pokemonCard.querySelector('.pokemon-info strong')?.textContent?.trim() || 'este monstrinho';
            if (!confirm(`Usar o item selecionado em ${nomePkm}?`)) return;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../../controllers/item_controller.php';

            const i1 = document.createElement('input');
            i1.type = 'hidden'; i1.name = 'id_item'; i1.value = itemSelecionado;
            const i2 = document.createElement('input');
            i2.type = 'hidden'; i2.name = 'id_pkm';  i2.value = pkmId;

            form.appendChild(i1);
            form.appendChild(i2);
            document.body.appendChild(form);
            form.submit();
        }, true); // capture=true, pega antes do <label>
    </script>
</body>
</html>