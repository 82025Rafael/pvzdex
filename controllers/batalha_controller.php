<?php
session_start();
require_once "../config.php";

// Lê o corpo JSON enviado pelo fetch logo no início
$json = file_get_contents('php://input');
$input = json_decode($json, true);

if (empty($_SESSION['logado']) || empty($_SESSION['id_usr'])) {
    if (is_array($input)) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(401);
        echo json_encode(['error' => 'Sessão expirada. Faça login novamente.']);
        exit;
    }
    header("Location: ../views/login.php");
    exit;
}

// Agora ele enxerga o 'action' vindo do JSON!
$action = $_POST['action'] ?? $_GET['action'] ?? $input['action'] ?? null;

if ($action === null && isset($_POST['pokemon_ids'])) {
    $action = 'iniciar';
}

// COMEÇANDO A BATALHA
if ($action === 'iniciar') {
    // Pega os IDs dos pokémons selecionados antes
    $pokemons_ids = $_POST['pokemon_ids'] ?? [];
    
    if (empty($pokemons_ids)) {
        // Se nao selecionou ninguem volta
        header("Location: ../views/selecao/selecao.php");
        exit;
    }

    // USUARIO
    $usr = new Usuario();
    $usr->setId((int)$_SESSION['id_usr']);
    $usr->load();
    $fase_atual = $usr->faseParaBatalha();

    // TIME DO USUARIO
    $userTeam = [];
    foreach ($pokemons_ids as $id_pkm) { // Para cada pokemon selecionado, ele faz o json do pokemon. Daí fica um vetorzão de json
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
    $inim_pokemons = $ini->pegarMeusPokemons(); // mesma coisa do usuario
    foreach ($inim_pokemons as $ep) {
        $esp = new Especie();
        $esp->setId($ep['id_esp']);
        $esp->load();
        
        $enemyTeam[] = [ // JSON DO TIME DO INIMIGO
            'id_pkm' => $ep['id_pkm'],
            'id_esp' => $ep['id_esp'], 
            'nome' => $ep['nome_pkm'],
            'vida_atual' => $ep['vida_restante_pkm'],
            'vida_max' => $esp->getVida(),
            'id_tipo' => $esp->getTipo(),
            'forca' => $esp->getForca(),
            'veloc' => $esp->getVeloc(),
            'atk1' => $esp->getAtk1(),
            'atk2' => $esp->getAtk2(),
            'id_cond' => null, //inimigo começa sempre sem condições
            'foto' => $esp->getFoto()
        ];
    }

        //CATALOGANDO TODOS OS ATAQUES E TIPOS
    $pdo = connect();

    // PEGANDO ataques
    $stmt = $pdo->query("SELECT id_atk, nome_atk, id_tipo, mult_dano, priori, id_cond, chance_cond FROM ataque"); // pega todos do banoc
    $movesCatalog = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // enfia num vetor associativo
        $movesCatalog[$row['id_atk']] = $row; //cada linha do catalogo é um ataque com seus dados
    }

    // Tabela de Tipos (TABELINHA)
    $stmt = $pdo->query("SELECT id_tipo, f1, f2, w1, w2 FROM tipo"); // pega todos os tipos
    $typeChart = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // enfia os dados no vetorzão e verifica por linha (cada tipo)
        $atkType = $row['id_tipo']; 
        $typeChart[$atkType] = [];
        for ($def = 1; $def <= 10; $def++) { // Para quem se defende do tipo inimigo
            if ($def == $row['f1'] || $def == $row['f2']) { // se é um dos efetivos ele recebe o dobro de dano
                $typeChart[$atkType][$def] = 2.0;
            } elseif ($def == $row['w1'] || $def == $row['w2']) { // se é um dos fracos ele recebe metade
                $typeChart[$atkType][$def] = 0.5; 
            } else {
                $typeChart[$atkType][$def] = 1.0; // se nenhum recebe só 1x mesmo
            }
        }
    }

    // Catalogando todos os itens
    $stmt = $pdo->query("SELECT id_item, nome_item, desc_item, foto FROM item WHERE id_item <= 12");
    $itemsCatalog = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // pra cada linha de item selecionado, enfia no catalogo com todos os dados
        $itemsCatalog[$row['id_item']] = $row;
    }

    // Carregando o inventario do usuario
    $stmt = $pdo->prepare("SELECT id_item, quant_item FROM inventario WHERE id_usr = ?");
    $stmt->execute([$usr->getID()]);
    $userInventory = []; // mesma coisaaaaaaaaaaa
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { // enfia as linhas com dados na tabela
        $userInventory[$row['id_item']] = $row['quant_item'];
    }

    // CENARIOS E MUSICAS PARA CADA ENCONTRO
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
        'enemyTeam' => $enemyTeam, // os dois times
        'movesCatalog' => $movesCatalog,
        'typeChart' => $typeChart,
        'itemsCatalog' => $itemsCatalog, // os catalogos
        'userInventory' => $userInventory, // o inventario
        'enemyData' => [
            'id_inim' => $ini->getInim(),
            'nome' => $ini->getNome(),
            'txt_entra' => $ini->getEntra(),
            'txt_mata' => $ini->getMata(),
            'txt_morre' => $ini->getMorre(),
            'foto' => $ini->getFoto(),
            'cor' => $ini->getCor()
        ],
        'userData' => [
            'nome' => $usr->getNome(),
            'foto' => $usr->getFoto(),
            'dinheiro' => $usr->getDinheiro(),
            'cor' => $usr->getCor()
        ],
        'cenario' => $cenarios[$fase_atual] ?? $cenarios[1],
        'musica' => $musicas[$fase_atual] ?? $musicas[1]
    ];

    // Salva tudo na sessão pra nao ter que ficar passando
    $_SESSION['battle_data'] = $battleData;
    
    header("Location: ../views/batalha/batalha.php"); // some daqui meu
    exit;
}

