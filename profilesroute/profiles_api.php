<?php
	require_once("Utils.php");
	require_once("FantaApp.php");
    require_once("profilesroute/Get.php");
    $app = FantaApp::GetSingleTon();
    $id = $GET["profile"];
    $obj = GetProfili($id);
    echo json_encode($obj);

	
?>