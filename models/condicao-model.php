<?php
class condicao{
    private int $id_cond;
    private string $nome_cond;
    private string $efeito;

    public function getId(){
        return $this->id_cond;
        }
    public function getNome(){
        return $this->nome_cond;
    }
    public function getEfeito(){
        return $this->efeito;
    }

    public function setId($id_cond){
        $this->id_cond = $id_cond;
    }
    public function setNome($nome_cond){
        $this->nome_cond = $nome_cond;
    }
    public function setEfeito($efeito){
        $this->efeito = $efeito;
    }

}