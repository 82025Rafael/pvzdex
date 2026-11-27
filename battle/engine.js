
import { SPECIAL_MOVES } from './specials.js'; // PEGA OS GOLPES QUE NAO DAO DANO
import { BatalhaIA } from './ai.js'; // PEGA O BOT QUE ESCOLHE GOLPES INIMIGOS

export class Lutador { // OS BIXO BRABO ATIVO
    constructor(pkmData) { // PEGA OS DADOS DE JSON DO POKEMON
        Object.assign(this, pkmData);
        this.hpMax = pkmData.vida_max; // SETANDO VALORES
        this.hp = pkmData.vida_atual;
        this.forcaBase = pkmData.forca; 
        this.velocBase = pkmData.veloc;

        const statusMap = { 1: 'Queimado', 2: 'Envenenado', 3: 'Paralisado', 4: 'Adormecido', 5: 'Congelado' };
        this.status = pkmData.id_cond ? statusMap[pkmData.id_cond] : null; // DEFININDO OS STATUS A PARTIR DO ID

        this.forcaBonus = 0; 
        this.protegido = false; 
        this.ultimoAtaque = null;
        this.velocPenalidade = 0;
        this.mortosNoBolso = 0;
        this.ultimaAcaoIA = 'ataque';
    }

    get vivo() { return this.hp > 0; }

    get forcaEfetiva() {
        let f = this.forcaBase + this.forcaBonus; // CALCULO DE FORCA TOTAL QUE APARECE DO BIXO
        if (this.status === 'Queimado') f = f * 0.75; // DIMINUI ATAQUE QUANDO TA QUEIMADO 75%
        return Math.max(0, Math.floor(f)); 
    }

    get velocEfetiva() {
        let v = this.velocBase - this.velocPenalidade;
        if (this.status === 'Envenenado') v = v * 0.80;
        if (this.status === 'Paralisado') v = v * 0.50; // DIMINUI VELOC QUANDO PARALIZADO OU ENVEN
        return Math.max(0, Math.floor(v));
    }
}

export class BattleEngine {
    constructor(dadosBatalha, callbacksUI) {
        this.movesCatalog = dadosBatalha.movesCatalog; // SETANDO OS GOLPES, TIPOS E ITEMS QUE EXISTEM
        this.typeChart = dadosBatalha.typeChart;
        this.itemsCatalog = dadosBatalha.itemsCatalog;

        this.usuarioTime = dadosBatalha.userTeam.map(p => new Lutador(p)); // CRIANDO LUTADOR DO USUARIO E INIMIGO A PARTIR DOS DADOS DO JSON
        this.inimigoTime = dadosBatalha.enemyTeam.map(p => new Lutador(p));

        this.usuarioAtivoIndex = 0; // NUMERO DO BIXO NA FILA A SER USADO
        this.inimigoAtivoIndex = 0;

        this.inventario = { ...dadosBatalha.userInventory };
        this.itensUsados = {}; // ESVAZIA O VETOR DE QUAIS ITENS FORAM GASTOS PRA SEMPRE RENOVAR E TIRAR QUANDO ACABAR

        this.callbacks = callbacksUI; // CONFIG INTERNA DA INTERFACE
        this.batalhaAtiva = true;
        this.esperandoClique = false;
        this.resolverClique = null;
        this.esperandoAcao = false;
        this.resolverAcao = null;
    }

    get usuarioAtivo() { return this.usuarioTime[this.usuarioAtivoIndex]; } // GET PRA MOSTRAR O POKEMON ATUAL
    get inimigoAtivo() { return this.inimigoTime[this.inimigoAtivoIndex]; } 
    get usuarioVivo() { return this.usuarioTime.filter(p => p.vivo).length; } // GET POKEMONS VIVOS DO USUARIO
    get inimigoVivo() { return this.inimigoTime.filter(p => p.vivo).length; }
    get totalVivo() { return this.usuarioVivo + this.inimigoVivo; }

    async log(texto, lado = 'usuario') {
        if (!this.batalhaAtiva) return;
        this.callbacks.log(texto, lado); 
        await this.esperarClique();
    }

    async esperarClique() { // MENU OCIOSO
        if (!this.batalhaAtiva) return;
        return new Promise(resolve => {
            this.esperandoClique = true;
            this.resolverClique = resolve;
            this.callbacks.mostrarIndicadorClique(true);
        });
    }

