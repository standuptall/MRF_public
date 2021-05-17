<?php
	require_once("Utils.php");
    $app = FantaApp::GetSingleTon();
	$tabella = Utils::GetTabellaMondo($app,"Posta");
	$where = $_POST["where"];
    include_once("FantaApp.php");
    include_once("Utils.php");
    include_once("../FantaApp.php");
    include_once("../Utils.php");
    $obj = $app->Sql()->Fetch($tabella,$where);
    echo json_encode($obj);
?>