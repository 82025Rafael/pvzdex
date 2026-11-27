<?php

class ItemCura extends Item{

private ?int $quant_cura = null;
private ?int $id_cond = null;
private array $id_conds = [];

public function getCura()
{return $this->quant_cura;}

public function getCond()
{return $this->id_cond;}

public function getConds(): array
{return $this->id_conds;}

public function setCura(?int $quant_cura)
{$this->quant_cura = $quant_cura;}


public function setCond(?int $id_cond)
{$this->id_cond = $id_cond;}


public function loadCura(): bool{

$pdo = connect();
$sql = "select quant_cura, id_cond from cura where id_item = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':id' => $this->getId()
]);
$curas = $stmt->fetchAll(PDO::FETCH_ASSOC);

$this->quant_cura = null;
$this->id_cond = null;
$this->id_conds = [];

foreach ($curas as $cura) {
    if ($this->quant_cura === null && $cura['quant_cura'] !== null) {
        $this->setCura((int) $cura['quant_cura']);
    }

    if ($cura['id_cond'] !== null) {
        $idCond = (int) $cura['id_cond'];
        if (!in_array($idCond, $this->id_conds, true)) {
            $this->id_conds[] = $idCond;
        }
    }
}

$this->setCond($this->id_conds[0] ?? null);

return count($curas) > 0;

}





}


?>