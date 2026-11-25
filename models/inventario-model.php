<?php

    class Inventario{


    private int $id_item;
    private int $id_usr;
    private int $quant_item = 0;

    // GETTERS

    public function getItem()
    {return $this->id_item;}

    public function getUsr()
    {return $this->id_usr;}

    public function getQuant()
    {return $this->quant_item;}

    // SETTERS

    public function setItem(int $id_item)
    {$this->id_item = $id_item;}
    
    public function setUsr(int $id_usr)
    {$this->id_usr = $id_usr;}

    public function setQuant(int $quant_item)
    {$this->quant_item = $quant_item;}


    public function insert() // INSERIR NO BANCO COM ID DO USUARIO
   {
   
   $pdo = connect();

   $sql = "INSERT into inventario (id_item, id_usr, quant_item) values (:item, :usr, :quant) on duplicate key update quant_item = quant_item + values(quant_item);
";
   $stmt = $pdo->prepare($sql);
   $stmt->execute([
      ':item'    =>    $this->id_item,
      ':usr'     =>    $this->id_usr,
      ':quant'   =>    $this->quant_item  
   
      
      ]);
      
   } // FIM METODO INSERT =======================================================================================


 public function load(){ // CARREGAR DADOS DO INVENTARIO FROM BANCO

    $pdo = connect();
    $sql = "select id_usr, id_item, quant_item from inventario where id_item = :item and id_usr = :usr" ; // RECUPERANDO DADOS A PARTIR DO ID
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':item' => $this->id_item,
        ':usr'  => $this->id_usr
    ]);
    $item = $stmt->fetch(); // ENFIA TUDO NUM VETOR

    //var_dump($item);
    
    if($item)
        {
            $this->setItem($item['id_item']); // SETANDO OQ FOI RECUPERADO
            $this->setQuant($item['quant_item']); 
            $this->setUsr($item['id_usr']);
        }
    else{
        return false;
    }



    } // ======================================================================================================



    public function update(){ // ATUALIZA

    $pdo = connect();
    $sql = "update inventario set quant_item = :quant where id_item = :item and id_usr = :usr";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([

        ':quant'   =>      ($this->quant_item),
        ':item'     =>      $this->id_item,
        ':usr'      =>      $this->id_usr

    ]);
    } // FIM DO LOAD ===============================================================================================



public function delete(){ // DELETA OS ITENS 

    $pdo = connect();
    $sql = "delete from inventario where id_item = :item and id_usr = :usr";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':item'     =>      $this->id_item,
        ':usr'      =>      $this->id_usr
    ]);


}

    }

?>