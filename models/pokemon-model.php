<?php
require_once '../config.php';
class pokemon {
    //nome
    private string $nome;
    //vida
    private int $vida_restante;
    private int $vida_total;
    //ids dos ataques
    private ataque $atk1;
    private ataque $atk2;
    private ataque $atk3;
    private ataque $atk4;
    //id da especie
    private especie $id_esp;
    //id da evolução
    private especie $id_evo; 
   
   public function getNome() {
        return $this->nome;  
    }
   public function getVidaRestante() {
         return $this->vida_restante;
      }
   public function getVidaTotal() {
         return $this->vida_total;
      }
   public function  getAtk1() {
         return $this->atk1;
      }
   public function  getAtk2() {
         return $this->atk2;
      }
   public function getAtk3() {
         return $this->atk3;
      }
   public function getAtk4() {
         return $this->atk4;
      }
   public function getIdEsp() {
         return $this->id_esp;
      }
   public function getIdEvo() {
         return $this->id_evo;
      }
   public function setNome($nome) {
      $this->nome = $nome;
   }
   public function setVidaRestante($vida_restante) {
      $this->vida_restante = $vida_restante;
   }
   public function setVidaTotal($vida_total) {
      $this->vida_total = $vida_total;
   }
   public function setAtk1($atk1) {
      $this->atk1 = $atk1;
   }
   public function  setAtk2($atk2) {
      $this->atk2 = $atk2;
   }
   public function setAtk3($atk3) {
      $this->atk3 = $atk3;
   }
   public function setAtk4($atk4) {
      $this->atk4 = $atk4;
   }
   public function setIdEsp($id_esp) {
      $this->id_esp = $id_esp;
   }
   public function setIdEvo($id_evo) {
      $this->id_evo = $id_evo;
   }