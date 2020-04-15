<?php
	require_once("FantaApp.php");
    $App = FantaApp::GetSingleton();
    $App->UserSignOut();
	header("location: /FantaApp");
?>