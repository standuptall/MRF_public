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
	function GetPassword($id)
    {
    	$app = FantaApp::GetSingleTon();
        
        $order = "nome ";
        $where = " ID = '".$id."'";
        $ret1 = $app->Sql()->Fetch_L("Passwords",$where,$order);
        $ret = array();
        $ret["nome"] = $ret1[0]["nome"];
        $ret["descrizione"] = $ret1[0]["descrizione"];
        $ret["nomeutente"] = $ret1[0]["nomeutente"];
        $ret["IV"] = $ret1[0]["IV"];
        $ret["password"] = $ret1[0]["password"];
        return $ret;
    }
    function UpdatePassword($id,$obj){
    	$app = FantaApp::GetSingleTon();
    	$ingr = new stdClass();
        $ingr->ID = $id;    
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
        $old["idpass"] = $id;
        $id = $app->Sql()->AddRecord_L("Passwords_Story",$old);
        $id = $app->Sql()->EditRecord("Passwords",$ingr);
    }
    function AddPassword($obj){    
    	$app = FantaApp::GetSingleTon();
    	$ingr["nome"] = $obj->nome;    
        $ingr["descrizione"] = $obj->descrizione;    
        $ingr["nomeutente"] = $obj->nomeutente;      
        $ingr["password"] = $obj->password;      
        $ingr["IV"] = $obj->IV;    
        $id = $app->Sql()->AddRecord_L("Passwords",$ingr);
    }
}
?>

