<?php
	require_once("Utils.php");
	require_once("FantaApp.php");
	require_once('CastLib.php');
    $lib = new CastLib();
    $app = FantaApp::GetSingleTon();
    $app->TokenLogin();
    $verb = $_SERVER["REQUEST_METHOD"];
    switch($verb){
    
    	case "GET":
            	echo json_encode($lib->GetP());
        		break;
    	case "POST":
        		$obj = Utils::GetObjectFromBody();    
                var_dump($obj);
            	echo json_encode($lib->CastP($obj->name,$obj->pass));
        		break;
    }
	
?>