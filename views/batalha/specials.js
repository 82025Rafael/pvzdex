// ============================================================
// BATTLE/SPECIALS.JS
// Efeitos de golpes que não causam dano (mult_dano == 0)
// ============================================================

const proteger = (engine, atacante) => {
    atacante.protegido = true;
    engine.log(`${atacante.nome} se protegeu!`);
};

const reduzirVelocidade = (engine, defensor) => {
    const atual = defensor.velocPenalidade ?? 0;
    if (atual >= 15) {
        engine.log(`A velocidade de ${defensor.nome} não pode diminuir mais!`);
        return;
    }
    defensor.velocPenalidade = atual + 5;
    engine.log(`${defensor.nome} ficou mais lento!`);
};

export const SPECIAL_MOVES = {
    // Fertilizar (Aumenta ataque em 20%, máximo de +60%)
    6: { 
        executar: (engine, atacante, defensor) => {
            const atual = atacante.forcaBonus ?? 0;
            if (atual >= 60) {
                engine.log(`O ataque de ${atacante.nome} já está no máximo!`);
                return;
            }
            atacante.forcaBonus = atual + 20;
            engine.log(`O ataque de ${atacante.nome} aumentou!`);
        }
    },
    // Barreira de Noz (ID 2) & Sepultar (ID 18)
    2: { executar: (engine, atacante) => proteger(engine, atacante) },
    18: { executar: (engine, atacante) => proteger(engine, atacante) },

    // Doce Saudável (Cura até 20 de vida)
    20: {
        executar: (engine, atacante, defensor) => {
            if (atacante.hp >= atacante.hpMax) {
                engine.log(`${atacante.nome} já está com a vida cheia!`);
                return;
            }
            const hpAntes = atacante.hp;
            atacante.hp = Math.min(atacante.hpMax, atacante.hp + 20);
            const curado = atacante.hp - hpAntes;

            engine.log(`${atacante.nome} recuperou ${curado} de vida!`);
            engine.emitir('hpChange', atacante);
        }
    },
    // Alfaiceberg (Congela 100%)
    8: {
        executar: (engine, atacante, defensor) => {
            if (defensor.status) {
                engine.log(`Mas falhou! ${defensor.nome} já está ${defensor.status.toLowerCase()}!`);
                return;
            }
            defensor.status = 'Congelado';
            engine.log(`${defensor.nome} foi congelado!`);
        }
    },
    // Insolação (ID 10) & Criomiolos (ID 14) - Reduzem velocidade
    10: { executar: (engine, atacante, defensor) => reduzirVelocidade(engine, defensor) },
    14: { executar: (engine, atacante, defensor) => reduzirVelocidade(engine, defensor) },

    // Dança do Sono (Aplica Sono 100%)
    16: {
        executar: (engine, atacante, defensor) => {
            if (defensor.status) {
                engine.log(`Mas falhou! ${defensor.nome} já está ${defensor.status.toLowerCase()}!`);
                return;
            }
            defensor.status = 'Adormecido';
            engine.log(`${defensor.nome} adormeceu!`);
        }
    }
};

// ============================================================
// HABILIDADES PASSIVAS DE ESPÉCIES (SPECIAL_SPECIES)
// ============================================================
export const SPECIAL_SPECIES = {
    // Batatamina (ID 5) - Explode ao morrer
    5: {
        aoMorrer: (engine, morto, assassino) => {
            engine.log(`A ${morto.nome} explodiu ao ser derrotada!`);
            
            if (assassino && assassino.vivo && assassino.hp > 0) {
                assassino.hp = Math.max(0, assassino.hp - 40);
                engine.log(`${assassino.nome} recebeu 40 de dano da explosão!`);
                engine.emitir('hpChange', assassino);

                // Se a explosão derrotar o atacante, atualiza o estado de vivo
                if (assassino.hp === 0) {
                    assassino.vivo = false;
                    engine.log(`${assassino.nome} também foi derrotado pela explosão!`);
                }
            }
        }
    }
};
