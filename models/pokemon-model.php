<?php
require_once '../config.php';
class pokemon {
    

   private string $nome;
   private int $vida_max;
   private int $vida_atual;
   private int $forca;
    // ATAQUES

    private ataque $atk1;
    private ataque $atk2;
    
    // QUEM É ELE
    
    private especie $esp;
    
    // PRA QUEM ELE EVOLUI
    
    private especie $evo; 

    private int $id_usr; // DONO DELE

    //   GETTERS E SETTERS
   public function getNome() {
        return $this->nome;  
    }
   public function getVidaAtual() {
         return $this->vida_atual;
      }
   public function getVidaMax() {
         return $this->vida_max;
      }
   public function  getAtk1() {
         return $this->atk1;
      }
   public function  getAtk2() {
         return $this->atk2;
      }

   public function getEsp() {
         return $this->esp;
      }
   public function getEvo() {
         return $this->evo;
      }

   public function getIdUsr()
   {return $this->id_usr;}

   public function setNome(string $nome) {
      $this->nome = $nome;
   }
   public function setVidaAtual(int $vida_atual) {
      $this->vida_atual = $vida_atual;
}

   public function setVidaMax(int $vida_max) {
      $this->vida_max = $vida_max;
   }

   public function setIdUsr(int $id_usr)
   { $this->id_usr = $id_usr;}

   public function setAtk1(ataque $atk1) {
      $this->atk1 = $atk1;
   }
   public function  setAtk2(ataque $atk2) {
      $this->atk2 = $atk2;
   }

   public function setEsp(especie $esp) {
      $this->esp = esp;
   }
   public function setEvo(especie $evo) {
      $this->evo = $evo;
   }

   public function insert() // INSERIR NO BANCO COM ID DO USUARIO
   {
   
   $pdo = connect();

   $sql = "INSERT into pokemon (id_usr, nome_pkm, id_esp, id_atk1,id_atk2) values (:id, :nome, :esp, :atk1, :atk2)";
   $stmt = $pdo->prepare($sql);
   $stmt->execute([
      ':id'    =>    $this->id_usr,
      ':nome'  =>    $this->nome_pkm,
      ':esp'   =>    $this->esp->getId(),

      ':atk1'  =>    $this->atk1->getId(),
      ':atk2'  =>    $this->atk2->getId(),
      
      ]);
      
   }


}