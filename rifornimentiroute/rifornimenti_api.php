 <?php
	require_once("Utils.php");
	require_once("FantaApp.php");
    require_once('RifornimentiLib.php');
    $lib = new RifornimentiLib();
    $app = FantaApp::GetSingleTon();
    $app->TokenLogin();
    $verb = $_SERVER["REQUEST_METHOD"];
    switch($verb){
    	case "GET":
        	if (isset($_GET["km"]))
            	echo json_encode($lib->GetChilometriMensili());
            else if (isset($_GET["consumo"]))
            	echo json_encode($lib->GetConsumoMedio());
            else if (isset($_GET["costo"]))
            	echo json_encode($lib->GetCosto());
            else if (isset($_GET["costomedio"]))
            	echo json_encode($lib->GetCostoOgniCentoChilometri());       
            else if (isset($_GET["kmtotali"]))
            	echo json_encode($lib->GetChilometriTotali());                
            else if (isset($_GET["excel"]))
            	echo json_encode($lib->GetExcel());                          
            else if (isset($_GET["stazioni"]))
            	echo json_encode($lib->GetStazioni('PV','19'));             
            else if (isset($_GET["graph"])){
            	$x = $lib->GetXSeries();
            	$y = $lib->GetYSeries();
                $ret = array();
                $count = 0;
                foreach($x as $num)
                {
                    $obj = array();
                    $obj["x"] = $num;
                    $obj["y"] = $y[$count]; 
                    array_push($ret, (object)$obj);
                    $count++;
                }
                echo json_encode($ret);
            }
            else
        		echo json_encode($lib->GetRifornimenti($argument));
            
        	break;
    	case "POST":
        	$obj = Utils::GetObjectFromBody();   
            
            $ingr["data"] = trim($obj->data);    
            $ingr["importo"] = $obj->importo;      
            $ingr["tachimetro"] = $obj->tachimetro;    
            $ingr["costo"] = $obj->costo;     
            $ingr["residuo"] = $obj->residuo;    
            $id = $app->Sql()->AddRecord_L("Rifornimenti",$ingr);
            echo json_encode($ingr);
        	break;
    	case "PUT":
        	if (isset($_GET["first"]))
            	$lib->SetFirst($argument);
        	else if (isset($_GET["last"]))
            	$lib->SetLast($argument);
            else {
              $obj = Utils::GetObjectFromBody();   
              if ($argument<=0)
                  exit;
              $ingr = new stdClass();
              $ingr->ID = $argument;    
              $ingr->data = str_replace("/","-",trim($obj->data));    
              $ingr->importo = $obj->importo;      
              $ingr->tachimetro = $obj->tachimetro;    
              $ingr->costo = $obj->costo;     
              $ingr->residuo = $obj->residuo;    
              $id = $app->Sql()->EditRecord("Rifornimenti",$ingr);
            }
        	break;
    	case "DELETE":
            $app->Sql()->DeleteRecord_L("Rifornimenti","ID=".$argument);
        	break;
    }
	
?>