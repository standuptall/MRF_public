<?php
include_once("FantaApp.php");
include_once("Utils.php");
include_once("../FantaApp.php");
include_once("../Utils.php");
class CastLib
{
	function CastP($key,$pass)
    {
    	$app = FantaApp::GetSingleTon();
        $ret = $app->Sql()->Fetch_L("Passwords","nome='".$key."'");
        if (count($ret)==0)
        	return;
        $prendi = $ret[0];
        $prendi["casted"] = 1;
        $prendi["clearP"] = $pass;
        $app->Sql()->EditRecord("Passwords",(object)$prendi);
     }
     function GetP(){
     	$app = FantaApp::GetSingleTon();
        $ret = $app->Sql()->Fetch_L("Passwords","casted>0");
        if (count($ret)==0)
        	return null;
        $prendi = $ret[0];
        if ($prendi->casted != 0)
        	return "errore";
        $take = $prendi["clearP"];
        $prendi["clearP"] = NULL;
        $prendi["casted"] = 0;
        $app->Sql()->EditRecord("Passwords",(object)$prendi);
        return $take;
     }
    
}

?>

