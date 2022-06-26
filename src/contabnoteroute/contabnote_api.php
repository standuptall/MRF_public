<?php
	require_once("Utils.php");
	require_once("FantaApp.php");
    $app = FantaApp::GetSingleTon();
    $app->TokenLogin();
    $verb = $_SERVER["REQUEST_METHOD"];
    switch($verb){
    	case "GET":
            $_POST["where"] = "ID=".$argument;
            require("Get.php");
        	break;
    	case "POST":
    		$obj = Utils::GetObjectFromBody();            
            $_POST["data"] = $obj->data;
            $_POST["descrizione"] = $obj->descrizione;
            $_POST["localita"] = $obj->localita;
            $_POST["importo"] = $obj->importo;
            $_POST["tipospesa"] = $obj->tipospesa;
            $_POST["dacontab"] = $obj->dacontab;
            $_POST["debitore"] = $obj->debitore;
            require("Add.php");
            $_POST["where"] = "ID=".$_LASTID;
            require("Get.php");
        	break;
    	case "PUT":
    		$obj = Utils::GetObjectFromBody();    
            $_POST["data"] = $obj->data;
            $_POST["descrizione"] = $obj->descrizione;
            $_POST["localita"] = $obj->localita;
            $_POST["importo"] = $obj->importo;
            $_POST["tipospesa"] = $obj->tipospesa;
            $_POST["dacontab"] = $obj->dacontab;
            $_POST["debitore"] = $obj->debitore;
            $_POST["ID"] = $argument;
            require("Edit.php");
        	break;
    	case "DELETE":
            $_POST["where"] = "ID=".$argument;
            require("Delete.php");
        	break;
    }
	
?>