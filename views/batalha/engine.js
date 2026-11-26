// ============================================================
// BATTLE/ENGINE.JS
// Motor Principal de Combate
// ============================================================
import { SPECIAL_MOVES, SPECIAL_SPECIES } from './specials.js';
import { BatalhaIA } from './ai.js';

export class Lutador {
    constructor(pkmData, moveCatalog) {
        Object.assign(this, pkmData);
        this.hpMax = pkmData.vida_max;
        this.hp = pkmData.vida_atual;
        this.forcaBase = pkmData.forca;
        this.velocBase = pkmData.veloc;
        
        // Corrigido 'Paralisado' com S para bater com o restante da engine
        this.status = pkmData.id_cond 
            ? ['Queimado', 'Envenenado', 'Paralisado', 'Adormecido', 'Congelado'][pkmData.id_cond - 1] 
            : null;
        
        // Controle de estado
        this.forcaBonus = 0;
        this.protegido = false;
        this.ultimoAtaque = null;
        this.velocPenalidade = 0;
        this.mortosNoBolso = 0; // Para Valquíria
    }

    get vivo() { return this.hp > 0; }

    get forcaEfetiva() {
        let f = this.forcaBase + this.forcaBonus;
        if (this.status === 'Queimado') f = f * 0.75;
        return Math.max(0, Math.floor(f));
    }

    get velocEfetiva() {
        let v = this.velocBase - this.velocPenalidade;
        if (this.status === 'Envenenado') v = v * 0.80;
        if (this.status === 'Paralisado') v = v * 0.50;
        return Math.max(0, Math.floor(v));
    }
}

export class BattleEngine {
    constructor(dadosBatalha, callbacksUI) {
        this.movesCatalog = dadosBatalha.movesCatalog;
        this.typeChart = dadosBatalha.typeChart;
        this.itemsCatalog = dadosBatalha.itemsCatalog;
        
        this.usuarioTime = dadosBatalha.userTeam.map(p => new Lutador(p, this.movesCatalog));
        this.inimigoTime = dadosBatalha.enemyTeam.map(p => new Lutador(p, this.movesCatalog));
        
        this.usuarioAtivoIndex = 0;
        this.inimigoAtivoIndex = 0;
        
        this.inventario = { ...dadosBatalha.userInventory };
        this.itensUsados = {};
        
        this.callbacks = callbacksUI; // Funções para atualizar a tela
        this.turno = 0;
        this.esperandoAcao = false;
        this.resolverAcao = null;
    }

    // GETTERS RÁPIDOS
    get usuarioAtivo() { return this.usuarioTime[this.usuarioAtivoIndex]; }
    get inimigoAtivo() { return this.inimigoTime[this.inimigoAtivoIndex]; }
    get usuarioVivo() { return this.usuarioTime.filter(p => p.vivo).length; }
    get inimigoVivo() { return this.inimigoTime.filter(p => p.vivo).length; }
    get totalVivo() { return this.usuarioVivo + this.inimigoVivo; }

    // COMUNICAÇÃO COM A UI
    log(msg) { this.callbacks.log(msg); }
    emitir(evento, dados) { this.callbacks.evento(evento, dados); }

    // --- LOOP PRINCIPAL ---
    async iniciar() {
        this.log("A batalha começou!");
        this.turno = 1;
        await this.loopTurno();
    }

    // Chamado pela UI quando o jogador clica em um botão
    submitAcao(acao) {
        if (!this.esperandoAcao) return;
        this.esperandoAcao = false;
        this.resolverAcao(acao);
    }

    async pedirAcaoJogador() {
        return new Promise(resolve => {
            this.esperandoAcao = true;
            this.resolverAcao = resolve;
            this.callbacks.pedirAcao(); // Avisa o batalha.php para liberar os botões
        });
    }

