<?php
    include_once("FantaApp.php");
    include_once("Utils.php");
    include_once("../FantaApp.php");
    include_once("../Utils.php");
    $ret = (object)array("error"=>false,"message"=>"");
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // collect value of input field
        $app = FantaApp::GetSingleTon();
        $tabella = "Giocatori";
        $where = $_POST['where'];
        Utils::RequestAuthorization($app);
        if (isset($tabella) && isset($where)){
        	$app->Sql()->DeleteRecord($tabella,$where);
            $ret->error = false;
			$ret->message = "Giocatore eliminato correttamente";
        }
        else
        {
            $ret->error = true;
			$ret->message = "Parametri insufficienti";
        }
    }
    else
    {
      $ret->error = true;
      $ret->message = "Method not allowed";
    }
    
    echo json_encode($ret);
?>