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
    	case "PUT":
    		$obj = Utils::GetObjectFromBody();
            $_POST["nome"] = $obj->first_name;
            $_POST["cognome"] = $obj->last_name;
            $_POST["localita"] = $obj->location;
            $_POST["immagine"] = $obj->picture;
            $_POST["telefono"] = $obj->phone;
            $_POST["idmondo"] = $obj->idmondo;
            $_POST["idsquadra"] = $obj->idsquadra;
            $_POST["ID"] = $id;
            require("Edit.php");
        	break;
    }
	
?>