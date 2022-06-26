<?php
	require_once("Utils.php");
	require_once("FantaApp.php");
    $app = FantaApp::GetSingleTon();
    $app->TokenLogin();
    $verb = $_SERVER["REQUEST_METHOD"];
    switch($verb){
    	case "GET":
        	if (!isset($app->userInfo["menu_idutente"]))
          		$app->userInfo["menu_idutente"] = ($_GET["id_utente"]);
            if (isset($_GET["frequent"]))
            	$ret1 = $app->Sql()->Fetch_L("Menu_IngredientiFrequenti","1","ingrediente");
            else
        		$ret1 = $app->Sql()->Fetch_L("Menu_ListaSpesa","1","ingrediente");
            $ret2 = array();
            foreach($ret1 as $ingr)
            {
             	$nome = strtolower($ingr["ingrediente"]);
                $disp = $app->Sql()->Fetch_L("Menu_IngredientiCategoria","ingrediente = '".$nome."'");
                if ($disp){
                  $ingr["categoria"] = $disp[0]["categoria"];
                }
                else $ingr["categoria"] = 1;
                array_push($ret2,$ingr);
            }
        	echo json_encode($ret2);
        	break;
    	case "POST":
    	case "PUT":
        	if (isset($_GET["savelist"]))
            {
            	$ret1 = $app->Sql()->Fetch_L("Menu_ListaSpesa","prelevato=1");
                foreach($ret1 as $ingr){
                    $nome = strtolower($ingr["ingrediente"]);
                    $disp = $app->Sql()->Fetch_L("Menu_Dispensa","ingrediente = '".$nome."'");
                    if (!$disp){
                        $disp = array();
                        $disp["ingrediente"] = trim($ingr["ingrediente"]);
                        $app->Sql()->AddRecord_L("Menu_Dispensa",$disp);
                    }
                    $app->Sql()->DeleteRecord_L("Menu_ListaSpesa","ID = ".$ingr["ID"]);
                }
            }
            else
        	{
              $obj = Utils::GetObjectFromBody();   
              if ($argument>0)
              {
                  $ingr["ID"] = $argument;
                  $ingr["ingrediente"] = $obj->ingrediente;
                  $ingr["prelevato"] = $obj->prelevato;
                  $app->Sql()->EditRecord("Menu_ListaSpesa",(object)$ingr);
              }
              else
              {
              	foreach($obj as $single)
                {
                  $nome  = strtolower($single->ingrediente);
                  if (!isset($app->userInfo["menu_idutente"]))
                  	$app->userInfo["menu_idutente"] = $single->id_utente;
                  $ret = $ret1 = $app->Sql()->Fetch_L("Menu_ListaSpesa","ingrediente='".$nome."'");
                  if (count($ret)==0)
                  {
                    $ingr["ingrediente"] = $single->ingrediente;    
                    $ingr["prelevato"] = 0;      
                    $id = $app->Sql()->AddRecord_L("Menu_ListaSpesa",$ingr);
                  }
                }
              }
            }
        	break;
    	case "DELETE":
            $app->Sql()->DeleteRecord_L("Menu_ListaSpesa","ID=".$argument);
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