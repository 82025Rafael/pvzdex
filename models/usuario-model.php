<?php
require_once '../config.php'; // conectando com o banco


class usuario {

private string $nome_usr;
private string $senha;
private string $cor_favorita;

private int $id_usr;

private int $dinheiro = 500;



// GETTERS 
public function getNome()
{
    return $this->nome_usr;
}
public function getSenha()
{
    return $this->senha;
}

public function getCorfav()
{
    return $this->cor_favorita;
}

public function getDinheiro()
{
    return $this->dinheiro;
}

public function getIDusr()
{
    return $this->id_usr;
}

// SETTERS

public function setNome(string $nome_usr)
{$this->nome_usr = $nome_usr;}

public function setSenha(string $senha)
{$this->senha = $senha;}

public function setCorfav(string $cor_favorita)
{$this->cor_favorita = $cor_favorita;}

public function setDinheiro(string $dinheiro)
{$this->dinheiro = $dinheiro;}

//FUNCAO DE CADASTRAR O USUARIO NO BANCO
public function cadastroUsr(){

    $pdo = connect();
    $sql = "insert into usuario (nome_usr, senha, cor_favorita) values (:n, :s, :c)"; 
    $stmt = $pdo->prepare($sql); // Substituindo os valores provisorios
    
    $stmt->execute([
    
            ':n' => $this->nome_usr, //EVITA SQL INJECT
            ':s' => $this->senha,
            ':c' => $this->cor_favorita
         ]);

}
}

// fazer funcoes UPDATE DO BANCO, DELETE DO BANCO E VERIFICAR NOME EXISTENTE
?>