<?php
    include_once("FantaApp.php");
    include_once("Utils.php");
    include_once("../FantaApp.php");
    include_once("../Utils.php");
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $app = FantaApp::GetSingleTon();
        $nome = $_POST["Nome"];
        $tabella = "Giocatori";
        Utils::RequestAuthorization($app);
        if (isset($nome)&&isset($tabella)) {
        	$obj = (object)array("nome"=>$nome);
        	$_LASTID = $app->Sql()->AddRecord($tabella,$obj);
            echo $_LASTID;
        }
    }
?>