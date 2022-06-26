<?php
	$tabella = "Giocatori";
	$where = $_POST["where"];
    include_once("FantaApp.php");
    include_once("Utils.php");
    include_once("../FantaApp.php");
    include_once("../Utils.php");
    $app = FantaApp::GetSingleTon();
    $obj = $app->Sql()->Fetch($tabella,$where);
    echo json_encode($obj);
?>