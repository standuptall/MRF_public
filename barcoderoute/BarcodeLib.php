<?php
include_once("FantaApp.php");
include_once("Utils.php");
include_once("../FantaApp.php");
include_once("../Utils.php");
class BarcodeLib
{
	function GetBarcode($barcode)
    {
    	$app = FantaApp::GetSingleTon();
        $ret = $app->Sql()->Fetch_L("Menu_IngredientiBarcode","barcode='".$barcode."'");
        $pasti = array();
        return $ret[0];
     }
    
}

?>

