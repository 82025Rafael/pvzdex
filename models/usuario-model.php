<?php
class usuario {
    private $db;
    public function __construct($db) {
        $this->db = $db;

    function cadastro ($nome, $senha, $cor_favorita) {
    $sql = "INSERT INTO usuario (nome, senha, cor_favorita) VALUES (:n, :s, :c)"; 
    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        ':n' => "$nome;",
        ':s' => "$senha;",
        ':c' => "$cor_favorita;"
    ]);

    function get_list(){
        foreach( $con->query("SELECT * FROM usuario") as $row) {
            lista[] = {
                echo $row['nome'] . => echo $row['senha'], echo $row['cor_favorita'];
            }
             return lista;
        }
    }
        
    }