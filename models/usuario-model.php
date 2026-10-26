<?php
requiure_once '../config.php';
class usuario {
    private $db;
    //Constructor para inicializar a conexão com o banco de dados
    public function __construct($db) {
        $this->db = $db;
    }
    //Função para cadastrar um usuário
    function cadastro ($nome, $senha, $cor_favorita) {
    $sql = "INSERT INTO usuario (nome_usr, senha, cor_favorita) VALUES (:n, :s, :c)"; 
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':n' => "$nome;",
        ':s' => "$senha;",
        ':c' => "$cor_favorita;"
    ]);
    }
    //Função para pegar um usuário específico
        function get_usr($nome, $senha){
        $sql = "SELECT nome_usr, senha FROM usuario WHERE nome_usr = :n AND senha = :s";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':n' => $nome,
            ':s' => $senha
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }