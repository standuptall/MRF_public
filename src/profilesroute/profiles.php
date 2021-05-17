<?php
	require_once("FantaApp.php");
	require_once("Utils.php");
    $app = FantaApp::GetSingleTon();
    $app->Init(dirname(__file__));
    require_once("profilesroute/Get.php");
    $profili = GetProfili();
    $header = array();
    array_push($header,"ID","nomeutente","nome","cognome","datareg");
    $settings = (object) array();
    echo "<hr>";
    Utils::ploatTable($profili,$header,$settings);
    $app->Close();
?>