<?php
    include_once("FantaApp.php");
    include_once("Utils.php");
    include_once("../FantaApp.php");
    include_once("../Utils.php");
    $app = FantaApp::GetSingleTon();
    $tabella = "ProfiliAggiuntiva";
    Utils::RequestAuthorization($app);
    $where = "ID = ".$_POST["ID"];
    $obj = $app->Sql()->Fetch($tabella,$where);
    $newobj = array("ID"=>$_POST["ID"]);
    $newobjuser = array("ID"=>$_POST["ID"]);
    if (isset($_POST["nome"]))
    	$newobj["nome"] = $_POST["nome"];
    if (isset($_POST["cognome"]))
    	$newobj["cognome"] = $_POST["cognome"];
    if (isset($_POST["localita"]))
    	$newobj["localita"] = $_POST["localita"];
    if (isset($_POST["telefono"]))
    	$newobj["telefono"] = $_POST["telefono"];
    if (isset($_POST["immagine"]))
    	$newobj["immagine"] = $_POST["immagine"];
    if (isset($_POST["idmondo"]))
    	$newobj["idmondo"] = $_POST["idmondo"];
    if (isset($_POST["idsquadra"]))
    	$newobj["idsquadra"] = $_POST["idsquadra"];
    if (isset($_POST["cdlingua"]))
    	$newobjuser["cdlingua"] = $_POST["cdlingua"];
    if (isset($_POST["cdnazione"]))
    	$newobj["cdnazione"] = $_POST["cdnazione"];
    if (count($obj)>0){
        $app->Sql()->EditRecord($tabella,(object)$newobj);
    }
    else
    {
      $app->Sql()->AddRecord($tabella,$newobj);
    }
    $app->Sql()->EditRecord("Profili",(object)$newobjuser);
    echo json_encode($newobj);
    header('Content-Type: application/json');
?>