    async loopTurno() {
        while (this.usuarioVivo > 0 && this.inimigoVivo > 0) {
            this.log(`--- Turno ${this.turno} ---`);
            
            // 1. Escolha de ações
            const acaoUser = await this.pedirAcaoJogador();
            
            if (acaoUser.tipo === 'fugir') {
                this.finalizar('enemy', 'fuga');
                return;
            }

            if (acaoUser.tipo === 'trocar') {
                this.executarTroca('usuario', acaoUser.index);
                await this.executarAtaqueInimigo();
                await this.fimDeTurno();
                this.turno++;
                continue;
            }

            if (acaoUser.tipo === 'item') {
                this.executarItem(acaoUser.id_item, acaoUser.index);
                await this.executarAtaqueInimigo();
                await this.fimDeTurno();
                this.turno++;
                continue;
            }

            // Se for Ataque, a IA também escolhe
            const acaoInimigo = BatalhaIA.escolherAcao(this);
            
            // 2. Resolve os ataques
            await this.resolverAtaques(acaoUser, acaoInimigo);
            
            // 3. Fim de Turno
            await this.fimDeTurno();
            this.turno++;
        }
    }

    // --- RESOLUÇÃO DE ATAQUES ---
    async resolverAtaques(acaoUser, acaoInimigo) {
        const golpeUser = this.movesCatalog[this.usuarioAtivo.atk1 + (acaoUser.slot === 2 ? 0 : 0)]; // Necessita ajuste para pegar o ID correto
        // Correção para pegar o ID real do golpe:
        const idGolpeUser = acaoUser.slot === 1 ? this.usuarioAtivo.atk1 : this.usuarioAtivo.atk2;
        const idGolpeIni = acaoInimigo.slot === 1 ? this.inimigoAtivo.atk1 : this.inimigoAtivo.atk2;

        const movUser = this.movesCatalog[idGolpeUser];
        const movIni = this.movesCatalog[idGolpeIni];

        // Verifica status (paralisia, sono)
        const podeUser = this.checarStatusAntesDeAgir(this.usuarioAtivo);
        const podeIni = this.checarStatusAntesDeAgir(this.inimigoAtivo);

        if (!podeUser) this.log(`${this.usuarioAtivo.nome} não pode se mover!`);
        if (!podeIni) this.log(`${this.inimigoAtivo.nome} não pode se mover!`);

        // Ordem de prioridade
        let primeiro = null, segundo = null;
        
        if (podeUser && podeIni) {
            if (movUser.priori !== movIni.priori) {
                primeiro = movUser.priori < movIni.priori ? 'user' : 'ini';
            } else if (this.usuarioAtivo.velocEfetiva !== this.inimigoAtivo.velocEfetiva) {
                primeiro = this.usuarioAtivo.velocEfetiva > this.inimigoAtivo.velocEfetiva ? 'user' : 'ini';
            } else {
                primeiro = Math.random() < 0.5 ? 'user' : 'ini';
            }
            segundo = primeiro === 'user' ? 'ini' : 'user';
        } else if (podeUser) {
            primeiro = 'user';
        } else if (podeIni) {
            primeiro = 'ini';
        }

        // Executa na ordem
        if (primeiro === 'user') {
            await this.executarGolpe(this.usuarioAtivo, this.inimigoAtivo, movUser, 'usuario');
            if (this.inimigoAtivo.vivo) await this.executarGolpe(this.inimigoAtivo, this.usuarioAtivo, movIni, 'inimigo');
        } else if (primeiro === 'ini') {
            await this.executarGolpe(this.inimigoAtivo, this.usuarioAtivo, movIni, 'inimigo');
            if (this.usuarioAtivo.vivo) await this.executarGolpe(this.usuarioAtivo, this.inimigoAtivo, movUser, 'usuario');
        }
        
        // Se algum morreu, checa substituições
        await this.checarDesmaios();
    }

    checarStatusAntesDeAgir(pkm) {
        if (pkm.status === 'Congelado') return false;
        if (pkm.status === 'Adormecido') {
            if (Math.random() * 100 <= 40) {
                pkm.status = null;
                this.log(`${pkm.nome} acordou!`);
                return true;
            }
            return false;
        }
        if (pkm.status === 'Paralisado') {
            if (Math.random() * 100 <= 33) {
                return false;
            }
        }
        return true;
    }

