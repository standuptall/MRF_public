<?php
   function GetProfili($id){
      $app = FantaApp::GetSingleTon();
      $tabella = "Profili";
      if (isset($id))
      	$where = " ID = ".$id;
      $obj = $app->Sql()->Fetch($tabella,$where);
      $ret = array();
      foreach($obj as $ob){
      	$newo = $app->Sql()->Fetch("ProfiliAggiuntiva","ID=".$ob["ID"]);
        if (count($newo)>0){
        	$ob["nome"] = $newo[0]["nome"];
        	$ob["cognome"] = $newo[0]["cognome"];
        	$ob["immagine"] = $newo[0]["immagine"];
        	$ob["idmondo"] = $newo[0]["idmondo"];
         }
         //tolgo alcuni campi
         unset($ob["url_confirm"]);
         unset($ob["key_confirm"]);
         unset($ob["session"]);
         unset($ob["password"]);
         array_push($ret,$ob);
      }
      return $ret;
    }
?>