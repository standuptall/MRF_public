<?php
	require_once("Utils.php");
	require_once("FantaApp.php");
    $app = FantaApp::GetSingleTon();
    $app->TokenLogin();
    if (isset($app->userInfo)){
    	$id = $app->userInfo["ID"];
    }
    else
    {
    	header('HTTP/1.0 403 Forbidden');
    	exit(0);
    }
    $verb = $_SERVER["REQUEST_METHOD"];
    switch($verb){
    	case "GET":
        	if (isset($argument))
            	$_POST["where"] = "ID=".$argument;
            else
            	$_POST["where"] = "ID=".$id;                
            require("Get.php");
        	break;
    	case "POST":
    		$obj = Utils::GetObjectFromBody();
            $_POST["nome"] = $obj->nome;
            $_POST["cognome"] = $obj->cognome;
            $_POST["localita"] = $obj->localita;
            $_POST["immagine"] = $obj->immagine;
            $_POST["telefono"] = $obj->telefono;
            $_POST["idmondo"] = $obj->idmondo;
            $_POST["idsquadra"] = $obj->idsquadra;
            $_POST["cdlingua"] = $obj->cdlingua;
            $_POST["cdnazione"] = $obj->cdnazione;
            $_POST["ID"] = $id;
            require("Edit.php");
        	break;
    }
	
?>