    avancarClique() { 
        if (this.esperandoClique && this.resolverClique) {
            this.esperandoClique = false;
            this.callbacks.mostrarIndicadorClique(false);
            const r = this.resolverClique;
            this.resolverClique = null;
            r();
        }
    }

    async pedirAcaoJogador() {
        return new Promise(resolve => {
            this.esperandoAcao = true;
            this.resolverAcao = resolve;
            this.callbacks.pedirAcao();
        });
    }

    submitAcao(acao) {
        if (!this.esperandoAcao) return;
        this.esperandoAcao = false;
        const r = this.resolverAcao;
        this.resolverAcao = null;
        r(acao);
    }

    async iniciar() {  // QUANDO A BATALHA COMEÇA
        await this.log("A batalha começou!", 'usuario');
        this.loopTurno();
    }

    async loopTurno() { // LOOP DE REPETIÇÃO DOS PROCESSOS DO TURNO
        while (this.batalhaAtiva && this.usuarioVivo > 0 && this.inimigoVivo > 0) { // SO TEM TURNO ENQUANTO TIVER ALGUEM VIVO NUM DOS TIMES
            const acaoUser = await this.pedirAcaoJogador();
            if (!this.batalhaAtiva) break;

            if (acaoUser.tipo === 'fugir') { // SE FUGIR O USUARIO PERDE
                await this.log("Você fugiu da batalha!", 'usuario');
                this.finalizar('enemy', 'fuga');
                return;
            }

            if (acaoUser.tipo === 'trocar') { // ACAO PRA TROCAR DE BIXO
                this.executarTroca('usuario', acaoUser.index);
                await this.log(`Você enviou ${this.usuarioAtivo.nome}!`, 'usuario');
                await this.executarAtaqueInimigo();
                if (!this.batalhaAtiva) break;
                await this.fimDeTurno();
                continue;
            }

            if (acaoUser.tipo === 'item') { // ACAO PRA USAR ITENS
                await this.executarItem(acaoUser.id_item, acaoUser.index);
                await this.executarAtaqueInimigo();
                if (!this.batalhaAtiva) break;
                await this.fimDeTurno();
                continue;
            }

            const acaoInimigo = BatalhaIA.escolherAcao(this); 
            await this.resolverAtaques(acaoUser, acaoInimigo); // ESPERAR O IA FAZER SUA DECISAO
            if (!this.batalhaAtiva) break;
            await this.fimDeTurno();
        }
    }

    async resolverAtaques(acaoUser, acaoInimigo) { // CALCULA OS ATAQUES
    const idGolpeUser = acaoUser.slot === 1 ? this.usuarioAtivo.atk1 : this.usuarioAtivo.atk2;
    const idGolpeIni  = acaoInimigo.slot === 1 ? this.inimigoAtivo.atk1 : this.inimigoAtivo.atk2;
    const movUser = this.movesCatalog[idGolpeUser];
    const movIni  = this.movesCatalog[idGolpeIni]; 

    const podeUser = this.checarStatusAntesDeAgir(this.usuarioAtivo);
    const podeIni  = this.checarStatusAntesDeAgir(this.inimigoAtivo);

    if (!podeUser) await this.log(`${this.usuarioAtivo.nome} não pode se mover!`, 'usuario'); // SE NAO PODE SE MOVER NAO MOVE
    if (!podeIni)  await this.log(`${this.inimigoAtivo.nome} não pode se mover!`, 'inimigo');

    let primeiro = null;
    if (podeUser && podeIni) {
        if (movUser.priori !== movIni.priori) {
            primeiro = movUser.priori < movIni.priori ? 'user' : 'ini';
        } else if (this.usuarioAtivo.velocEfetiva !== this.inimigoAtivo.velocEfetiva) {
            // ✅ CORREÇÃO: MENOR veloc age primeiro
            primeiro = this.usuarioAtivo.velocEfetiva < this.inimigoAtivo.velocEfetiva ? 'user' : 'ini';
        } else {
            primeiro = Math.random() < 0.5 ? 'user' : 'ini';
        }
    } else if (podeUser) {
        primeiro = 'user';
    } else if (podeIni) {
        primeiro = 'ini';
    }

    if (primeiro === 'user') {
        await this.executarGolpe(this.usuarioAtivo, this.inimigoAtivo, movUser, 'usuario');
        if (podeIni && this.inimigoAtivo.vivo && this.batalhaAtiva)
            await this.executarGolpe(this.inimigoAtivo, this.usuarioAtivo, movIni, 'inimigo');
    } else if (primeiro === 'ini') {
        await this.executarGolpe(this.inimigoAtivo, this.usuarioAtivo, movIni, 'inimigo');
        if (podeUser && this.usuarioAtivo.vivo && this.batalhaAtiva)
            await this.executarGolpe(this.usuarioAtivo, this.inimigoAtivo, movUser, 'usuario');
    }

    if (this.batalhaAtiva) await this.checarDesmaios();
}

