<?php
class Especie{
    private string $nome_esp;
    private int $id_esp;

    private int $id_tipo;
    private int $id_evo;

    private int $vida_max;
    private int $forca;
    private int $veloc;

    private string $foto;


    // GETTERS

    public function getNome() {
        return $this->nome_esp;
    }
    public function getId() {
        return $this->id_esp;
    }

    public function getEvo() {
        return $this->id_evo;
    }

    public function getTipo() {
        return $this->$id_tipo;
    }

    public function getVida() {
        return $this->vida_max;
    }
    public function getForca() {
        return $this->forca;
    }
    public function getVeloc() {
        return $this->veloc;
    }

    public function getFoto(){
        return $this->foto;
    }
    

    // SETTERS

    public function setNome(string $nome_esp) {
        $this->nome_esp = $nome_esp;
    }
    public function setId(int $id_esp) {
        $this->id_esp = $id_esp;
    }

    public function setEvo(int $id_evo) {
        $this->id_evo = $id_evo;
    }

    public function setTipo(int $id_tipo) {
        $this->id_tipo = $id_tipo;
    }

    public function setVida(int $vida_max) {
        $this->vida_max = $vida_max;
    }
    public function setForca(int $forca) {
        $this->forca = $forca;
    }
    public function setVeloc(int $veloc) {
        $this->veloc = $veloc;
    }

    public function setFoto(string $foto) {
        $this->foto = $foto;
    }




     public function load(){ // CARREGAR DADOS DA ESPECIE FROM BANCO

    $pdo = connect();
    $sql = "select nome_esp, id_tipo, id_evo, vida_max, forca, veloc, foto from especie where id_esp = :id"; // RECUPERANDO DADOS A PARTIR DO ID
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $this->id_esp
    ]);
    $esp = $stmt->fetch(); // ENFIA TUDO NUM VETOR

    //var_dump($esp);
    
    $this->setNome($esp['nome_esp']); // SETANDO OQ FOI RECUPERADO
    $this->setTipo($esp['id_tipo']); 
    $this->setEvo($esp['id_evo']);
    $this->setVida($esp['vida_max']); 
    $this->setForca($esp['forca']); 
    $this->setVeloc($esp['veloc']);
    $this->setFoto($esp['foto']);


    } // ======================================================================================================
}