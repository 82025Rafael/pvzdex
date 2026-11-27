<?php
require_once __DIR__ . '/../config.php'; // conectando com o banco


class Usuario {

private string $nome_usr;
private string $senha;
private string $cor_favorita = '#00bfff';

private int $id_usr;

private int $dinheiro = 1000;
private string $foto;

private int $fase;



// GETTERS 
public function getNome()
{return $this->nome_usr;}

public function getSenha()
{return $this->senha;}

public function getCor()
{return $this->cor_favorita;}

public function getDinheiro()
{return $this->dinheiro;}

public function getID()
{return $this->id_usr;}

public function getFoto()
{return $this->foto;}

public function getFase()
{return $this->fase;}

// SETTERS

public function setId(int $id_usr)
{$this->id_usr = $id_usr;}

public function setNome(string $nome_usr)
{$this->nome_usr = $nome_usr;}

public function setSenha(string $senha)
{$this->senha = $senha;}

public function setCor(string $cor_favorita)
{$this->cor_favorita = $cor_favorita;}

public function setDinheiro(int $dinheiro)
{$this->dinheiro = $dinheiro;}

public function setFoto(string $foto)
{$this->foto = $foto;}


public function setFase(int $fase)
{$this->fase = $fase;}

public function updateBD() // ATUALIZAR NO BANCO DE DADOS
{
$pdo = connect();
    $sql = "update usuario set nome_usr = :n, senha = :s, cor_favorita = :c, dinheiro = :d, foto = :f, fase = :fase where id_usr = :id"; 
    $stmt = $pdo->prepare($sql); // Substituindo os valores provisorios
    
    $stmt->execute([
    
            ':n' => $this->nome_usr, //EVITA SQL INJECT
            ':s' => $this->senha,
            ':c' => $this->cor_favorita,
            ':d' => $this->dinheiro,
            ':f'=>  $this->foto,
            ':fase' => $this->fase,
            ':id' => $this->id_usr
         ]);

} // ======================================================================================================

public function load(){ // CARREGAR DADOS DO USUARIO FROM BANCO

$pdo = connect();
$sql = "select nome_usr, senha, cor_favorita, dinheiro, foto, fase from usuario where id_usr = :id"; // RECUPERANDO DADOS A PARTIR DO ID
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id' => $this->id_usr
]);
$usuario = $stmt->fetch(); // ENFIA TUDO NUM VETOR

//var_dump($usuario);

$this->setNome($usuario['nome_usr']); // SETANDO OQ FOI RECUPERADO
$this->setSenha($usuario['senha']);
$this->setCor($usuario['cor_favorita']);
$this->setDinheiro($usuario['dinheiro']);
$this->setFoto($usuario['foto']);
$this->setFase($usuario['fase']);

} // ======================================================================================================



public function cadastroUsr(){ //FUNCAO DE CADASTRAR O USUARIO NO BANCO

    $pdo = connect();
    $sql = "insert into usuario (nome_usr, senha, cor_favorita, foto) values (:n, :s, :c, :f)"; 
    $stmt = $pdo->prepare($sql); // Substituindo os valores provisorios
    
    $stmt->execute([
    
            ':n' => $this->nome_usr, //EVITA SQL INJECT
            ':s' => $this->senha,
            ':c' => $this->cor_favorita,
            ':f' => $this->foto
         ]);

         $this->id_usr = $pdo->lastInsertId();

} // FIM CADASTRO ===========================================================================

public function SeraQueExisteNome(){ // METODO VERIFICAR SE O NOME DIGITADO JA EXISTE

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

try{
    
$pdo = connect();
$sql = "select nome_usr from usuario where nome_usr = :n"; // VERIFICANDO SE USUARIO JA EXISTE 
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':n' => $this->nome_usr
]);
$teste = $stmt->fetch(); // CATANDO

if(!empty($teste))
    {throw new Exception("Nome de Usuário já existente!");} // JA EXISTE POXA

}
catch(Exception $e){

    $_SESSION['erroCadastro'] = $e->getMessage(); // VOLTANDO A EXCESSAO
    return true;
    
}

unset($_SESSION['erroCadastro']); // DESCARTA A EXCESSAO

return false;
} // FIM METODO VERIFICADOR ===========================================================================

public function login($nome_usr, $senha){

    $pdo =  connect();
    session_start();

 // SELECIONAR ID A PARTIR DE NOME E SENHA E VER SE ESTÁ CERTO
$sql = "SELECT id_usr from usuario where nome_usr = :n and senha = :s"; // SQL SELECT tudo do usuario
$stmt = $pdo->prepare($sql); // prepara e executa o SQL
$stmt->execute([

    ':n' => $nome_usr, //pra evitar o SQL inject 
    ':s' => $senha
]);

$usr = $stmt->fetch(); 
return $usr['id_usr'];


}public function pegarMeusPokemons() {  // METODO PEGAR OS POKEMONS DO USUARIO COM ID TAL
    $pdo = connect();
    $sql = "SELECT pokemon.id_pkm, pokemon.id_esp, pokemon.nome_pkm, especie.nome_esp, especie.foto, tipo.nome_tipo,
                   pokemon.vida_atual AS vida_restante_pkm,
                   especie.vida_max AS vida_total_pkm,
                   a1.nome_atk AS atk1,
                   a2.nome_atk AS atk2
            FROM pokemon
            JOIN especie ON pokemon.id_esp = especie.id_esp
            JOIN tipo ON especie.id_tipo = tipo.id_tipo
            JOIN ataque a1 ON especie.atk1 = a1.id_atk
            LEFT JOIN ataque a2 ON especie.atk2 = a2.id_atk 
            WHERE pokemon.id_usr = :id";
            // left join garante que as especies sem um segundo ataque não deem problema
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $this->id_usr
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function pegarMeuInventario() {  // METODO PEGAR OS ITENS DO INVENTARIO DO USUARIO
    $pdo = connect();
    $sql = "SELECT i.id_item, i.nome_item, i.desc_item, i.foto, i.preco_item,
                   inv.quant_item
            FROM inventario inv
            JOIN item i ON i.id_item = inv.id_item
            WHERE inv.id_usr = :id
              AND inv.quant_item > 0
            ORDER BY i.nome_item";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $this->id_usr]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function pegarMeusLogs(int $limite = 300) {  // METODO PARA A PAGINA DE LOG
    $pdo = connect();
    $sql = "SELECT id_log, nome_usr, nome_inim, data_log,
                   ganhou_perdeu, tipo, detalhe
            FROM log
            WHERE nome_usr = :nome
            ORDER BY data_log DESC
            LIMIT :lim";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':nome', $this->nome_usr);
    $stmt->bindValue(':lim', $limite, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

} // ← ESSA chave é a que estava faltando! Fecha a classe Usuario