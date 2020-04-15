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
    $app->showQuery = true;
    switch($verb){
    	case "GET":
        	$where = "idprofilo = ".$id;
        	if (isset($argument))
            {
            	//messaggi di un utente
                $where .= " AND IdProfiloDa = ".$argument;
            }
            else
            {
            	//messaggi di tutti gli utenti
            }
            $_POST["where"] = $where;
            require("Get.php");
        	break;
    	case "POST":
        	//invio un messaggio a
    		$obj = Utils::GetObjectFromBody();
            $idmondo = $app->userInfoAgg["idmondo"];
            $from = $app->userInfo["ID"];
            $to = $argument;
            $date = date("Y-m-d H:i:s");
            $oggetto = $obj->subject;
            $contenuto = $obj->body;            
            require("Edit.php");
            if (isset($idmondo,$from,$to,$date,$oggetto,$contenuto))
            	SendMessage($idmondo,$from,$to,$date,$oggetto,$contenuto);
        	break;
    }
	
?>