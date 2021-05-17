<?php
	require_once("Utils.php");
	require_once("FantaApp.php");
    $app = FantaApp::GetSingleTon();
    $app->TokenLogin();
    $verb = $_SERVER["REQUEST_METHOD"];
    switch($verb){
    	case "GET":
        	$order = "ingrediente";
        	if (isset($_GET["order"]))
            	$order = "ID";
            $ret1 = $app->Sql()->Fetch_L("Menu_Dispensa","1",$order);
            	
        	echo json_encode($ret1);
        	break;
    	case "POST":
    	case "PUT":
            $obj = Utils::GetObjectFromBody();   
            
            $ingr["ingrediente"] = trim($obj->nome);    
            $ingr["presente"] = 0;      
            $ingr["quantita"] = $obj->quantita;    
            $id = $app->Sql()->AddRecord_L("Menu_Dispensa",$ingr);
        	break;
    	case "DELETE":
            $app->Sql()->DeleteRecord_L("Menu_Dispensa","ID=".$argument);
        	break;
    }
    
    class Ricetta
    {
    	public $ID = 0;
    	public $nomericetta ="";
    	public $descrizionericetta ="";
        public $ingredienti = array();
    }
    class Ingrediente
    {
    	public $ID = 0;
        public $idricetta = 0;
    	public $nome = "";
    }
	
?>