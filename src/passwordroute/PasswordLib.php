<?php
include_once("FantaApp.php");
include_once("Utils.php");
include_once("../FantaApp.php");
include_once("../Utils.php");
class PasswordLib
{
	function GetPasswords($argument)
    {
    	$app = FantaApp::GetSingleTon();
        
        $order = "nome ";
        $where = ($argument>0 ? " 1=1 AND  ID=".$argument : "1");
        $ret1 = $app->Sql()->Fetch_L("Passwords",$where,$order);
        return $ret1;
    }
	function GetPasswordsSearch($sea)
    {
    	$app = FantaApp::GetSingleTon();
        
        $order = "nome ";
        $where = " 1=1 AND ( nome like '%".$sea."%' OR descrizione like '%".$sea."%') ";
        $ret1 = $app->Sql()->Fetch_L("Passwords",$where,$order);
        return $ret1;
    }
}
?>