// QUANDO A BATALHA ACABA ELE VOLTA AQUI
if ($action === 'finalizar') {
    header('Content-Type: application/json; charset=utf-8');

    if (!$input) {
        http_response_code(400);
        echo json_encode([
            'success' => true, 
            'redirect' => '../selecao/selecao.php'
        ]);
        exit;
    }

    // Variaveis recebidas pos termino da batalha
    $winner = $input['winner'] ?? null; // 'user' ou 'enemy' pra ver quem ganhou
    $teamFinalHp = $input['teamFinalHp'] ?? []; // a vida final do time do usuario
    $itemsUsed = $input['itemsUsed'] ?? []; // quais itens foram usados
    $lastEnemyEspId = $input['lastEnemyEspId'] ?? null;
    $userActiveHp = $input['userActiveHp'] ?? 0; // vida do pokemon ativo do usuario
    $aliveUser = $input['aliveUser'] ?? 0; 

    if ($winner !== 'user' && $winner !== 'enemy') { // se der ruim pra setar quem ganhou
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

 
            // Pega o id_esp enviado pelo JS ou, como garantia, o do último inimigo salvo na sessão
            $ultimoInimigoSessao = end($_SESSION['battle_data']['enemyTeam']);
            $idEspFinal = (int)($lastEnemyEspId ?: ($ultimoInimigoSessao['id_esp'] ?? 0));

            $preco = 10; // Valor padrão de segurança caso não ache a espécie
            if ($idEspFinal > 0) {
                $esp = new Especie();
                $esp->setId($idEspFinal);
                $esp->load();
                $preco = $esp->getPreco() ?? 10;
            }

            $divisor = max(1, $userActiveHp / 2); 
            $recompensa = (ceil($preco / $divisor) + $aliveUser) * 10; // arredonda pra cima a divisão
            // Atualiza dinheiro
            $usr->setDinheiro($usr->getDinheiro() + $recompensa);

            // Só avança a fase se o jogador ainda não estiver na ultima
            if ($usr->getFase() < 3) {
                $usr->setFase($usr->getFase() + 1);
            }
            
            // Salva o usuário (dinheiro atualizado e, se for o caso, nova fase)
            $usr->updateBD();

     
            } else {
        // JOGADOR PERDEU/FUGIU (ruim)
        // Cura APENAS os pokémons que participaram da batalha
        $idsTime = array_map(fn($p) => (int)$p['id_pkm'], $_SESSION['battle_data']['userTeam']);

            if (!empty($idsTime)) {
                $placeholders = implode(',', array_fill(0, count($idsTime), '?'));
                $stmt = $pdo->prepare("
                    UPDATE pokemon 
                    SET vida_atual = (SELECT vida_max FROM especie WHERE id_esp = pokemon.id_esp), 
                        id_cond = NULL 
                    WHERE id_pkm IN ($placeholders)
                ");
                $stmt->execute($idsTime);
            }
}
        // Atualizar inventario do usuario
        foreach ($itemsUsed as $id_item => $quant) {
            $quant = (int) $quant;
            if ($quant <= 0) continue;

            $vent = new inventario();
            $vent->setItem((int) $id_item);
            $vent->setUsr($usr->getID());
            $vent->load();

            $restante = $vent->getQuant() - $quant;

            if ($restante <= 0) {
                $vent->delete();
            } else {
                $vent->setQuant($restante);
                $vent->update();
            }
        }

        // REGISTRAR O LOG
        $nome_usr = $usr->getNome();
        $nome_inim = $_SESSION['battle_data']['enemyData']['nome'];
        $ganhou = ($winner === 'user') ? 1 : 0;

        $stmt = $pdo->prepare("INSERT INTO log (nome_usr, nome_inim, ganhou_perdeu) VALUES (?, ?, ?)");
        $stmt->execute([$nome_usr, $nome_inim, $ganhou]);

        $pdo->commit(); // Confirma tudo no banco

        // mata todo o json da batalha
        unset($_SESSION['battle_data']);

        // Retorna sucesso para o JS redirecionar
        echo json_encode([
            'success' => true, 
            'redirect' => '../selecao/selecao.php'
        ]);
        exit;

    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage() . ' na linha ' . $e->getLine()]);
        exit;
    }

}

// Se chegar aqui sem ação definida volta pra seleção como se nada tivesse acontessido
header("Location: ../views/selecao/selecao.php");
exit;