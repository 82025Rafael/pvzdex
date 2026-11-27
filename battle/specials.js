// ============================================================
// BATTLE/SPECIALS.JS
// Efeitos de golpes que não causam dano e passivas de espécies
// ============================================================

export const SPECIAL_MOVES = {
    // 2: Barreira de Noz (proteção)
    2: {
        executar: async (engine, atacante, defensor) => {
            atacante.protegido = true;
            await engine.log(`${atacante.nome} se protegeu!`);
        }
    },
    // 6: Fertilizar (+6 ataque)
    6: {
        executar: async (engine, atacante, defensor) => {
            atacante.forcaBonus += 6;
            engine.emitir('hpChange', atacante);
            await engine.log(`O ataque de ${atacante.nome} aumentou em 6!`);
        }
    },
    // 8: Alfaiceberg (congela 100%)
    8: {
        executar: async (engine, atacante, defensor) => {
            if (!defensor.status) {
                defensor.status = 'Congelado';
                engine.emitir('statusChange', defensor);
                await engine.log(`${defensor.nome} foi congelado!`);
            }
        }
    },
    // 10: Insolação (reduz prioridade)
    10: {
        executar: async (engine, atacante, defensor) => {
            defensor.velocPenalidade = 5;
            await engine.log(`${defensor.nome} ficou mais lento!`);
        }
    },
    // 12: Miolo no Prato (+10 ataque)
    12: {
        executar: async (engine, atacante, defensor) => {
            atacante.forcaBonus += 5;
            engine.emitir('hpChange', atacante);
            await engine.log(`O ataque de ${atacante.nome} aumentou em 4!`);
        }
    },
    // 14: Criomiolos (reduz prioridade)
    14: {
        executar: async (engine, atacante, defensor) => {
            defensor.velocPenalidade = 5;
            await engine.log(`${defensor.nome} ficou mais lento!`);
        }
    },
    // 16: Dança do Sono (sono 100%)
    16: {
        executar: async (engine, atacante, defensor) => {
            if (!defensor.status) {
                defensor.status = 'Adormecido';
                engine.emitir('statusChange', defensor);
                await engine.log(`${defensor.nome} adormeceu!`);
            }
        }
    },
    // 18: Sepultar (proteção)
    18: {
        executar: async (engine, atacante, defensor) => {
            atacante.protegido = true;
            await engine.log(`${atacante.nome} se protegeu!`);
        }
    },
    // 20: Doce Saudável (cura 20)
    20: {
        executar: async (engine, atacante, defensor) => {
            const cura = Math.min(20, atacante.hpMax - atacante.hp);
            atacante.hp += cura;
            engine.emitir('hpChange', atacante);
            await engine.log(`${atacante.nome} curou ${cura} de vida!`);
        }
    }
};

