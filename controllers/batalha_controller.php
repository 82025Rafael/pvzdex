<?php


session_start();
require_once "../config.php";

if (empty($_SESSION['logado']) || empty($_SESSION['id_usr'])) {
    header("Location: ../views/login.php");
    exit;
}

$action = $_POST['action'] ?? $_GET['action'] ?? null;
if ($action === null && isset($_POST['pokemon_ids'])) {
    $action = 'iniciar';
}

// COMEÇANDO A BATALHA
if ($action === 'iniciar') {
    // Pega os IDs dos pokémons selecionados na tela anterior.
    $pokemons_ids = $_POST['pokemon_ids'] ?? [];
    
    if (empty($pokemons_ids)) {
        // Se não selecionou ninguém, volta pra seleção.
        header("Location: ../views/selecao/selecao.php");
        exit;
    }

    // USUARIO
    $usr = new Usuario();
    $usr->setId((int)$_SESSION['id_usr']);
    $usr->load();
    $fase_atual = $usr->getFase();

    // TIME DO USUARIO
    $userTeam = [];
    foreach ($pokemons_ids as $id_pkm) {
        $pkm = new Pokemon();
        $pkm->setId((int)$id_pkm);
        $pkm->load();
        
        $esp = new Especie();
        $esp->setId($pkm->getEsp());
        $esp->load();
        
        $userTeam[] = [ // JSON DO TIME DO USUARIO
            'id_pkm' => $pkm->getId(),
            'nome' => $pkm->getNome(),
            'vida_atual' => $pkm->getVida(),
            'vida_max' => $esp->getVida(),
            'id_tipo' => $esp->getTipo(),
            'forca' => $esp->getForca(),
            'veloc' => $esp->getVeloc(),
            'atk1' => $esp->getAtk1(),
            'atk2' => $esp->getAtk2(),
            'id_cond' => $pkm->getCond(),
            'foto' => $esp->getFoto()
        ];
    }

    // INIMIGO BASEADO NA FASE (que vem do usuario do banco)
    $ini = new Inimigo();
    $ini->setInim($fase_atual); 
    $ini->loadInim();   
    $ini->setId($fase_atual);        
    $ini->load();    
      // CARREGA DADOS INIMIGOS PELA HERANÇA COM USUARIO

    // TIME DO INIMIGO
    $enemyTeam = [];
    $inim_pokemons = $ini->pegarMeusPokemons();
    foreach ($inim_pokemons as $ep) {
        $esp = new Especie();
        $esp->setId($ep['id_esp']);
        $esp->load();
        
        $enemyTeam[] = [ // JSON DO TIME DO INIMIGO
            'id_pkm' => $ep['id_pkm'],
            'nome' => $ep['nome_pkm'],
            'vida_atual' => $ep['vida_restante_pkm'],
            'vida_max' => $esp->getVida(),
            'id_tipo' => $esp->getTipo(),
            'forca' => $esp->getForca(),
            'veloc' => $esp->getVeloc(),
            'atk1' => $esp->getAtk1(),
            'atk2' => $esp->getAtk2(),
            'id_cond' => null, // Pode ser buscado se o inimigo iniciar com status.
            'foto' => $esp->getFoto()
        ];
    }

        // --- 4. Carregando Catálogos Globais (Ataques, Tipos, Itens) ---
    $pdo = connect();

    // Catálogo de Ataques
    $stmt = $pdo->query("SELECT id_atk, nome_atk, id_tipo, mult_dano, priori, id_cond, chance_cond FROM ataque");
    $movesCatalog = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $movesCatalog[$row['id_atk']] = $row;
    }

    // Tabela de Tipos (Matriz de Efetividade)
    $stmt = $pdo->query("SELECT id_tipo, f1, f2, w1, w2 FROM tipo");
    $typeChart = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $atkType = $row['id_tipo'];
        $typeChart[$atkType] = [];
        for ($def = 1; $def <= 10; $def++) {
            if ($def == $row['f1'] || $def == $row['f2']) {
                $typeChart[$atkType][$def] = 2.0; // Super efetivo
            } elseif ($def == $row['w1'] || $def == $row['w2']) {
                $typeChart[$atkType][$def] = 0.5; // Pouco efetivo
            } else {
                $typeChart[$atkType][$def] = 1.0; // Neutro
            }
        }
    }

    // Catálogo de Itens de Cura (IDs de 1 a 12) - CORRIGIDO COM A FOTO
    $stmt = $pdo->query("SELECT id_item, nome_item, desc_item, foto FROM item WHERE id_item <= 12");
    $itemsCatalog = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $itemsCatalog[$row['id_item']] = $row;
    }

    // Inventário atual do jogador - CORRIGIDO (Query estava errada e dando erro de parâmetros)
    $stmt = $pdo->prepare("SELECT id_item, quant_item FROM inventario WHERE id_usr = ?");
    $stmt->execute([$usr->getID()]);
    $userInventory = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $userInventory[$row['id_item']] = $row['quant_item'];
    }

    // CENARIOS PARA CADA SITUAÇÂO
    $cenarios = [
        1 => '../../ImagensPVZ/Cenários/campoGW.png',
        2 => '../../ImagensPVZ/Cenários/cenariozumbibasico.png',
        3 => '../../ImagensPVZ/Cenários/casadoszumbis.png'
    ];
    $musicas = [ // MUSICAS DO JOGO
        1 => '../../Música/Laura Shigihara - Loonboon.mp3',
        2 => '../../Música/Laura Shigihara - Ultimate Battle.mp3',
        3 => '../../Música/Laura Shigihara - Brainiac Maniac.mp3'
    ];

    // JSON COMPLETASSO NOSSAAAAAAAAAAAAA
    $battleData = [
        'userTeam' => $userTeam,
        'enemyTeam' => $enemyTeam,
        'movesCatalog' => $movesCatalog,
        'typeChart' => $typeChart,
        'itemsCatalog' => $itemsCatalog,
        'userInventory' => $userInventory,
        'enemyData' => [
            'id_inim' => $ini->getInim(),
            'nome' => $ini->getNome(),
            'txt_entra' => $ini->getEntra(),
            'txt_mata' => $ini->getMata(),
            'txt_morre' => $ini->getMorre(),
            'foto' => $ini->getFoto()
        ],
        'userData' => [
            'nome' => $usr->getNome(),
            'foto' => $usr->getFoto(),
            'dinheiro' => $usr->getDinheiro()
        ],
        'cenario' => $cenarios[$fase_atual] ?? $cenarios[1],
        'musica' => $musicas[$fase_atual] ?? $musicas[1]
    ];

    // Salva na sessão para a View batalha.php ler e repassar pro JS.
    $_SESSION['battle_data'] = $battleData;
    
    header("Location: ../views/batalha/batalha.php");
    exit;
}

