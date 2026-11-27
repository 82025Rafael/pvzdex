<?php

class ItemEvo extends Item{

private ?int $id_esp = null;

public function getEsp()
{return $this->id_esp;}

public function setEsp(int $id_esp)
{$this->id_esp = $id_esp;}

public function loadEvo(): bool
{
    $pdo = connect();
    $sql = "select id_esp from evolucao where id_item = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $this->getId()
    ]);
    $idEsp = $stmt->fetchColumn();

    if ($idEsp === false) {
        return false;
    }

    $this->setEsp((int) $idEsp);
    return true;
}

public function evoluir(Pokemon $pokemon, Especie $especie): bool
{
    if (!$this->loadEvo()) {
        return false;
    }

    $especieRequerida = $this->getEsp();
    $proximaEspecie = (int) $especie->getEvo();

    if ($pokemon->getEsp() === $especieRequerida) {
        if ($this->getId() === 19) {
            $proximaEspecie -= 3;
        }
    } elseif ($proximaEspecie !== $especieRequerida) {
        return false;
    }

    $pokemon->setEsp($proximaEspecie);
    return true;
}



}

?>