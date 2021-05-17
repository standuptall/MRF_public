<?php
    include_once("FantaApp.php");
    include_once("Utils.php");
    include_once("../FantaApp.php");
    include_once("../Utils.php");
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $app = FantaApp::GetSingleTon();
        $data = $_POST["data"];
        $descrizione = $_POST["descrizione"];
        $localita = $_POST["localita"];
        $importo = $_POST["importo"];
        $tipospesa = $_POST["tipospesa"];
        $dacontab = $_POST["dacontab"];
        $debitore = $_POST["debitore"];
        $tabella = "ContabNote";
        Utils::RequestAuthorization($app);
        if (isset($data)&&isset($descrizione)&&isset($importo)&&isset($tabella)) {
        	$obj = (object)array("data"=>$data,
            					 "descrizione"=>$descrizione,
            					 "localita"=>$localita,
            					 "importo"=>$importo,
            					 "tipospesa"=>$tipospesa,
            					 "dacontab"=>$dacontab,
            					 "debitore"=>$debitore,
            );
        	$_LASTID = $app->Sql()->AddRecord($tabella,$obj);
            echo $_LASTID;
        }
    }
?>