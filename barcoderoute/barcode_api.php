<?php
	require_once("Utils.php");
	require_once("FantaApp.php");
	require_once('BarcodeLib.php');
    $lib = new BarcodeLib();
    $app = FantaApp::GetSingleTon();
    $app->TokenLogin();
    $verb = $_SERVER["REQUEST_METHOD"];
    $date = new DateTime($argument);
    switch($verb){
    	case "GET":
        	if (isset($_GET["barcode"]))
            	echo json_encode($lib->GetBarcode($_GET["barcode"]));
        	break;
    }
	
?>