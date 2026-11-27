<?php
session_start();
require_once '../config.php';

if (empty($_SESSION['logado']) || empty($_SESSION['id_usr'])) {
    header('Location: ../views/login.php');
    exit;
}

$redirecionarComMensagem = static function (string $mensagem): void {
    $_SESSION['msg_item'] = $mensagem;
    header('Location: ../views/selecao/selecao.php');
    exit;
};

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $redirecionarComMensagem('Selecione um item e um monstrinho para usar.');
}

$idItem = filter_input(INPUT_POST, 'id_item', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
$idPkm = filter_input(INPUT_POST, 'id_pkm', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

if ($idItem === false || $idItem === null || $idPkm === false || $idPkm === null) {
    $redirecionarComMensagem('Item ou monstrinho inválido.');
}

$stmt = $pdo->prepare('SELECT id_item FROM item WHERE id_item = :id');
$stmt->execute([':id' => $idItem]);
if (!$stmt->fetchColumn()) {
    $redirecionarComMensagem('Esse item não existe.');
}

$stmt = $pdo->prepare('SELECT id_pkm FROM pokemon WHERE id_pkm = :id AND id_usr = :usr');
$stmt->execute([
    ':id' => $idPkm,
    ':usr' => (int) $_SESSION['id_usr'],
]);
if (!$stmt->fetchColumn()) {
    $redirecionarComMensagem('Esse monstrinho não pertence à sua equipe.');
}

$item = new Item();
$item->setId($idItem);
$item->load();

$vent = new Inventario();
$vent->setItem($idItem);
$vent->setUsr((int) $_SESSION['id_usr']);
$vent->load();
if ($vent->getQuant() < 1) {
    $redirecionarComMensagem('Você não possui esse item no inventário.');
}

$pkm = new Pokemon();
$pkm->setId($idPkm);
$pkm->load();

$esp = new Especie();
$esp->setId($pkm->getEsp());
$esp->load();

$vidaAnterior = $pkm->getVida();
$condicaoAnterior = $pkm->getCond();
$especieAnterior = $pkm->getEsp();

switch($item->getId()){

        case 1:
        if($pkm->getVida() > 0)
            {$pkm->setVida($pkm->getVida() + 6);
            if($pkm->getVida() > $esp->getVida()) {$pkm->setVida($esp->getVida()); }
            }
        break;
        
        case 2:
        if($pkm->getVida() > 0){        
            $pkm->setVida($pkm->getVida() + 12);
            if($pkm->getVida() > $esp->getVida()) {$pkm->setVida($esp->getVida());}
        }
        break;
        case 3:  
        if($pkm->getVida() > 0){
            $pkm->setVida($pkm->getVida() + 45);
            if($pkm->getVida() > $esp->getVida()) {$pkm->setVida($esp->getVida()); }
        }
        break;
        case 4:       
        if($pkm->getVida() > 0)
        {
            $pkm->setVida($esp->getVida());$vent->setQuant(0);
        }
        break;
        case 5:
            if($pkm->getCond() == 1) {$pkm->setCond(null); }
        break;
        case 6:
            if($pkm->getCond() == 5) {$pkm->setCond(null); }
        break;
        case 7:
            if($pkm->getCond() == 2) {$pkm->setCond(null); }
        break;
        case 8:
            if($pkm->getCond() == 3) {$pkm->setCond(null); }
        break;
        case 9:
            if($pkm->getCond() == 4) {$pkm->setCond(null); }
        break;
        case 10:
            $pkm->setCond(null);
        break;
        case 11:

        if($pkm->getVida() > 0)
        {$pkm->setVida($esp->getVida());}
        $pkm->setCond(null);
    
        break;
        case 12:
        if($pkm->getVida() == 0)
        {
            $pkm->setVida($esp->getVida()/2);
        }
        break;
        case 13:
            if($pkm->getEsp() == 11)
                {$pkm->setEsp($esp->getEvo());}
        break;
        case 14:
            if($pkm->getEsp() == 18)
                {$pkm->setEsp($esp->getEvo());}
        break;
        case 15:
            if($pkm->getEsp() == 9)
                {$pkm->setEsp($esp->getEvo());}
        break;
        
        case 16:
            if($pkm->getEsp() == 17)
                {$pkm->setEsp($esp->getEvo());}
        break;
        
        case 17:
            if($pkm->getEsp() == 21)
                {$pkm->setEsp($esp->getEvo());}
        break;
        
        case 18:
            if($pkm->getEsp() == 8)
                {$pkm->setEsp($esp->getEvo());}
        break;
        case 19:
            if($pkm->getEsp() == 47)
                {$pkm->setEsp($esp->getEvo()-3);}
        break;
        case 20:
            if($pkm->getEsp() == 47)
                {$pkm->setEsp($esp->getEvo());}
        break;
        case 21:
            if($pkm->getEsp() == 32)
                {$pkm->setEsp($esp->getEvo());}
        break;
        case 22:
            if($pkm->getEsp() == 38)
                {$pkm->setEsp($esp->getEvo());}
        break;
        case 23:
            if($pkm->getEsp() == 35)
                {$pkm->setEsp($esp->getEvo());}
        break;
        case 24:
            if($pkm->getEsp() == 41)
                {$pkm->setEsp($esp->getEvo());}
        break;
        case 25:
            if($pkm->getEsp() == 26)
                {$pkm->setEsp($esp->getEvo());}
        break;




}

if (
    $pkm->getVida() === $vidaAnterior
    && $pkm->getCond() === $condicaoAnterior
    && $pkm->getEsp() === $especieAnterior
) {
    $redirecionarComMensagem('Esse item não teve efeito neste monstrinho e não foi consumido.');
}

$vent->setQuant($vent->getQuant()-1);

if ($vent->getQuant() <= 0) {
    $vent->delete();
} else {
    $vent->update();
}

$pkm->update();

$redirecionarComMensagem('Item ' . $item->getNome() . ' usado em ' . $pkm->getNome() . '.');


?>