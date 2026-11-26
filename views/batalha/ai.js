// ============================================================
// BATTLE/AI.JS
// Inteligência Artificial do Inimigo
// ============================================================

export const BatalhaIA = {
    // Estado interno para alternar entre ataque e suporte
    ultimaAcao: 'ataque',

    escolherAcao(engine) {
        const eu = engine.inimigoAtivo;
        const oponente = engine.usuarioAtivo;

        const golpe1 = engine.movesCatalog[eu.atk1];
        const golpe2 = eu.atk2 ? engine.movesCatalog[eu.atk2] : null;

        // 1. Se só tem um golpe, usa ele
        if (!golpe2) return { tipo: 'atacar', slot: 1 };

        const isDano1 = golpe1.mult_dano > 0;
        const isDano2 = golpe2.mult_dano > 0;

        // 2. Se ambos são de dano, escolhe o de maior vantagem de tipo
        if (isDano1 && isDano2) {
            const mod1 = engine.typeChart[golpe1.id_tipo][oponente.id_tipo] || 1;
            const mod2 = engine.typeChart[golpe2.id_tipo][oponente.id_tipo] || 1;

            if (mod1 > mod2) return { tipo: 'atacar', slot: 1 };
            if (mod2 > mod1) return { tipo: 'atacar', slot: 2 };
            // Empate: 50/50
            return { tipo: 'atacar', slot: Math.random() < 0.5 ? 1 : 2 };
        }

        // 3. Se um é dano e o outro é suporte, alterna (inicia atacando)
        if (isDano1 !== isDano2) {
            if (this.ultimaAcao === 'ataque') {
                this.ultimaAcao = 'suporte';
                return { tipo: 'atacar', slot: isDano1 ? 2 : 1 }; // Usa o suporte
            } else {
                this.ultimaAcao = 'ataque';
                return { tipo: 'atacar', slot: isDano1 ? 1 : 2 }; // Usa o dano
            }
        }

        // 4. Se ambos são de suporte, escolhe aleatório
        return { tipo: 'atacar', slot: Math.random() < 0.5 ? 1 : 2 };
    }
};