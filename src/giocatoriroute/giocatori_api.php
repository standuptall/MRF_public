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
            $_POST["Nome"] = $obj->Nome;
            require("Add.php");
            $_POST["where"] = "ID=".$_LASTID;
            require("Get.php");
        	break;
    	case "PUT":
    		$obj = Utils::GetObjectFromBody();
            $_POST["Nome"] = $obj->Nome;
            $_POST["ID"] = $argument;
            require("Edit.php");
        	break;
    	case "DELETE":
            $_POST["where"] = "ID=".$argument;
            require("Delete.php");
        	break;
    }
	
?>