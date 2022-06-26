<?php
$request = $_SERVER['REQUEST_URI'];
if (substr($request,strlen($request)-1,1) == "/")
	$request = substr($request,0,strlen($request)-1);
$pos = strpos($request,"?");
if ($pos>0){
	$getarguments = substr($request,$pos+1,strlen($request)-$pos-1);
    $arr = explode("&",$getarguments);
    $GET = [""=>""];
    foreach($arr as $args){
		$idue = explode("=",$args);
        if (count($idue)==2)
    		$GET = [
            	$idue[0]=>$idue[1]
                ];
    }
	$newrequest = substr($request,0,$pos);
    $request = $newrequest;
}
$pos = strrpos($request,"/");
if ($pos>0){
	$lastargument = substr($request,$pos+1,strlen($request)-$pos-1);
    if (Is_Numeric($lastargument)){
      $newrequest = substr($request,0,$pos);
      $request = $newrequest;
    }
    else
    	unset($lastargument);
    $argument = $lastargument;
}
require __DIR__."/FantaApp.php";
$app = FantaApp::GetSingleTon();
$auth = $app->GetAuth($request);
$verb = $_SERVER["REQUEST_METHOD"];
if ($auth["enabled"]=="0")
	NotFound();
if ($auth[strtolower($verb)]=="0")
	Utils::RequestAuthorization($app);
switch ($request) {
    case '/FantaApp' :
    	$app->pagina = "home";
        require __DIR__ . '/home.php';
        break;
    case '/FantaApp/giocatori' :
    	$app->pagina = "giocatori";
        require __DIR__ . '/giocatoriroute/giocatori.php';
        break;
    case '/FantaApp/api/giocatori' :
        require __DIR__ . '/giocatoriroute/giocatori_api.php';
        break;
    case '/FantaApp/auth' :
    	$app->pagina = "login";
        require __DIR__ . '/loginroute/login.php';
        break;
    case '/FantaApp/api/token' :
        require __DIR__ . '/loginroute/login_api.php';
        break;
    case '/FantaApp/signup' :
    	$app->pagina = "signup";
        require __DIR__ . '/signuproute/signup.php';
        break;
    case '/FantaApp/signout' :
        $app->FallBackIfNotAuthenticated();
        require __DIR__ . '/signoutroute/signout.php';
        break;
    case '/FantaApp/email_confirm' :
        require __DIR__ . '/email_confirmroute/email_confirm.php';
        break;
    case '/FantaApp/profile' :
    	$app->pagina = "profile";
        $app->FallBackIfNotAuthenticated();
        require __DIR__ . '/profileroute/profile.php';
        break;
    case '/FantaApp/api/profile' :
        require __DIR__ . '/profileroute/profile_api.php';
        break;
    case '/FantaApp/messages' :
    	$app->pagina = "messages";
        $app->FallBackIfNotAuthenticated();
        require __DIR__ . '/messagesroute/messages.php';
        break;
    case '/FantaApp/api/messages' :
        require __DIR__ . '/messagesroute/messages_api.php';
        break;
    case '/FantaApp/profiles' :
    	$app->pagina = "profiles";
        require __DIR__ . '/profilesroute/profiles.php';
        break;
    case '/FantaApp/api/profiles' :
        require __DIR__ . '/profilesroute/profiles_api.php';
        break;
    case '/FantaApp/contabnote' :
    	$app->pagina = "contabnote";
        require __DIR__ . '/contabnoteroute/contabnote.php';
        break;
    case '/FantaApp/api/contabnote' :
        require __DIR__ . '/contabnoteroute/contabnote_api.php';
        break;
    case '/FantaApp/menu' :
        require __DIR__ . '/menuroute/menu.php';
        break;
    case '/FantaApp/api/menu' :
        require __DIR__ . '/menuroute/menu_api.php';
        break;
    case '/FantaApp/api/ricetta' :
        require __DIR__ . '/ricettaroute/ricetta_api.php';
        break;
    case '/FantaApp/api/dispensa' :
        require __DIR__ . '/dispensaroute/dispensa_api.php';
        break;
    case '/FantaApp/api/listaspesa' :
        require __DIR__ . '/listaspesaroute/listaspesa_api.php';
        break;
    case '/FantaApp/rifornimenti' :
    	$app->pagina = "rifornimenti";
        require __DIR__ . '/rifornimentiroute/rifornimenti.php';
        break;
    case '/FantaApp/api/rifornimenti' :
        require __DIR__ . '/rifornimentiroute/rifornimenti_api.php';
        break;
    case '/FantaApp/api/password' :
        require __DIR__ . '/passwordroute/password_api.php';
        break;
    case '/FantaApp/barcode' :
    	$app->pagina = "barcode";
        require __DIR__ . '/barcoderoute/barcode.php';
        break;    
    case '/FantaApp/api/barcode' :
        require __DIR__ . '/barcoderoute/barcode_api.php';
        break;
    case '/FantaApp/cast' :
    	$app->pagina = "cast";
        require __DIR__ . '/castroute/cast.php';
        break;    
    case '/FantaApp/api/cast' :
        require __DIR__ . '/castroute/cast_api.php';
        break;    
    case '/FantaApp/castnoshadow' :
    	$app->pagina = "castnoshadow";
        require __DIR__ . '/castnoshadowroute/castnoshadow.php';
        break;    
    default :
    	NotFound();
        break;
}
function NotFound(){
	require __DIR__ . '/404.html';
    exit("");
}
?>