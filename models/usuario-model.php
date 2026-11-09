<?php
require_once '../config.php'; // conectando com o banco


class usuario {

private string $nome_usr;
private string $senha;
private string $cor_favorita = '#00bfff';

private int $id_usr;

private int $dinheiro = 500;



// GETTERS 
public function getNome()
{return $this->nome_usr;}

public function getSenha()
{return $this->senha;}

public function getCor()
{return $this->cor_favorita;}

public function getDinheiro()
{return $this->dinheiro;}

public function getIDusr()
{return $this->id_usr;}

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


public function updateBD()
{
$pdo = connect();
    $sql = "update usuario set nome_usr = :n, senha = :s, cor_favorita = :c, dinheiro = :d where id_usr = :id"; 
    $stmt = $pdo->prepare($sql); // Substituindo os valores provisorios
    
    $stmt->execute([
    
            ':n' => $this->nome_usr, //EVITA SQL INJECT
            ':c' => $this->$cor_favorita,
            ':d' => $this->$dinheiro,
            ':id' => $this->$id_usr
         ]);

}



public function cadastroUsr(){ //FUNCAO DE CADASTRAR O USUARIO NO BANCO

    $pdo = connect();
    $sql = "insert into usuario (nome_usr, senha, cor_favorita) values (:n, :s, :c)"; 
    $stmt = $pdo->prepare($sql); // Substituindo os valores provisorios
    
    $stmt->execute([
    
            ':n' => $this->nome_usr, //EVITA SQL INJECT
            ':s' => $this->senha,
            ':c' => $this->cor_favorita
         ]);

         $this->$id_usr = $pdo->lastInsertId();

}

public function SeraQueExisteNome(){ // VERIFICAR SE O NOME DIGITADO JA EXISTE

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
}

public function load(){ // CARREGAR DADOS DO USUARIO FROM BANCO

$pdo = connect();
$sql = "select nome_usr, cor_favorita, dinheiro from usuario where id_usr = :id"; // RECUPERANDO DADOS A PARTIR DO ID
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id' => $this->id_usr
]);
$usuario = $stmt->fetch(); // ENFIA TUDO NUM VETOR

//var_dump($usuario);

$this->setNome($usuario['nome_usr']); // SETANDO OQ FOI RECUPERADO
$this->setCor($usuario['cor_favorita']);
$this->setDinheiro($usuario['dinheiro']);

}


}

?>