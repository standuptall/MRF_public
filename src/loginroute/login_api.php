<?php
	require_once("Utils.php");
	require_once("FantaApp.php");
    $obj = Utils::GetObjectFromBody();
    $nomeutente = $obj->nomeutente;
    $password = $obj->password;
    if (!isset($nomeutente,$password)){
    	http_response_code(400);
        exit(0);
    }
    $app = FantaApp::GetSingleTon();
	$ret = (object)array("message"=>"","token"=>"");
    if ($app->UserLogin($nomeutente,$password)){
    	$user = $app->userInfo;
        $fields = (object)array("nomeutente"=>$user["nomeutente"],"email"=>$user["email"],
        						"iat"=>time());
        $token = Utils::getJwt($fields);
        $ret->message = "Token generato scadra' fra 7 giorni";
        $ret->token = $token;
    }
    else
    	$ret->message = "Username e/o password non validi";
    echo json_encode($ret);
    
    
?>