    async executarGolpe(atacante, defensor, golpe, ladoAtacante) {
        this.log(`${atacante.nome} usou ${golpe.nome_atk}!`);

        // Proteção (Barreira / Sepultar)
        if (defensor.protegido) {
            defensor.protegido = false;
            this.log(`${defensor.nome} bloqueou o ataque!`);
            atacante.ultimoAtaque = golpe.id_atk;
            return;
        }

        // Verifica se é golpe especial
        if (SPECIAL_MOVES[golpe.id_atk]) {
            SPECIAL_MOVES[golpe.id_atk].executar(this, atacante, defensor);
            atacante.ultimoAtaque = golpe.id_atk;
            await this.delay(1000);
            return;
        }

               // Golpe de dano normal
        if (golpe.mult_dano > 0) {
            let dano = 0;
            
            // Exceção da Lança-Banana + Bombanana
            if (atacante.id_esp === 6 && golpe.id_atk === 4) {
                dano = 20 + (this.totalVivo - 1) * 5;
            } else {
                // Usa '?? 1' para respeitar imunidade (0x)
                const mod = this.typeChart?.[golpe.id_tipo]?.[defensor.id_tipo] ?? 1;
                if (mod === 0) {
                    this.log(`Não teve efeito em ${defensor.nome}...`);
                    atacante.ultimoAtaque = golpe.id_atk;
                    await this.delay(1000);
                    return;
                }
                dano = Math.ceil(atacante.forcaEfetiva * golpe.mult_dano * mod);
            }

            defensor.hp = Math.max(0, defensor.hp - dano);
            this.log(`Causou ${dano} de dano!`);
            this.emitir('hpChange', defensor);

            // Congelado quebra ao levar dano
            if (defensor.status === 'Congelado') {
                defensor.status = null;
                this.log(`${defensor.nome} descongelou!`);
            }

            // Efeito secundário (Status) - Corrigido 'Paralisado' com S
            if (golpe.id_cond && Math.random() * 100 <= golpe.chance_cond) {
                if (!defensor.status) {
                    const statusList = ['Queimado', 'Envenenado', 'Paralisado', 'Adormecido', 'Congelado'];
                    defensor.status = statusList[golpe.id_cond - 1];
                    this.log(`${defensor.nome} sofreu ${defensor.status}!`);
                }
            }

            if (!defensor.vivo) {
                this.log(`${defensor.nome} desmaiou!`);
                // Habilidade passiva da Batatamina
                if (SPECIAL_SPECIES[defensor.id_esp]?.aoMorrer) {
                    SPECIAL_SPECIES[defensor.id_esp].aoMorrer(this, defensor, atacante);
                }
            }
        }
        
        atacante.ultimoAtaque = golpe.id_atk;
        await this.delay(1000);
    }

    // --- TROCA E ITENS ---
    executarTroca(lado, index) {
        if (lado === 'usuario') {
            this.usuarioAtivo.ultimoAtaque = null;
            this.usuarioAtivoIndex = index;
            this.log(`Você trocou para ${this.usuarioAtivo.nome}!`);
            this.emitir('troca', this.usuarioAtivo);
            
            // Valquíria ao entrar (acumula +4 de força por aliado derrotado enquanto estava no bolso)
            if (this.usuarioAtivo.id_esp === 40 && this.usuarioAtivo.mortosNoBolso > 0) {
                this.usuarioAtivo.forcaBonus += 4 * this.usuarioAtivo.mortosNoBolso;
                this.log(`A força de ${this.usuarioAtivo.nome} aumentou pela queda dos aliados!`);
                this.usuarioAtivo.mortosNoBolso = 0;
            }
        } else {
            this.inimigoAtivo.ultimoAtaque = null;
            this.inimigoAtivoIndex = index;
            this.log(`O oponente trocou para ${this.inimigoAtivo.nome}!`);
            this.emitir('troca', this.inimigoAtivo);
        }
    }

    executarItem(id_item, index_pkm) {
        const pkm = this.usuarioTime[index_pkm];
        const item = this.itemsCatalog[id_item];
        if (!pkm || !item) return;
        
        this.log(`Usou ${item.nome_item} em ${pkm.nome}.`);
        
        if (id_item === 12) { // Cenoura
            if (pkm.hp === 0) pkm.hp = Math.ceil(pkm.hpMax / 2);
        } else if (id_item === 11) { // Frangão
            pkm.hp = pkm.hpMax;
            pkm.status = null;
        } else if (id_item >= 5 && id_item <= 9) { // Cura status específico
            pkm.status = null;
        } else { // Cura HP
            const curas = {1: 6, 2: 12, 3: 45, 4: 100};
            if (curas[id_item]) {
                pkm.hp = Math.min(pkm.hpMax, pkm.hp + curas[id_item]);
            }
        }
        
        this.inventario[id_item]--;
        this.itensUsados[id_item] = (this.itensUsados[id_item] || 0) + 1;
        this.emitir('hpChange', pkm);
    }

