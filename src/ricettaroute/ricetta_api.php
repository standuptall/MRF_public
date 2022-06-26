<?php
	require_once("Utils.php");
	require_once("FantaApp.php");
    $app = FantaApp::GetSingleTon();
    $app->TokenLogin();
    $verb = $_SERVER["REQUEST_METHOD"];
    switch($verb){
    	case "GET":
        	if ($argument)
            {
        		$ret1 = $app->Sql()->Fetch_L("Menu_Ricetta","ID=".$argument)[0];
                $dispensa = $app->Sql()->Fetch_L("Menu_Dispensa");
        		$ret2 = $app->Sql()->Fetch_L("Menu_RicettaIngredienti","idricetta=".$argument);
                $ret = new Ricetta();
                $ret->ID = $ret1["ID"];
                $ret->nomericetta = $ret1["nome"];
                $ret->descrizionericetta = $ret1["descrizione"];
                foreach($ret2 as $ing){
                	$ingrediente = new Ingrediente();
                    $ingrediente->ID = $ing["ID"];
                    $ingrediente->nome = $ing["nome"];
                    $ingrediente->idricetta = $ing["idricetta"];
                    foreach($dispensa as $disp)
                    {
                    	if (strtolower($disp["ingrediente"])==strtolower($ing["nome"]))
                        	$ingrediente->presente = 1;
                    }
                    array_push($ret->ingredienti,$ingrediente);
                }
            }
            else{
            	if (isset($_GET["search"]))
                	$ret = $app->Sql()->Fetch_L("Menu_Ricetta"," nome LIKE '%".$_GET["search"]."%'"," nome");
                else
                	$ret = $app->Sql()->Fetch_L("Menu_Ricetta",NULL," nome");
            }
            	
        	echo json_encode($ret);
        	break;
    	case "POST":
    	case "PUT":
            $obj = Utils::GetObjectFromBody();    
  			if ($argument){
              $ricetta["nome"] = $obj->nome;    
              $ricetta["descrizione"] = $obj->descrizione;
              $ricetta["ID"] = $argument;
              $app->Sql()->EditRecord("Menu_Ricetta",(object)$ricetta);
              $app->Sql()->DeleteRecord_L("Menu_RicettaIngredienti","idricetta=".$argument);
              foreach($obj->ingredienti as $ing)
              {
                  if (!$ing->nome)
                      continue;
                  $ingrediente["idricetta"] = $argument;
                  $ingrediente["nome"] = $ing->nome;
                  $app->Sql()->AddRecord_L("Menu_RicettaIngredienti",$ingrediente);
              }
            }
            else {
              $ricetta["nome"] = $obj->nome;    
              $ricetta["descrizione"] = $obj->descrizione;
              $id = $app->Sql()->AddRecord_L("Menu_Ricetta",$ricetta);
              foreach($obj->ingredienti as $ing)
              {
                  if (!$ing->nome)
                      continue;
                  $ingrediente["idricetta"] = $id;
                  $ingrediente["nome"] = $ing->nome;
                  $app->Sql()->AddRecord_L("Menu_RicettaIngredienti",$ingrediente);
              }
            }
        	break;
    	case "DELETE":
            $app->Sql()->DeleteRecord_L("Menu_Ricetta","ID=".$argument);
            $app->Sql()->DeleteRecord_L("Menu_RicettaIngredienti","idricetta=".$argument);
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
        public $presente = 0;
    }
	
?>