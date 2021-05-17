<?php
    include_once("FantaApp.php");
    include_once("Utils.php");
    include_once("../FantaApp.php");
    include_once("../Utils.php");
	$date = new DateTime("now",new DateTimeZone("Europe/Rome"));
    $ora = $date->format("G");
    if ($ora >= 12 && $ora <14)
    {
    	$app = FantaApp::getSingleTon();
        $ret = $app->Sql()->Fetch("Menu_Pasto","data='".$date->format("Y-m-d")."' AND pranzocena=1");
        if (count($ret)>0)
        {
        	$ret1 = $app->Sql()->Fetch("Menu_PastoIngredienti","idpasto=".$ret[0]["ID"]);
            $list = "";
            foreach($ret1 as $ing)
            {
            	$list .= "<li>".$ing["ingrediente"]."</li>";
            }
                $subject = "Preparare i seguenti ingredienti per la cena";
                $message = '<html><body><ul>'.$list.'</ul></body></html>';
                $app->SendMail("a.zichittella@gmail.com",$subject,$message);
        }
    }
    
?>