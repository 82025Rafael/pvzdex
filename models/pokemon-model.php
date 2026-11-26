<?php
require_once __DIR__ . '/../config.php';
class Pokemon {
    
   private int $id_pkm;
   private string $nome_pkm;
   private int $vida_atual;
    
   private int $id_esp;
   private int $id_usr; // DONO DELE
   private $id_cond;

    // GETTERS
   public function getNome() {
        return $this->nome_pkm;  
    }
   public function getVida() {
         return $this->vida_atual;
      }


   public function getEsp() {
         return $this->id_esp;
      }

   public function getUsr()
   {return $this->id_usr;}

      public function getId()
   {return $this->id_pkm;}

   public function getCond()
   {return $this->id_cond;}

   // SETTERS

   public function setNome(string $nome_pkm) {
      $this->nome_pkm = $nome_pkm;
   }
   public function setVida(int $vida_atual) {
      $this->vida_atual = $vida_atual;
}

   public function setUsr(int $id_usr)
   { $this->id_usr = $id_usr;}

   public function setEsp(int $id_esp) {
      $this->id_esp = $id_esp;
   }


    public function setId(int $id_pkm) {
      $this->id_pkm = $id_pkm;
   }

   public function setCond($id_cond)
   {$this->id_cond = $id_cond;}

   public function insert() // INSERIR NO BANCO COM ID DO USUARIO
   {
   
   $pdo = connect();

   $sql = "INSERT into pokemon (id_usr, nome_pkm, id_esp, vida_atual) values (:id, :nome, :esp, :vat)";
   $stmt = $pdo->prepare($sql);
   $stmt->execute([
      ':id'    =>    $this->id_usr,
      ':nome'  =>    $this->nome_pkm,
      ':esp'   =>    $this->id_esp,
      ':vat'   =>    $this->vida_atual
      
      ]);
      
   } // FIM METODO INSERT =======================================================================================

   public function load(){ // CARREGAR DADOS DO POKEMON FROM BANCO

    $pdo = connect();
    $sql = "select nome_pkm, vida_atual, id_esp, id_cond from pokemon where id_pkm = :id"; // RECUPERANDO DADOS A PARTIR DO ID
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $this->id_pkm
    ]);

    $pkm = $stmt->fetch(); // ENFIA TUDO NUM VETOR

    //var_dump($pkm);
    
   $this->setNome($pkm['nome_pkm']); // SETANDO OQ FOI RECUPERADO
   $this->setVida($pkm['vida_atual']);
   $this->setEsp($pkm['id_esp']);
   $this->setCond($pkm['id_cond']);



    } // ======================================================================================================

public function update(){ // CARREGAR DADOS DO POKEMON FROM BANCO

    $pdo = connect();
    $sql = "update pokemon set nome_pkm = :n, vida_atual = :v, id_esp = :e, id_cond = :c where id_pkm = :id"; // RECUPERANDO DADOS A PARTIR DO ID
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
         ':n' => $this->nome_pkm,
         ':v'  => $this->vida_atual,
         ':e' => $this->id_esp,
         ':c' => $this->id_cond,
        ':id' => $this->id_pkm
    ]);


    
    } // ======================================================================================================




}