    async executarAtaqueInimigo() {
        const acaoIni = BatalhaIA.escolherAcao(this);
        const idGolpe = acaoIni.slot === 1 ? this.inimigoAtivo.atk1 : this.inimigoAtivo.atk2;
        const movIni = this.movesCatalog[idGolpe];
        
        if (this.checarStatusAntesDeAgir(this.inimigoAtivo)) {
            await this.executarGolpe(this.inimigoAtivo, this.usuarioAtivo, movIni, 'inimigo');
            await this.checarDesmaios();
        } else {
            this.log(`${this.inimigoAtivo.nome} não pode se mover!`);
        }
    }

    // --- FIM DE TURNO E DESMAIOS ---
    async fimDeTurno() {
        const todos = [...this.usuarioTime, ...this.inimigoTime];
        todos.forEach(pkm => {
            if (!pkm.vivo) return;
            if (pkm.status === 'Queimado') {
                const dano = Math.ceil(pkm.hpMax * 0.05);
                pkm.hp = Math.max(0, pkm.hp - dano);
                this.log(`${pkm.nome} sofreu ${dano} de queimadura!`);
                this.emitir('hpChange', pkm);
            } else if (pkm.status === 'Envenenado') {
                const dano = Math.ceil(pkm.hpMax * 0.05);
                pkm.hp = Math.max(0, pkm.hp - dano);
                this.log(`${pkm.nome} sofreu ${dano} de veneno!`);
                this.emitir('hpChange', pkm);
            }
            pkm.protegido = false; // Reseta proteção no fim do turno
        });

        await this.checarDesmaios();
    }

    async checarDesmaios() {
        // Se o inimigo morreu
        if (!this.inimigoAtivo.vivo && this.inimigoVivo > 0) {
            const novoIndex = this.inimigoTime.findIndex(p => p.vivo);
            this.inimigoAtivoIndex = novoIndex;
            this.log(`O oponente enviou ${this.inimigoAtivo.nome}!`);
            this.emitir('troca', this.inimigoAtivo);
        }
        
        // Se o usuário morreu
        if (!this.usuarioAtivo.vivo && this.usuarioVivo > 0) {
            // Incrementa mortosNoBolso para qualquer Valquíria viva no banco
            this.usuarioTime.forEach(pkm => {
                if (pkm.vivo && pkm.id_esp === 40) {
                    pkm.mortosNoBolso++;
                }
            });

            // Destrava submitAcao() para receber o clique da troca forçada!
            const novoIndex = await new Promise(resolve => {
                this.esperandoAcao = true;
                this.resolverAcao = (acao) => resolve(acao.index);
                this.callbacks.pedirTroca(this.usuarioTime, resolve);
            });
            this.executarTroca('usuario', novoIndex);
        }

        // Fim de jogo
        if (this.usuarioVivo === 0) {
            this.finalizar('enemy', 'faint');
        } else if (this.inimigoVivo === 0) {
            this.finalizar('user', 'faint');
        }
    }

    delay(ms) { return new Promise(res => setTimeout(res, ms)); }

    finalizar(vencedor, motivo) {
        const ultimoInimigo = this.inimigoTime[this.inimigoTime.length - 1];
        
        this.callbacks.batalhaAcabou({
            winner: vencedor,
            reason: motivo,
            teamFinalHp: Object.fromEntries(this.usuarioTime.map(p => [p.id_pkm, p.hp])),
            itemsUsed: this.itensUsados,
            lastEnemyEspId: ultimoInimigo.id_esp,
            userActiveHp: this.usuarioAtivo.vivo ? this.usuarioAtivo.hp : 0,
            aliveUser: this.usuarioVivo
        });
    }
}
