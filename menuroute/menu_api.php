<?php
	require_once("Utils.php");
	require_once("FantaApp.php");
	require_once('MenuLib.php');
    $lib = new MenuLib();
    $app = FantaApp::GetSingleTon();
    $app->TokenLogin();
    $verb = $_SERVER["REQUEST_METHOD"];
    $date = new DateTime($argument);
    switch($verb){
    	case "GET":
        	if (isset($_GET["ingredienti"]))
            	echo json_encode($lib->GetIngredientiToBuy());
            else if (isset($_GET["controllo"]))
            	echo json_encode($lib->GetIngredientiDispensaDaControllare());
            else if (isset($_GET["proposte"]))
            	echo json_encode($lib->GetProposte());
            else if (isset($_GET["costo"]))
            	echo json_encode($lib->GetCostoStimato($_GET["tipo"]));
            else {
                if (!isset($app->userInfo["menu_idutente"]))
                	$app->userInfo["menu_idutente"] = $_GET["idutente"];
        		echo json_encode($lib->GetMealByDay($date));
                }
        	break;
    	case "POST":
    	case "PUT":
        	if (isset($_GET["aggiorna"]))
            {
            	$lib->RefreshPastoIngredients();
            }
            else
            {
              $obj = Utils::GetObjectFromBody();            
              $lib->SaveMeal($date,$obj);
            }
        	break;
    	case "DELETE":
    		$obj = Utils::GetObjectFromBody();            
            $lib->DeleteMeal($date,$obj);
        	break;
    }
	
?>