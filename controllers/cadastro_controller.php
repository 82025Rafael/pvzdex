<?php

require_once '../config.php'; // conectando com o banco
session_start();

/**
 * Busca uma palavra aleatória na API do Dicionário Aberto.
 * Tenta até 2 vezes antes de desistir. Timeout de 2s por tentativa.
 * Retorna null se todas falharem.
 */
function gerarPalavraAleatoria(): ?string {
    for ($i = 0; $i < 2; $i++) {
        $contexto = stream_context_create([
            'http' => [
                'timeout' => 2,
                'method'  => 'GET',
            ],
        ]);

        $resposta = @file_get_contents('https://api.dicionario-aberto.net/random', false, $contexto);
        if ($resposta === false) {
            continue;
        }

        $dados = json_decode($resposta, true);
        if (!is_array($dados) || empty($dados['word'])) {
            continue;
        }

        $palavra = trim($dados['word']);
        if ($palavra !== '') {
            return ucfirst($palavra);
        }
    }
    return null;
}

    $nome_usr = htmlspecialchars($_POST['nome_usr']);   
    $senha = htmlspecialchars($_POST['senha']);
    $cor_favorita = htmlspecialchars($_POST['cor']);

    $usuario = new usuario(); // CRIANDO CLASSE DO USUARIO
    $usuario->setNome($nome_usr);

    if($usuario->SeraQueExisteNome()) // ESSE USUARIO JA EXISTE COM ESSE NOME????
        {header('location:../views/cadastro.php');
        exit;}


 $destino = "../uploads/"; // PRA ONDE AS FOTOS VAO SER UPADAS
    if (!is_dir($destino)) // SERA QUE EXISTE O DESTINO?
        {mkdir($destino, 0777, true);} // CRIA

    if(isset($_FILES["arquivo"])){ // FEZ UPLOAD?
    $arquivo = $_FILES["arquivo"];

    $arquivo;
     ; // AONDE ELE ESTARÁ NO FIM
    $extensao = strtolower(pathinfo($arquivo["name"], PATHINFO_EXTENSION)); // 

    $NomeArquivo = uniqid() . "." . $extensao;
    $caminho = $destino . $NomeArquivo;

    $tamMax = 4 * 1024 * 1024; // 4 MB

    try{
        if($arquivo["size"] > $tamMax)
        throw new Exception("Arquivo Muito Grande!"); // SE O ARQUIVO FOR MAIOR QUE 4 MB
    }
    catch(Exception $e)
    {
        $_SESSION['erroCadastro'] = $e->getMessage();
        header("location:../views/cadastro.php");
        exit;
    }
    
    $extensoesPermitidas = ["png", "jpg", "jpeg", "gif"]; // QUAIS EXTENSOES PODEM SER UPADAS

    try{

    if(in_array($extensao, $extensoesPermitidas))
        {
          move_uploaded_file($arquivo['tmp_name'], $caminho); // MOVE O ARQUIVO PRO DESTINO

        }
    else{
        throw new Exception("Tipo de arquivo inválido");
       
    }    
    }
    catch(Exception $e)
    {
        $_SESSION['erroCadastro'] = $e->getMessage();
        header("location:../views/cadastro.php");
         exit;
    }
     // VOLTANDO A EXCESSAO
   
    
    }

    $usuario->setFoto($caminho);



try{

    $usuario->setSenha($senha);
    $usuario->setCor($cor_favorita);
    $usuario->cadastroUsr();

// CRIAÇÃO DOS POKEMONS INICIAIS -------------------

$id_usr = $usuario->getID(); // PEGANDO O ID RECEM INSERIDO

// Sorteia 3 espécies diferentes (IDs de 1 a 50)
$pool = range(1, 50);
shuffle($pool);
$idsEspecies = array_slice($pool, 0, 3);

foreach ($idsEspecies as $idEsp) {
    $especie = new Especie();
    $especie->setId($idEsp);
    $especie->load();

    // Gera nome aleatório via API; se falhar, usa o nome da espécie
    $nomePokemon = gerarPalavraAleatoria();
    if ($nomePokemon === null) {
        $nomePokemon = $especie->getNome(); // fallback seguro
    }

    $pokemon = new Pokemon();
    $pokemon->setUsr($id_usr);
    $pokemon->setEsp($especie->getId());
    $pokemon->setNome($nomePokemon);
    $pokemon->setVida($especie->getVida());
    $pokemon->insert();
}

// Inserindo entrada no log
$mensagem = "Usuário cadastrado: {$usuario->getNome()} ";
$arquivoLog = "../logs/registro.log";
date_default_timezone_set('America/Sao_Paulo');
$dataHora = date("Y-m-d H:i:s");
$entrada = "[$dataHora] = $mensagem" . PHP_EOL;
file_put_contents($arquivoLog, $entrada, FILE_APPEND);


header('Location: ../views/index.php'); // volta pro index
} catch(PDOException $e) {
    echo "Erro ao cadastrar usuário: " . $e->getMessage();
}
?>