<?php
    include_once("FantaApp.php");
    include_once("Utils.php");
    include_once("../FantaApp.php");
    include_once("../Utils.php");
    $app = FantaApp::GetSingleTon();
    $nome = $_POST["Nome"];
    $tabella = "Giocatori";
    $id = $_POST["ID"];
    Utils::RequestAuthorization($app);
    if (isset($nome,$tabella,$id)) {
      $obj = (object)array("nome"=>$nome,"ID"=>$id);
      $app->Sql()->EditRecord($tabella,$obj);
    }
?>