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

if ($idItem === 12) {
    if ($pkm->getVida() === 0) {
        $pkm->setVida(max(1, (int) ($esp->getVida() / 2)));
    }
} else {
    $itemCura = new ItemCura();
    $itemCura->setId($idItem);

    if ($itemCura->loadCura()) {
        $quantCura = $itemCura->getCura();

        if ($quantCura !== null && $pkm->getVida() > 0) {
            $vidaRestaurada = min($pkm->getVida() + $quantCura, $esp->getVida());
            $pkm->setVida($vidaRestaurada);
        }

        if (
            $pkm->getCond() !== null
            && in_array((int) $pkm->getCond(), $itemCura->getConds(), true)
        ) {
            $pkm->setCond(null);
        }
    } else {
        $itemEvo = new ItemEvo();
        $itemEvo->setId($idItem);
        $itemEvo->evoluir($pkm, $esp);
    }
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