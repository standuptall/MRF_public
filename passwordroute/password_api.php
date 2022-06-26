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
            else if (isset($_GET["id"]))
            	echo json_encode($lib->GetPassword($_GET["id"]));
            else
        		echo json_encode($lib->GetPasswords($argument));
        	break;
    	case "POST":
        	$obj = Utils::GetObjectFromBody();  
            $lib->AddPassword($obj);
        	break;
    	case "PUT":
            $obj = Utils::GetObjectFromBody();   
            if ($argument<=0)
            	exit;
            $lib->UpdatePassword($argument,$obj);
        	break;
    	case "DELETE":
            $app->Sql()->DeleteRecord_L("Passwords","ID=".$argument);
        	break;
    }
	
?>