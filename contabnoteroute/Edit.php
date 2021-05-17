<?php
    include_once("FantaApp.php");
    include_once("Utils.php");
    include_once("../FantaApp.php");
    include_once("../Utils.php");
    $app = FantaApp::GetSingleTon();
    $data = $_POST["data"];
    $descrizione = $_POST["descrizione"];
    $localita = $_POST["localita"];
    $importo = $_POST["importo"];
    $tipospesa = $_POST["tipospesa"];
    $dacontab = $_POST["dacontab"];
    $debitore = $_POST["debitore"];
    $tabella = "ContabNote";
    $id = $_POST["ID"];
    Utils::RequestAuthorization($app);
    $arr = array("ID"=>$id);
    if (isset($data))
    	$arr["data"] = $data;
    if (isset($descrizione))
    	$arr["descrizione"] = $descrizione;
    if (isset($localita))
    	$arr["localita"] = $localita;
    if (isset($importo))
    	$arr["importo"] = $importo;
    if (isset($tipospesa))
    	$arr["tipospesa"] = $tipospesa;
    if (isset($dacontab))
    	$arr["dacontab"] = $dacontab;
    if (isset($debitore))
    	$arr["debitore"] = $debitore;
    $obj = (object)$arr;
    var_dump($arr);
    if (isset($id))
    	$app->Sql()->EditRecord($tabella,$obj);
?>