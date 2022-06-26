 <?php
	require_once("Utils.php");
	require_once("FantaApp.php");
    require_once('PasswordLib.php');
    $lib = new PasswordLib();
    $app = FantaApp::GetSingleTon();
    $app->TokenLogin();
    $verb = $_SERVER["REQUEST_METHOD"];
    switch($verb){
    	case "GET":
        	if (isset($_GET["search"]))
            	echo json_encode($lib->GetPasswordsSearch($_GET["search"]));
            else
        		echo json_encode($lib->GetPasswords($argument));
            
        	break;
    	case "POST":
        	$obj = Utils::GetObjectFromBody();   
            
            $ingr["nome"] = $obj->nome;    
            $ingr["descrizione"] = $obj->descrizione;    
            $ingr["nomeutente"] = $obj->nomeutente;      
            $ingr["password"] = $obj->password;      
            $ingr["IV"] = $obj->IV;    
            $id = $app->Sql()->AddRecord_L("Passwords",$ingr);
        	break;
    	case "PUT":
            $obj = Utils::GetObjectFromBody();   
            if ($argument<=0)
            	exit;
            $ingr = new stdClass();
            $ingr->ID = $argument;    
            $ingr->nome = $obj->nome;       
            $ingr->descrizione = $obj->descrizione;     
            $ingr->nomeutente = $obj->nomeutente;      
            $ingr->password = $obj->password;         
            $ingr->IV = $obj->IV;   
            $old = array();
            $old["nome"] = $ingr->nome;
            $old["descrizione"] = $ingr->descrizione;
            $old["nomeutente"] = $ingr->nomeutente;
            $old["password"] = $ingr->password;
            $old["IV"] = $ingr->IV;
            $old["idpass"] = $argument;
            $id = $app->Sql()->AddRecord_L("Passwords_Story",$old);
            $id = $app->Sql()->EditRecord("Passwords",$ingr);
        	break;
    	case "DELETE":
            $app->Sql()->DeleteRecord_L("Passwords","ID=".$argument);
        	break;
    }
	
?>