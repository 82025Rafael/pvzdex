<?php
require_once '../config.php';
class pokemon {
    
   private int $id_pkm
   private string $nome_pkm;
   private int $vida_atual;
   private int $atk1;
   private int $atk2;
    
   private int $id_esp;
   private int $id_usr; // DONO DELE

    // GETTERS
   public function getNome() {
        return $this->nome_pkm;  
    }
   public function getVida() {
         return $this->vida_atual;
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

   public function getUsr()
   {return $this->id_usr;}

   // SETTERS

   public function setNome(string $nome_pkm) {
      $this->nome_pkm = $nome_pkm;
   }
   public function setVida(int $vida_atual) {
      $this->vida_atual = $vida_atual;
}

   public function setUsr(int $id_usr)
   { $this->id_usr = $id_usr;}

   public function setAtk1(int $id_atk1) {
      $this->id_atk1 = $id_atk1;
   }
   public function  setAtk2(int $id_atk2) {
      $this->id_atk2 = $id_atk2;
   }

   public function setEsp(int $id_esp) {
      $this->id_esp = $id_esp;
   }
   public function setEvo(int $id_evo) {
      $this->id_evo = $id_evo;
   }

   public function insert() // INSERIR NO BANCO COM ID DO USUARIO
   {
   
   $pdo = connect();

   $sql = "INSERT into pokemon (id_usr, nome_pkm, id_esp, id_atk1, id_atk2) values (:id, :nome, :esp, :atk1, :atk2)";
   $stmt = $pdo->prepare($sql);
   $stmt->execute([
      ':id'    =>    $this->id_usr,
      ':nome'  =>    $this->nome_pkm,
      ':esp'   =>    $this->esp->getId(),

      ':atk1'  =>    $this->atk1->getId(),
      ':atk2'  =>    $this->atk2->getId()
      
      ]);
      
   }

   public function load(){ // CARREGAR DADOS DO POKEMON FROM BANCO

    $pdo = connect();
    $sql = "select nome_pkm, vida_atual, id_esp, id_evo, id_atk1, id_atk2 where id_pkm = :id"; // RECUPERANDO DADOS A PARTIR DO ID
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $this->id_tipo
    ]);

    $pkm = $stmt->fetch(); // ENFIA TUDO NUM VETOR

    //var_dump($pkm);
    
   $this->setNome($pkm['nome_pkm']); // SETANDO OQ FOI RECUPERADO
   $this->setVida($pkm['vida_atual']);
   $this->setEsp($pkm['id_esp']);
   $this->setEvo($pkm['id_evo']);
   $this->setAtk1($pkm['id_atk1']);
   $this->setAtk2($pkm['id_atk2']);


    } // ======================================================================================================

}