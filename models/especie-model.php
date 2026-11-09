<?php
class especie{
    private string $nome;
    private int $id;
    private int $vida;
    private int $forca;
    private int $veloc;

    public function getNome() {
        return $this->nome;
    }
    public function getId() {
        return $this->id;
    }
    public function getVida() {
        return $this->vida;
    }
    public function getForca() {
        return $this->forca;
    }
    public function getVeloc() {
        return $this->veloc;
    }
    
    public function setNome($nome) {
        $this->nome = $nome;
    }
    public function setId($id) {
        $this->id = $id;
    }
    public function setVida($vida) {
        $this->vida = $vida;
    }
    public function setForca($forca) {
        $this->forca = $forca;
    }
    public function setVeloc($veloc) {
        $this->veloc = $veloc;
    }
}