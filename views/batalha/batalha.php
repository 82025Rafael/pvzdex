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
                <h3 class="nome-pokemon">
                    <span id="nome-inimigo">---</span>
                    <span id="status-inimigo" class="status-badge"></span>
                </h3>
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
                <h3 class="nome-pokemon">
                    <span id="nome-usuario">---</span>
                    <span id="status-usuario" class="status-badge"></span>
                </h3>
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
                <div id="indicador-clique" style="display:none; position:absolute; bottom:10px; right:15px; font-size:22px; color:#ffcb05; animation: piscar 1s infinite;">▼</div>
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
        import { BattleEngine } from '../../battle/engine.js';
        
        const DADOS_BATALHA = <?= json_encode($dados) ?>;
        
        let motor = null;
        let estado_interface = "INTRO";
        let callbackTrocaForcada = null;

        if (!DADOS_BATALHA || !DADOS_BATALHA.userTeam || DADOS_BATALHA.userTeam.length === 0) {
            alert("ERRO CRÍTICO: DADOS_BATALHA.userTeam está vazio ou inválido. Verifique o console (F12).");
            console.error("❌ DADOS_BATALHA inválido:", DADOS_BATALHA);
            // NÃO usar throw — apenas alerta e segue
        }
  
        
        const nomesTipos = { 
            1: 'Protetor', 2: 'Explosivo', 3: 'Crescida', 4: 'Esperto', 5: 'Solar', 
            6: 'Feroz', 7: 'Miolastico', 8: 'Doidao', 9: 'Vigoroso', 10: 'Furtivo' 
        };
        const classesStatus = {
            'Queimado': 'st-qmd', 'Congelado': 'st-con', 
            'Adormecido': 'st-sno', 'Paralisado': 'st-par', 'Envenenado': 'st-env'
        };
        const siglasStatus = {
            'Queimado': 'QMD', 'Congelado': 'CON', 
            'Adormecido': 'SNO', 'Paralisado': 'PAR', 'Envenenado': 'ENV'
        };

        // =========================================================
        // INICIALIZAÇÃO
        // =========================================================
        document.getElementById('tela-intro').addEventListener('click', () => {
            console.log("🎮 Clique na intro detectado");
            document.getElementById('tela-intro').style.display = 'none';
            document.getElementById('tela-batalha').style.display = 'flex'; 
            document.getElementById('musica-fundo').play().catch(e => console.log("Áudio bloqueado."));
            
            try {
                iniciar_combate();
            } catch (e) {
                console.error("❌ ERRO em iniciar_combate:", e);
                alert("Erro ao iniciar combate: " + e.message);
            }
        });

        document.getElementById('caixa-texto').addEventListener('click', () => {
            if (motor) motor.avancarClique();
        });

        function iniciar_combate() {
            console.log("⚙️ Criando BattleEngine...");
            
            motor = new BattleEngine(DADOS_BATALHA, {
                log: (msg, lado) => {
                    const cor = lado === 'usuario' 
                        ? (DADOS_BATALHA.userData?.cor || '#ffffff') 
                        : (DADOS_BATALHA.enemyData?.cor || '#ffcc00');
                    mudar_texto(`<span style="color: ${cor};">${msg}</span>`);
                },
                evento: (tipo, dados) => {
                    if (tipo === 'hpChange' || tipo === 'troca' || tipo === 'statusChange') {
                        try {
                            atualizar_interface(motor.usuarioAtivo, motor.inimigoAtivo);
                        } catch (e) {
                            console.error("Erro em evento->atualizar_interface:", e);
                        }
                    }
                },
                pedirAcao: () => {
                    estado_interface = "MENU";
                    alternar_menu_principal(true);
                    try {
                        atualizar_interface(motor.usuarioAtivo, motor.inimigoAtivo);
                    } catch (e) {
                        console.error("Erro em pedirAcao->atualizar_interface:", e);
                    }
                    mudar_texto(`<span style="color: ${DADOS_BATALHA.userData?.cor || '#ffffff'};">O que você vai fazer?</span>`);
                },
                pedirTroca: (time, callback) => {
                    callbackTrocaForcada = callback;
                    estado_interface = "TROCAR_FORCADO";
                    alternar_menu_principal(false);
                    mudar_texto(gerar_menu_pokemons('trocar_forcado'));
                },
                mostrarIndicadorClique: (mostrar) => {
                    const ind = document.getElementById('indicador-clique');
                    if (ind) ind.style.display = mostrar ? 'block' : 'none';
                },
                batalhaAcabou: (resultado) => {
                    alternar_menu_principal(false);
                    
                    let msgFinal = resultado.winner === 'user' 
                        ? DADOS_BATALHA.enemyData.txt_morre 
                        : DADOS_BATALHA.enemyData.txt_mata;
                    let corFinal = resultado.winner === 'user' 
                        ? (DADOS_BATALHA.userData?.cor || '#ffffff')
                        : (DADOS_BATALHA.enemyData?.cor || '#ffcc00');
                    
                    mudar_texto(`<span style="color: ${corFinal};">${msgFinal}</span>`);
                    document.getElementById('indicador-clique').style.display = 'block';
                    
                    const handlerClique = () => {
                        document.getElementById('caixa-texto').removeEventListener('click', handlerClique);
                        finalizar_batalha(resultado);
                    };
                    document.getElementById('caixa-texto').addEventListener('click', handlerClique);
                }
            });
            
            console.log("✅ BattleEngine criado:", motor);
            console.log("   - usuarioTime:", motor.usuarioTime);
            console.log("   - inimigoTime:", motor.inimigoTime);
            console.log("   - usuarioAtivo:", motor.usuarioAtivo);
            console.log("   - inimigoAtivo:", motor.inimigoAtivo);
            
            // Atualiza IMEDIATAMENTE (não espera setTimeout)
            try {
                atualizar_interface(motor.usuarioAtivo, motor.inimigoAtivo);
                console.log("✅ atualizar_interface inicial executou");
            } catch (e) {
                console.error("❌ ERRO em atualizar_interface inicial:", e);
            }
            
            motor.iniciar();
        }

        // =========================================================
        // ATUALIZAÇÃO DA INTERFACE (BLINDADA)
        // =========================================================
        function atualizar_interface(pkm_usr, pkm_ini) {
            if (!pkm_usr || !pkm_ini) {
                console.error("❌ atualizar_interface: pkm_usr ou pkm_ini undefined", pkm_usr, pkm_ini);
                return;
            }
            
            // Helper para construir caminho de imagem com segurança
            const construirCaminho = (foto) => {
                if (!foto || typeof foto !== 'string') {
                    console.warn("⚠️ foto inválida:", foto);
                    return '';
                }
                return '../../' + foto.replace('../', '');
            };
            
            // Inimigo
            document.getElementById('nome-inimigo').innerText = pkm_ini.nome || '???';
            document.getElementById('vida-inimigo').innerText = (pkm_ini.hp ?? 0) + "/" + (pkm_ini.hpMax ?? 0);
            document.getElementById('forca-inimigo').innerText = pkm_ini.forcaEfetiva ?? 0;
            document.getElementById('veloc-inimigo').innerText = pkm_ini.velocEfetiva ?? 0;
            document.getElementById('img-inimigo').src = construirCaminho(pkm_ini.foto);
            
            // Usuário
            document.getElementById('nome-usuario').innerText = pkm_usr.nome || '???';
            document.getElementById('vida-usuario').innerText = (pkm_usr.hp ?? 0) + "/" + (pkm_usr.hpMax ?? 0);
            document.getElementById('forca-usuario').innerText = pkm_usr.forcaEfetiva ?? 0;
            document.getElementById('veloc-usuario').innerText = pkm_usr.velocEfetiva ?? 0;
            document.getElementById('img-usuario').src = construirCaminho(pkm_usr.foto);

            // Badges de status
            const badgeIni = document.getElementById('status-inimigo');
            const badgeUsr = document.getElementById('status-usuario');
            
            badgeIni.className = 'status-badge ' + (pkm_ini.status ? classesStatus[pkm_ini.status] : '');
            badgeIni.innerText = pkm_ini.status ? siglasStatus[pkm_ini.status] : '';

            badgeUsr.className = 'status-badge ' + (pkm_usr.status ? classesStatus[pkm_usr.status] : '');
            badgeUsr.innerText = pkm_usr.status ? siglasStatus[pkm_usr.status] : '';
        }

        function mudar_texto(html) { 
            const el = document.getElementById('caixa-texto');
            // Preserva o indicador de clique
            const ind = document.getElementById('indicador-clique');
            el.innerHTML = html;
            if (ind) el.appendChild(ind);
        }
        
        function alternar_menu_principal(mostrar) { 
            document.getElementById('menu-principal').style.display = mostrar ? 'grid' : 'none'; 
        }

        // =========================================================
        // GERADOR DE MENU DE POKÉMONS (BLINDADO)
        // =========================================================
        function gerar_menu_pokemons(acao_destino, id_item = null) {
            let html = `<div class="menu-pokemons">`;
            
            if (!motor || !motor.usuarioTime) {
                console.error("❌ motor ou motor.usuarioTime inválido em gerar_menu_pokemons");
                return `<div class="menu-pokemons"><p style="color:white;">Erro ao gerar menu</p></div>`;
            }
            
            motor.usuarioTime.forEach((pkmReal, index) => {
                const itensRevive = [11, 12]; // 11 = Frangão, 12 = Cenoura do Poder
                const ehRevive = acao_destino === 'item' && itensRevive.includes(parseInt(id_item));

                let disabled = "";

                if (acao_destino === 'trocar_forcado') {
                    // Troca forçada: só pokémons vivos
                    if (!pkmReal.vivo) disabled = "disabled";
                } else if (acao_destino === 'trocar') {
                    // Troca normal: vivos, exceto o que já está em campo
                    if (!pkmReal.vivo || index === motor.usuarioAtivoIndex) disabled = "disabled";
                } else if (acao_destino === 'item') {
                    if (ehRevive) {
                        // Item revive: só pokémons desmaiados
                        if (pkmReal.vivo) disabled = "disabled";
                    } else {
                        // Item comum (cura/status): só pokémons vivos
                        if (!pkmReal.vivo) disabled = "disabled";
                    }
                }
                
                let classe = (disabled && acao_destino === 'trocar') ? "botao-pokemon desativado" : "botao-pokemon";
                let status_vida = (!pkmReal.vivo) ? " (Desmaiado)" : (index === motor.usuarioAtivoIndex ? " (Em campo)" : "");
                let caminhoImg = pkmReal.foto ? ('../../' + pkmReal.foto.replace('../', '')) : '';
                let tipoPkm = nomesTipos[pkmReal.id_tipo] || 'Furtivo';

                let atk1 = motor.movesCatalog[pkmReal.atk1];
                let atk2 = pkmReal.atk2 ? motor.movesCatalog[pkmReal.atk2] : null;
                
                let txtAtk1 = atk1 ? `${atk1.nome_atk} (${atk1.mult_dano}x)` : '';
                let txtAtk2 = atk2 ? `; ${atk2.nome_atk} (${atk2.mult_dano}x)` : '';
                let linha_detalhes = `HP: ${pkmReal.hp}/${pkmReal.hpMax} | F: ${pkmReal.forcaEfetiva} | V: ${pkmReal.velocEfetiva} | A1: ${txtAtk1}${txtAtk2}`;

                let clickAction;
                if (acao_destino === 'trocar_forcado') {
                    clickAction = `executar_troca_forcada(${index}, event)`;
                } else if (acao_destino === 'trocar') {
                    clickAction = `executar_troca(${index}, event)`;
                } else {
                    clickAction = `executar_item(${id_item}, ${index}, event)`;
                }

                html += `<button type="button" tipo="${tipoPkm}" class="${classe}" ${disabled} onclick="${clickAction}">
                            <img src="${caminhoImg}" class="btn-img-pokemon">
                            <div class="info-pokemon-btn">
                                <span class="nome-btn">${pkmReal.nome}${status_vida}</span>
                                <span class="detalhes-btn">${linha_detalhes}</span>
                            </div>
                         </button>`;
            });
            
            if (acao_destino !== 'trocar_forcado') {
                let acao_voltar = (acao_destino === 'trocar') ? `voltar_menu(event)` : `acao_item(event)`;
                html += `<button type="button" class="btn-voltar" onclick="${acao_voltar}">Voltar</button>`;
            }
            
            html += `</div>`;
            return html;
        }

        // =========================================================
        // AÇÕES PRINCIPAIS
        // =========================================================
        function acao_lutar(event) {
            if(estado_interface !== "MENU") return;
            if (event) event.stopPropagation();
            estado_interface = "LUTAR";
            alternar_menu_principal(false); 
            
            let pkmAtivo = motor.usuarioAtivo;
            let atk1 = motor.movesCatalog[pkmAtivo.atk1];
            let atk2 = pkmAtivo.atk2 ? motor.movesCatalog[pkmAtivo.atk2] : null;

            let html = `<div class="menu-dinamico">`;
            let tipo1 = nomesTipos[atk1.id_tipo];
            html += `<button type="button" tipo="${tipo1}" onclick="executar_ataque(1, event)" title="${atk1.desc_atk}">
                        ${atk1.nome_atk} <br><small>(Dano: ${atk1.mult_dano}x)</small>
                     </button>`;
            
            if (atk2) {
                let tipo2 = nomesTipos[atk2.id_tipo];
                html += `<button type="button" tipo="${tipo2}" onclick="executar_ataque(2, event)" title="${atk2.desc_atk}">
                            ${atk2.nome_atk} <br><small>(Dano: ${atk2.mult_dano}x)</small>
                         </button>`;
            } else {
                html += `<button type="button" disabled style="color: gray;">---</button>`;
            }
            
            html += `<button type="button" class="btn-voltar" onclick="voltar_menu(event)">Voltar</button>`;
            html += `</div>`;
            mudar_texto(html);
        }

        function acao_trocar(event) {
            if(estado_interface !== "MENU") return;
            if (event) event.stopPropagation();
            estado_interface = "TROCAR";
            alternar_menu_principal(false); 
            mudar_texto(gerar_menu_pokemons('trocar'));
        }

        function acao_item(event) {
            if (event) event.stopPropagation();
            estado_interface = "ITEM";
            alternar_menu_principal(false); 
            let html = `<div class="menu-itens">`;
            let tem_item = false;

            for (const [id_item, quantidade] of Object.entries(motor.inventario)) {
                if (quantidade > 0 && motor.itemsCatalog[id_item]) {
                    tem_item = true;
                    let item_info = motor.itemsCatalog[id_item];
                    let caminhoImg = item_info.foto ? '../../' + item_info.foto.replace('../', '') : '';
                    html += `<button type="button" class="botao-item" onclick="selecionar_alvo_item(${id_item}, event)" title="${item_info.desc_item}">
                                ${caminhoImg ? `<img src="${caminhoImg}" class="btn-img-item">` : ''}
                                <span class="qtd-item">x${quantidade}</span>
                             </button>`;
                }
            }
            if(!tem_item) html += `<p style="color: #ffcb05; padding: 10px; width: 100%; text-align:center;">Nenhum item disponível.</p>`;
            html += `<button type="button" class="btn-voltar" onclick="voltar_menu(event)">Voltar</button>`;
            html += `</div>`;
            mudar_texto(html);
        }

        function acao_fugir(event) {
            if(estado_interface !== "MENU") return;
            if (event) event.stopPropagation();
            alternar_menu_principal(false); 
            motor.submitAcao({ tipo: 'fugir' });
        }

        function voltar_menu(event) {
            if (event) event.stopPropagation();
            estado_interface = "MENU";
            alternar_menu_principal(true);
            mudar_texto(`<span style="color: ${DADOS_BATALHA.userData?.cor || '#ffffff'};">O que você vai fazer?</span>`);
        }

        function selecionar_alvo_item(id_item, event) {
            if (event) event.stopPropagation();
            estado_interface = "ITEM_ALVO";
            mudar_texto(gerar_menu_pokemons('item', id_item));
        }

        // =========================================================
        // EXECUÇÃO DE AÇÕES
        // =========================================================
        function executar_ataque(slot, event) {
            if(estado_interface !== "LUTAR") return;
            if (event) event.stopPropagation();
            estado_interface = "ANIMANDO";
            alternar_menu_principal(false);
            motor.submitAcao({ tipo: 'atacar', slot: slot });
        }

        function executar_troca(index, event) {
            if(estado_interface !== "TROCAR") return;
            if (event) event.stopPropagation();
            estado_interface = "ANIMANDO";
            alternar_menu_principal(false);
            motor.submitAcao({ tipo: 'trocar', index: index });
        }

        function executar_item(id_item, index, event) {
            if(estado_interface !== "ITEM_ALVO") return;
            if (event) event.stopPropagation();
            estado_interface = "ANIMANDO";
            alternar_menu_principal(false);
            motor.submitAcao({ tipo: 'item', id_item: id_item, index: index });
        }

        function executar_troca_forcada(index, event) {
            if (!callbackTrocaForcada) return;
            if (event) event.stopPropagation();
            const cb = callbackTrocaForcada;
            callbackTrocaForcada = null;
            estado_interface = "ANIMANDO";
            alternar_menu_principal(false);
            cb(index);
        }

        // =========================================================
        // FINALIZAÇÃO
        // =========================================================
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

        // =========================================================
        // EXPOSIÇÃO PARA O ESCOPO GLOBAL
        // =========================================================
        Object.assign(window, {
            acao_lutar, acao_trocar, acao_item, acao_fugir,
            executar_ataque, executar_troca, executar_item,
            executar_troca_forcada, selecionar_alvo_item, voltar_menu
        });
    </script>
    </body>
    </html>
     