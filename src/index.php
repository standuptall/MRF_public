<?php

$request = $_SERVER['REQUEST_URI'];
if (substr($request,strlen($request)-1,1) == "/")
	$request = substr($request,0,strlen($request)-1);

$pos = strpos($request,"?");
if ($pos>0){
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
switch ($request) {
    case '/FantaApp' :
        require __DIR__ . '/home.php';
        break;
    case '/FantaApp/giocatori' :
        require __DIR__ . '/giocatoriroute/giocatori.php';
        break;
    case '/FantaApp/api/giocatori' :
        require __DIR__ . '/giocatoriroute/giocatori_api.php';
        break;
    case '/FantaApp/auth' :
        require __DIR__ . '/loginroute/login.php';
        break;
    case '/FantaApp/api/token' :
        require __DIR__ . '/loginroute/login_api.php';
        break;
    case '/FantaApp/signup' :
        require __DIR__ . '/signuproute/signup.php';
        break;
    case '/FantaApp/signout' :
        require __DIR__ . '/signoutroute/signout.php';
        break;
    case '/FantaApp/email_confirm' :
        require __DIR__ . '/email_confirmroute/email_confirm.php';
        break;
    case '/FantaApp/profile' :
        require __DIR__ . '/profileroute/profile.php';
        break;
    case '/FantaApp/api/profile' :
        require __DIR__ . '/profileroute/profile_api.php';
        break;
    case '/FantaApp/messages' :
        require __DIR__ . '/messagesroute/messages.php';
        break;
    case '/FantaApp/api/messages' :
        require __DIR__ . '/messagesroute/messages_api.php';
        break;
    default :
    	require __DIR__ . '/404.html';
        break;
}
?>