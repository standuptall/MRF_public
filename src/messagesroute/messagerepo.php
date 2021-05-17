<?php
	function GetMessages($id)
    {
    	require_once("FantaApp.php");
        $app = FantaApp::GetSingleTon();
		$tabella = Utils::GetTabellaMondo($app,"Posta");
        $messaggi = $app->Sql()->Fetch($tabella,"idprofilo=".$id);
        
        $ret = array();
        foreach($messaggi as $msg){
          $newobj = (object)[];
          $newobj->contenuto = $msg["contenuto"];
          $newobj->oggetto = $msg["oggetto"];
          $mit1 = $app->Sql()->Fetch("Profili","ID=".$msg["idprofiloda"]);
          $mit2 = $app->Sql()->Fetch("ProfiliAggiuntiva","ID=".$msg["idprofiloda"]);
          if (count($mit2)==1)
          	$newobj->mittente = $mit2[0]["Nome."].' '.$mit2[0]["Cognome."];
          else
          	$newobj->mittente = $mit1[0]["nomeutente"];
          $newobj->id = $msg["ID"];
          $newobj->dataposta = $msg["dataposta"];
          array_push($ret,$newobj);
        }
        echo "<script>var msgreceivedcollection='".json_encode($ret)."'</script>";
        return $ret;
    }
    function GetMessagesSent($id)
    {
    	require_once("FantaApp.php");
        $app = FantaApp::GetSingleTon();
		$tabella = Utils::GetTabellaMondo($app,"Posta");
        $messaggi = $app->Sql()->Fetch($tabella,"idprofiloda=".$id);
        
        $ret = array();
        foreach($messaggi as $msg){
          $newobj = (object)[];
          $newobj->contenuto = $msg["contenuto"];
          $newobj->oggetto = $msg["oggetto"];
          $mit1 = $app->Sql()->Fetch("Profili","ID=".$msg["idprofiloda"]);
          $mit2 = $app->Sql()->Fetch("ProfiliAggiuntiva","ID=".$msg["idprofiloda"]);
          if (count($mit2)==1)
          	$newobj->mittente = $mit2[0]["Nome."].' '.$mit2[0]["Cognome."];
          else
          	$newobj->mittente = $mit1[0]["nomeutente"];
          $newobj->id = $msg["ID"];
          $newobj->dataposta = $msg["dataposta"];
          array_push($ret,$newobj);
        }
        echo "<script>var msgsentcollection='".json_encode($ret)."'</script>";
        return $ret;
    }
    
    function GetConversations($id)
    {
    	require_once("FantaApp.php");
        $app = FantaApp::GetSingleTon();
		$tabella = Utils::GetTabellaMondo($app,"Posta");
        $query = 'SELECT A.idprofilo, A.idprofiloda,A.Profilo,
                  A.profiloda,
                  A.id, A.dataposta,Posta.contenuto as testo
          FROM(

          SELECT idprofilo, idprofiloda,profilo.nomeutente as Profilo,
                  profiloda.nomeutente as profiloda,
                  MAX(Posta.ID) as id, MAX(Posta.dataposta) as dataposta
          FROM 
          `MRF_Mondo001_Posta` Posta
          INNER JOIN Profili	 profiloda
              ON	profiloda.ID = Idprofiloda
          INNER JOIN Profili	 profilo
              ON	profilo.ID = Idprofilo
          WHERE idprofiloda = '.$id.' OR idprofilo='.$id.'
          GROUP BY idprofilo, idprofiloda) A
          INNER JOIN `MRF_Mondo001_Posta` Posta
              ON	Posta.ID = A.ID';
        $messaggi = $app->Sql()->DoQuery($query);
        
        echo "<script>var msgreceivedcollection='".json_encode($messaggi)."'</script>";
        return $messaggi;
    }
?>