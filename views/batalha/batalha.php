<?php
session_start();
require_once "../../config.php";

if (empty($_SESSION['logado']) || empty($_SESSION['battle_data'])) {
    header("Location: ../selecao/selecao.php");
    exit;
}

$dados = $_SESSION['battle_data'];

function formatar_caminho_imagem($caminho) {
    return '../../' . ltrim($caminho, './'); 
}

$foto_usr = formatar_caminho_imagem($dados['userData']['foto']);
$foto_ini = formatar_caminho_imagem($dados['enemyData']['foto']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Batalha!</title>
    <link rel="stylesheet" href="batalha.css">
</head>
<body style="background-image: url('<?= $dados['cenario'] ?>');">

    <audio id="musica-fundo" src="<?= $dados['musica'] ?>" loop></audio>

    <div id="tela-intro" class="tela-intro">
        <div class="intro-fotos">
            <img src="<?= $foto_usr ?>" alt="Você">
            <h2>VS</h2>
            <img src="<?= $foto_ini ?>" alt="Inimigo">
        </div>
        <div class="caixa-dialogo-container">
            <div id="caixa-texto-intro" class="caixa-texto">
                <?= htmlspecialchars($dados['enemyData']['txt_entra']) ?>
            </div>
        </div>
    </div>

    <div id="tela-batalha" style="display: none;">
        <div class="area-combate">
            <div class="caixa-info caixa-inimigo">
                <h3 class="nome-pokemon" id="nome-inimigo">---</h3>
                <div class="stats-container">
                    <div class="stat vida"><span>Vida</span> <span id="vida-inimigo">---</span></div>
                    <div class="stat forca"><span>Força</span> <span id="forca-inimigo">---</span></div>
                    <div class="stat veloc"><span>Vel</span> <span id="veloc-inimigo">---</span></div>
                </div>
            </div>

            <div class="caixa-foto">
                <img id="img-inimigo" src="" alt="Pokemon Inimigo" class="sprite-pokemon">
            </div>

            <div class="caixa-foto">
                <img id="img-usuario" src="" alt="Seu Pokemon" class="sprite-pokemon">
            </div>

            <div class="caixa-info caixa-usuario">
                <h3 class="nome-pokemon" id="nome-usuario">---</h3>
                <div class="stats-container">
                    <div class="stat vida"><span>Vida</span> <span id="vida-usuario">---</span></div>
                    <div class="stat forca"><span>Força</span> <span id="forca-usuario">---</span></div>
                    <div class="stat veloc"><span>Vel</span> <span id="veloc-usuario">---</span></div>
                </div>
            </div>
        </div>

        <div class="caixa-dialogo-container">
            <div id="caixa-texto" class="caixa-texto">
                O que você vai fazer?
            </div>
            
            <div id="menu-principal" class="menu-acoes">
                <button type="button" onclick="acao_lutar()">LUTAR</button>
                <button type="button" onclick="acao_trocar()">TROCAR</button>
                <button type="button" onclick="acao_item()">ITEM</button>
                <button type="button" onclick="acao_fugir()">FUGIR</button>
            </div>
        </div>
    </div>

          <script type="module">
        import { BattleEngine } from './engine.js';

        const DADOS_BATALHA = <?= json_encode($dados) ?>;
        let motor = null;
        let estado_interface = "INTRO";

        
        const nomesTipos = { 
            1: 'Protetor', 2: 'Explosivo', 3: 'Crescida', 4: 'Esperto', 5: 'Solar', 
            6: 'Feroz', 7: 'Miolastico', 8: 'Doidao', 9: 'Vigoroso', 10: 'Furtivo' 
        };

        document.getElementById('tela-intro').addEventListener('click', () => {
            document.getElementById('tela-intro').style.display = 'none';
            document.getElementById('tela-batalha').style.display = 'flex'; 
            document.getElementById('musica-fundo').play().catch(e => console.log("Áudio aguardando interação."));
            iniciar_combate();
        });

         function iniciar_combate() {
            motor = new BattleEngine(DADOS_BATALHA, {
                log: (msg) => mudar_texto(msg),
                evento: (tipo) => {
                    if (tipo === 'hpChange' || tipo === 'troca') {
                        atualizar_interface(motor.usuarioAtivo, motor.inimigoAtivo);
                    }
                },
                pedirAcao: () => {
                    voltar_menu();
                },
                pedirTroca: () => {
                    estado_interface = "TROCA_FORCADA";
                    alternar_menu_principal(false);
                    mudar_texto(gerar_menu_pokemons('trocar', null, true));
                },
                batalhaAcabou: (resultado) => {
                    estado_interface = "FIM";
                    alternar_menu_principal(false);
                    finalizar_batalha(resultado);
                }
            });

            atualizar_interface(motor.usuarioAtivo, motor.inimigoAtivo);
            motor.iniciar();
        }

        function atualizar_interface(pkm_usr, pkm_ini) {
            document.getElementById('nome-inimigo').innerText = pkm_ini.nome;
            document.getElementById('vida-inimigo').innerText = pkm_ini.vida_atual + "/" + pkm_ini.vida_max;
            document.getElementById('forca-inimigo').innerText = pkm_ini.forca;
            document.getElementById('veloc-inimigo').innerText = pkm_ini.veloc;
            document.getElementById('img-inimigo').src = '../../' + pkm_ini.foto.replace('../', '');

            document.getElementById('nome-usuario').innerText = pkm_usr.nome;
            document.getElementById('vida-usuario').innerText = pkm_usr.vida_atual + "/" + pkm_usr.vida_max;
            document.getElementById('forca-usuario').innerText = pkm_usr.forca;
            document.getElementById('veloc-usuario').innerText = pkm_usr.veloc;
            document.getElementById('img-usuario').src = '../../' + pkm_usr.foto.replace('../', '');
        }

        function mudar_texto(html) {
            document.getElementById('caixa-texto').innerHTML = html;
        }

        function alternar_menu_principal(mostrar) {
            document.getElementById('menu-principal').style.display = mostrar ? 'grid' : 'none';
        }

        function voltar_menu() {
            estado_interface = "MENU";
            alternar_menu_principal(true);
            mudar_texto("O que você vai fazer?");
        }

        // =================================================================
        // GERADOR DE MENU DE POKEMONS
        // =================================================================
       function gerar_menu_pokemons(acao_destino, id_item = null, obrigatorio = false) {
    // Usa o time em tempo real do motor (com HP e status atualizados)
    const timeAtual = motor ? motor.usuarioTime : DADOS_BATALHA.userTeam;
    const indexAtivo = motor ? motor.usuarioAtivoIndex : 0;

    let html = `<div class="menu-pokemons">`;

    timeAtual.forEach((pkm, index) => {
        // Compatibilidade caso seja chamado antes do motor iniciar
        const hpAtual = pkm.hp ?? pkm.vida_atual;
        const hpMax = pkm.hpMax ?? pkm.vida_max;
        const estaVivo = hpAtual > 0;
        const estaEmCampo = index === indexAtivo;

        // Bloqueia se estiver morto (em qualquer ação) ou se já estiver em campo (na troca)
        const bloqueado = !estaVivo || (acao_destino === 'trocar' && estaEmCampo);
        const disabledAttr = bloqueado ? "disabled" : "";
        const classe = bloqueado ? "botao-pokemon desativado" : "botao-pokemon";

        let status_vida = "";
        if (!estaVivo) status_vida = " (Desmaiado)";
        else if (estaEmCampo && acao_destino === 'trocar') status_vida = " (Em campo)";

            const caminhoImg = '../../' + pkm.foto.replace('../', '');
            const tipoPkm = nomesTipos[pkm.id_tipo] || 'Furtivo';

            const atk1 = DADOS_BATALHA.movesCatalog[pkm.atk1];
            const atk2 = pkm.atk2 ? DADOS_BATALHA.movesCatalog[pkm.atk2] : null;

            const txtAtk1 = atk1 ? `${atk1.nome_atk} (${atk1.mult_dano}x)` : '';
            const txtAtk2 = atk2 ? `; ${atk2.nome_atk} (${atk2.mult_dano}x)` : '';

            const linha_detalhes = `HP: ${hpAtual}/${hpMax} | F: ${pkm.forca} | V: ${pkm.veloc} | A1: ${txtAtk1}${txtAtk2}`;

            // Ação limpa: sempre chama as funções globais da interface
            const clickAction = (acao_destino === 'trocar')
                ? `executar_troca(${index})`
                : `executar_item(${id_item}, ${index})`;

            html += `
                <button type="button" tipo="${tipoPkm}" class="${classe}" ${disabledAttr} onclick="${clickAction}">
                    <img src="${caminhoImg}" class="btn-img-pokemon" alt="${pkm.nome}">
                    <div class="info-pokemon-btn">
                        <span class="nome-btn">${pkm.nome}${status_vida}</span>
                        <span class="detalhes-btn">${linha_detalhes}</span>
                    </div>
                </button>`;
        });

        // Só exibe o botão "Voltar" se a troca não for forçada por desmaio
        if (!obrigatorio) {
            const acao_voltar = (acao_destino === 'trocar') ? `voltar_menu()` : `acao_item()`;
            html += `<button type="button" class="btn-voltar" onclick="${acao_voltar}">Voltar</button>`;
        }

        html += `</div>`;
        return html;
    }

            // =================================================================
            // MENUS PRINCIPAIS
            // =================================================================
            function acao_lutar() {
                if(estado_interface !== "MENU") return;
                estado_interface = "LUTAR";
                alternar_menu_principal(false); 
                
                let pkmAtivo = DADOS_BATALHA.userTeam[0];
                let atk1 = DADOS_BATALHA.movesCatalog[pkmAtivo.atk1];
                let atk2 = pkmAtivo.atk2 ? DADOS_BATALHA.movesCatalog[pkmAtivo.atk2] : null;

                let html = `<div class="menu-dinamico">`;
                let tipo1 = nomesTipos[atk1.id_tipo];
                
                html += `<button type="button" tipo="${tipo1}" onclick="executar_ataque(1)" title="${atk1.desc_atk}">
                            ${atk1.nome_atk} <br><small>(Dano: ${atk1.mult_dano}x)</small>
                        </button>`;
                
                if (atk2) {
                    let tipo2 = nomesTipos[atk2.id_tipo];
                    html += `<button type="button" tipo="${tipo2}" onclick="executar_ataque(2)" title="${atk2.desc_atk}">
                                ${atk2.nome_atk} <br><small>(Dano: ${atk2.mult_dano}x)</small>
                            </button>`;
                } else {
                    html += `<button type="button" disabled style="color: gray;">---</button>`;
                }
                
                html += `<button type="button" class="btn-voltar" onclick="voltar_menu()">Voltar</button>`;
                html += `</div>`;
                
                mudar_texto(html);
            }

            function acao_trocar() {
                if(estado_interface !== "MENU") return;
                estado_interface = "TROCAR";
                alternar_menu_principal(false); 
                mudar_texto(gerar_menu_pokemons('trocar'));
            }

            function acao_item() {
                estado_interface = "ITEM";
                alternar_menu_principal(false); 
                
                let html = `<div class="menu-itens">`;
                let tem_item = false;

                for (const [id_item, quantidade] of Object.entries(DADOS_BATALHA.userInventory)) {
                    if (quantidade > 0 && DADOS_BATALHA.itemsCatalog[id_item]) {
                        tem_item = true;
                        let item_info = DADOS_BATALHA.itemsCatalog[id_item];
                        let caminhoImg = item_info.foto ? '../../' + item_info.foto.replace('../', '') : '';
                        
                        // Botão do item apenas com imagem e badge de quantidade
                        html += `<button type="button" class="botao-item" onclick="selecionar_alvo_item(${id_item})" title="${item_info.desc_item}">
                                    ${caminhoImg ? `<img src="${caminhoImg}" class="btn-img-item">` : ''}
                                    <span class="qtd-item">x${quantidade}</span>
                                </button>`;
                    }
                }

                if(!tem_item) {
                    html += `<p style="color: #ffcb05; padding: 10px; width: 100%; font-weight:bold; font-size: 20px; text-align:center;">Nenhum item disponível.</p>`;
                }
                
                html += `<button type="button" class="btn-voltar" onclick="voltar_menu()">Voltar</button>`;
                html += `</div>`;
                
                mudar_texto(html);
            }

            function selecionar_alvo_item(id_item) {
                estado_interface = "ITEM_ALVO";
                mudar_texto(gerar_menu_pokemons('item', id_item));
            }

            function acao_fugir() {
                if(estado_interface !== "MENU") return;
                estado_interface = "ANIMANDO";
                alternar_menu_principal(false); 
                mudar_texto(DADOS_BATALHA.enemyData.txt_mata);
                setTimeout(() => finalizar_batalha("enemy"), 3000);
            }

                    // =================================================================
        // AÇÕES QUE ENVIAM COMANDOS PARA A ENGINE
        // =================================================================
        function executar_ataque(slot) {
            if (estado_interface !== "LUTAR") return;
            estado_interface = "ANIMANDO";
            alternar_menu_principal(false);
            motor.submitAcao({ tipo: 'atacar', slot: slot });
        }

        function executar_troca(index) {
            if (estado_interface !== "TROCAR" && estado_interface !== "TROCA_FORCADA") return;
            estado_interface = "ANIMANDO";
            alternar_menu_principal(false);
            motor.submitAcao({ tipo: 'trocar', index: index });
        }

        function executar_item(id_item, index) {
            if (estado_interface !== "ITEM_ALVO") return;
            estado_interface = "ANIMANDO";
            alternar_menu_principal(false);
            motor.submitAcao({ tipo: 'item', id_item: id_item, index: index });
        }

        function finalizar_batalha(resultado) {
            resultado.action = 'finalizar';
            fetch('../../controllers/batalha_controller.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(resultado)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) window.location.href = data.redirect;
                else alert("Erro ao salvar: " + data.error);
            });
        }

                    // =================================================================
        // EXPOSIÇÃO GLOBAL PARA OS BOTÕES ONCLICK
        // =================================================================
        window.acao_lutar = acao_lutar;
        window.acao_trocar = acao_trocar;
        window.acao_item = acao_item;
        window.acao_fugir = acao_fugir;
        window.selecionar_alvo_item = selecionar_alvo_item;
        window.voltar_menu = voltar_menu;
        window.executar_ataque = executar_ataque;
        window.executar_troca = executar_troca;
        window.executar_item = executar_item;
    </script>
</body>
</html>

        </script>

    </body>
    </html>