    checarStatusAntesDeAgir(pkm) { // CHECAR OS STATUS DE PARALISIA ANTES DE ATACAR E NAO DEIXAR
        if (pkm.status === 'Congelado') return false;
        if (pkm.status === 'Adormecido') {
            if (Math.random() * 100 <= 40) {
                pkm.status = null;
                this.callbacks.evento('statusChange', pkm);
                return true;
            }
            return false;
        }
        if (pkm.status === 'Paralisado' && Math.random() * 100 <= 33) return false;
        return true;
    }

    async executarGolpe(atacante, defensor, golpe, lado) { // ATACA
        if (!this.batalhaAtiva) return;
        await this.log(`${atacante.nome} usou ${golpe.nome_atk}!`, lado);

        const isProtecao = (golpe.id_atk === 2 || golpe.id_atk === 18);
        if (isProtecao && atacante.ultimoAtaque === golpe.id_atk) {
            await this.log(`Mas falhou! Não pode proteger duas vezes seguidas.`, lado);
            return;
        }

        if (defensor.protegido) {
            defensor.protegido = false;
            atacante.ultimoAtaque = golpe.id_atk;
            await this.log(`${defensor.nome} bloqueou o ataque!`, lado === 'usuario' ? 'inimigo' : 'usuario');
            return;
        }

        if (SPECIAL_MOVES[golpe.id_atk]) { // EXECUTA ATAQUES ESPECIAIS
            await SPECIAL_MOVES[golpe.id_atk].executar(this, atacante, defensor);
            atacante.ultimoAtaque = golpe.id_atk;
            return;
        }

        if (golpe.mult_dano > 0) {
            let dano = 0;
            if (atacante.id_esp === 6 && golpe.id_atk === 4) {
                dano = 20 + (this.totalVivo - 1) * 5;
            } else {
                const mod = this.typeChart[golpe.id_tipo]?.[defensor.id_tipo] ?? 1;
                dano = Math.ceil(atacante.forcaEfetiva * golpe.mult_dano * mod);
            }

            defensor.hp = Math.max(0, defensor.hp - dano);
            this.emitir('hpChange', defensor); // MUDA A VIDA COM BASE NO DANO
            await this.log(`Causou ${dano} de dano em ${defensor.nome}.`, lado);

            if (defensor.status === 'Congelado') { // SE CONGELADO E FOR ATACADO DESCONGELA
                defensor.status = null;
                this.callbacks.evento('statusChange', defensor);
                await this.log(`${defensor.nome} descongelou!`, lado === 'usuario' ? 'inimigo' : 'usuario');
            }

            if (golpe.id_cond && Math.random() * 100 <= (golpe.chance_cond ?? 100)) {
                if (!defensor.status) { // IMPEDIR ACUMULO DE STATUS
                    const statusList = ['Queimado', 'Envenenado', 'Paralisado', 'Adormecido', 'Congelado'];
                    defensor.status = statusList[golpe.id_cond - 1];
                    this.callbacks.evento('statusChange', defensor);
                    await this.log(`${defensor.nome} sofreu ${defensor.status}!`, lado === 'usuario' ? 'inimigo' : 'usuario');
                }
            }

            if (!defensor.vivo) { // SE NAO TA MAIS VIVO MORRE
                await this.log(`${defensor.nome} desmaiou!`, lado === 'usuario' ? 'inimigo' : 'usuario');
                
            }
        }
        atacante.ultimoAtaque = golpe.id_atk;
    }

    executarTroca(lado, index) { // FAZER TROCA DE BIXOS
        if (lado === 'usuario') {
            this.usuarioAtivo.ultimoAtaque = null;
            this.usuarioAtivoIndex = index;
            this.emitir('troca', this.usuarioAtivo);
            
        } else {
            this.inimigoAtivo.ultimoAtaque = null;
            this.inimigoAtivo.ultimaAcaoIA = 'ataque';
            this.inimigoAtivoIndex = index;
            this.emitir('troca', this.inimigoAtivo);
        }
    }

