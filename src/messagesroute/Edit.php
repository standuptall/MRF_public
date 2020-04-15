<?php
	function SendMessage($idmondo,$from,$to,$date,$oggetto,$contenuto){
        include_once("FantaApp.php");
        include_once("Utils.php");
        include_once("../FantaApp.php");
        include_once("../Utils.php");
        $app = FantaApp::GetSingleTon();
		$tabella = Utils::GetTabellaMondo($app,"Posta");
        Utils::RequestAuthorization($app);
        $newobj = array("idmondo"=>$idmondo,
        				"idprofilo"=>$from,
        				"idprofiloda"=>$to,
        				"dataposta"=>$date,
        				"oggetto"=>$oggetto,
        				"contenuto"=>$contenuto);
        $app->Sql(true)->AddRecord($tabella,$newobj);
     
    }
    
?>