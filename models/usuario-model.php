<?php
require_once __DIR__ . '/../config.php'; // conectando com o banco


class Usuario {

private string $nome_usr;
private string $senha;
private string $cor_favorita = '#00bfff';

private int $id_usr;

private int $dinheiro = 500;
private string $foto;



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

// SETTERS

public function setId(int $id_usr)
{$this->id_usr = $id_usr;}

public function setNome(string $nome_usr)
{$this->nome_usr = $nome_usr;}

public function setSenha(string $senha)
{$this->senha = $senha;}

public function setCor(string $cor_favorita)
{$this->cor_favorita = $cor_favorita;}

public function setDinheiro(string $dinheiro)
{$this->dinheiro = $dinheiro;}

public function setFoto(string $foto)
{$this->foto = $foto;}

public function updateBD() // ATUALIZAR NO BANCO DE DADOS
{
$pdo = connect();
    $sql = "update usuario set nome_usr = :n, senha = :s, cor_favorita = :c, dinheiro = :d, foto :f where id_usr = :id"; 
    $stmt = $pdo->prepare($sql); // Substituindo os valores provisorios
    
    $stmt->execute([
    
            ':n' => $this->nome_usr, //EVITA SQL INJECT
            ':c' => $this->cor_favorita,
            ':d' => $this->dinheiro,
            ':f'=>  $this->foto,
            ':id' => $this->id_usr
         ]);

} // ======================================================================================================

public function load(){ // CARREGAR DADOS DO USUARIO FROM BANCO

$pdo = connect();
$sql = "select nome_usr, cor_favorita, dinheiro, foto from usuario where id_usr = :id"; // RECUPERANDO DADOS A PARTIR DO ID
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id' => $this->id_usr
]);
$usuario = $stmt->fetch(); // ENFIA TUDO NUM VETOR

//var_dump($usuario);

$this->setNome($usuario['nome_usr']); // SETANDO OQ FOI RECUPERADO
$this->setCor($usuario['cor_favorita']);
$this->setDinheiro($usuario['dinheiro']);
$this->setFoto($usuario['foto']);

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

session_start(); // RETOMAR SESSAO

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


} // FIM METODO LOGIN  ==============================================================================
public function pegarMeusPokemons() {  // METODO PEGAR OS POKEMONS DO USUARIO COM ID TAL
    $pdo = connect();
    $sql = "SELECT pokemon.nome_pkm, especie.nome_esp, especie.foto,
                   pokemon.vida_atual AS vida_restante_pkm,
                   especie.vida_max AS vida_total_pkm
            FROM pokemon
            JOIN especie ON pokemon.id_esp = especie.id_esp
            WHERE pokemon.id_usr = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $this->id_usr
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}
public function pegarMeuInventario() { 
    $pdo = connect();
    $sql = "SELECT nome_item, quant_item FROM inventario 
    JOIN item ON inventario.id_item = item.id_item
    where id_usr = :id ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $this->id_usr
    ]);
    return $stmt->fetchAll();
    }
}