// QUANDO A BATALHA ACABA ELE VOLTA AQUI
if ($action === 'finalizar') {
    // Lê o corpo da requisição JSON (enviado via fetch pelo JS)
    $json = file_get_contents('php://input');
    $input = json_decode($json, true);

    if (!$input) {
        http_response_code(400);
        echo json_encode(['error' => 'Dados inválidos.']);
        exit;
    }

    // Variáveis enviadas pela engine
    $winner = $input['winner'] ?? null; // 'user' ou 'enemy'
    $teamFinalHp = $input['teamFinalHp'] ?? [];
    $itemsUsed = $input['itemsUsed'] ?? [];
    $lastEnemyEspId = $input['lastEnemyEspId'] ?? null;
    $userActiveHp = $input['userActiveHp'] ?? 0;
    $aliveUser = $input['aliveUser'] ?? 0;

    if ($winner !== 'user' && $winner !== 'enemy') {
        http_response_code(400);
        echo json_encode(['error' => 'Resultado de vitória inválido.']);
        exit;
    }

    if (!isset($_SESSION['battle_data'])) { // VER SE TEVE UMA BATALHA EM PRIMEIRO LUGAR
        http_response_code(400);
        echo json_encode(['error' => 'Sessão de batalha não encontrada.']);
        exit;
    }

    $usr = new Usuario();
    $usr->setId((int)$_SESSION['id_usr']);
    $usr->load();

    $pdo = connect();

    try {
        $pdo->beginTransaction(); // Inicia a transação para garantir que nada falhe pela metade.

        // ATUALIZAR DADOS DO USUARIO
        if ($winner === 'user') {
            // JOGADOR VENCEU EBAAAAAAAAAAAAAAAAAAAa atualiza tuDOOOOo
            foreach ($teamFinalHp as $id_pkm => $hp) {
                $stmt = $pdo->prepare("UPDATE pokemon SET vida_atual = ? WHERE id_pkm = ?");
                $stmt->execute([max(0, (int)$hp), (int)$id_pkm]);
            }

            // Calcula a recompensa 
            $esp = new Especie();
            $esp->setId((int)$lastEnemyEspId);
            $esp->load();
            $preco = $esp->getPreco();

 
            $divisor = max(1, $userActiveHp / 2); 
            $recompensa = ceil($preco / $divisor) + $aliveUser;

            // Atualiza dinheiro
            $usr->setDinheiro($usr->getDinheiro() + $recompensa);


            //  ID maximo de inimigo cadastrado no banco
            $stmtFase = $pdo->query("SELECT MAX(id_inim) FROM inimigo");
            $max_fase = (int)$stmtFase->fetchColumn();

            // Só avança a fase se o jogador ainda não estiver na ultima
            if ($usr->getFase() < $max_fase) {
                $usr->setFase($usr->getFase() + 1);
            }
            
            // Salva o usuário (dinheiro atualizado e, se for o caso, nova fase)
            $usr->updateBD();

        } else {
            // JOGADOR PERDEU/FUGIU (ruim)
            //CURA TODO MUNDO E TIRA CONDIÇÕES, MAS NAO PASSA
            $stmt = $pdo->prepare("
                UPDATE pokemon 
                SET vida_atual = (SELECT vida_max FROM especie WHERE id_esp = pokemon.id_esp), 
                    id_cond = NULL 
                WHERE id_usr = ?
            ");
            $stmt->execute([$usr->getID()]);
        }

            // Atualizar inventario do usuario
        foreach ($itemsUsed as $id_item => $quant) {
            if ($quant > 0) {
                $stmt = $pdo->prepare("UPDATE inventario SET quant_item = quant_item - ? WHERE id_usr = ? AND id_item = ?");
                $stmt->execute([(int)$quant, $usr->getID(), (int)$id_item]);
                
                // Remove a linha do inventário se a quantidade zerar
                $stmt = $pdo->prepare("DELETE FROM inventario WHERE quant_item <= 0 AND id_usr = ? AND id_item = ?");
                $stmt->execute([$usr->getID(), (int)$id_item]);
            }
        }

        // REGISTRAR O LOG
        $nome_usr = $usr->getNome();
        $nome_inim = $_SESSION['battle_data']['enemyData']['nome'];
        $ganhou = ($winner === 'user') ? 1 : 0;

        $stmt = $pdo->prepare("INSERT INTO log (nome_usr, nome_inim, ganhou_perdeu) VALUES (?, ?, ?)");
        $stmt->execute([$nome_usr, $nome_inim, $ganhou]);

        $pdo->commit(); // Confirma tudo no banco

        // Limpa os dados da batalha na sessão
        unset($_SESSION['battle_data']);

        // Retorna sucesso para o JS redirecionar
        echo json_encode([
            'success' => true, 
            'redirect' => '../views/selecao/selecao.php'
        ]);
        exit;

    } catch (Exception $e) {
        $pdo->rollBack(); // Desfaz tudo se der erro
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao salvar resultado: ' . $e->getMessage()]);
        exit;
    }
}

// Se chegar aqui sem ação definida, volta pra seleção.
header("Location: ../views/selecao/selecao.php");
exit;