    async executarItem(id_item, index_pkm) {
        const pkm = this.usuarioTime[index_pkm];
        const item = this.itemsCatalog[id_item];
        await this.log(`Usou ${item.nome_item} em ${pkm.nome}.`, 'usuario');

        if (id_item === 12) { 
            if (pkm.hp === 0) { pkm.hp = Math.ceil(pkm.hpMax / 2); pkm.status = null; }
        } else if (id_item === 11) { 
            pkm.hp = pkm.hpMax; pkm.status = null;
        } else if (id_item >= 5 && id_item <= 10) {
            pkm.status = null;
        } else {
            const curas = { 1: 6, 2: 12, 3: 45, 4: 70 };
            if (curas[id_item]) pkm.hp = Math.min(pkm.hpMax, pkm.hp + curas[id_item]);
        }

        this.inventario[id_item]--;
        this.itensUsados[id_item] = (this.itensUsados[id_item] || 0) + 1;
        this.emitir('hpChange', pkm);
        this.emitir('statusChange', pkm);
        await this.log(`O item foi aplicado com sucesso.`, 'usuario');
    }

    async executarAtaqueInimigo() { // ATAQUE DO INIMIGO
        if (!this.batalhaAtiva) return;
        const acaoIni = BatalhaIA.escolherAcao(this);
        const idGolpe = acaoIni.slot === 1 ? this.inimigoAtivo.atk1 : this.inimigoAtivo.atk2;
        const movIni = this.movesCatalog[idGolpe];

        if (this.checarStatusAntesDeAgir(this.inimigoAtivo)) { // MESMA COISA CHECA ANTES DE ATACAR
            await this.executarGolpe(this.inimigoAtivo, this.usuarioAtivo, movIni, 'inimigo');
            if (this.batalhaAtiva) await this.checarDesmaios();
        }
    }

    async fimDeTurno() { // TURNO ACABOU
        if (!this.batalhaAtiva) return;

        for (const pkm of [...this.usuarioTime, ...this.inimigoTime]) {
            if (!pkm.vivo) continue; // VERIFICAR SE A GALERA AINDA TA VIVA
            if (pkm.status === 'Queimado') {
                const dano = Math.ceil(pkm.hpMax * 0.1); // DANO DE QUEIMADURA
                pkm.hp = Math.max(0, pkm.hp - dano);
                this.emitir('hpChange', pkm);
                await this.log(`${pkm.nome} sofreu ${dano} de queimadura!`, pkm === this.usuarioAtivo ? 'usuario' : 'inimigo');
            } else if (pkm.status === 'Envenenado') {
                const dano = Math.ceil(pkm.hpMax * 0.1); // DANO DE VENENO
                pkm.hp = Math.max(0, pkm.hp - dano);
                this.emitir('hpChange', pkm);
                await this.log(`${pkm.nome} sofreu ${dano} de veneno!`, pkm === this.usuarioAtivo ? 'usuario' : 'inimigo');
            }
            pkm.protegido = false;
        }

        if (this.batalhaAtiva) await this.checarDesmaios();
    }

    async checarDesmaios() { 
        if (this.inimigoVivo === 0) { this.finalizar('user', 'faint'); return; }
        if (this.usuarioVivo === 0) { this.finalizar('enemy', 'faint'); return; }

        if (!this.inimigoAtivo.vivo) {
            const novoIndex = this.inimigoTime.findIndex(p => p.vivo);
            this.executarTroca('inimigo', novoIndex);
            await this.log(`O oponente enviou ${this.inimigoAtivo.nome}!`, 'inimigo');
        }

        if (!this.usuarioAtivo.vivo) {
            const novoIndex = await new Promise(resolve => {
                this.callbacks.pedirTroca(this.usuarioTime, resolve);
            });
            this.executarTroca('usuario', novoIndex);
            await this.log(`Você enviou ${this.usuarioAtivo.nome}!`, 'usuario');
        }
    }

    emitir(evento, dados) { this.callbacks.evento(evento, dados); } 

    finalizar(vencedor, motivo) {
        if (!this.batalhaAtiva) return;
        this.batalhaAtiva = false;
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