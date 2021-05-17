<?php
	require_once('FantaApp.php');
	require_once('Utils.php');
    require_once('RifornimentiLib.php');
    $lib = new RifornimentiLib();
	$App = FantaApp::getSingleTon();
    $App->Init(dirname(__file__));
    $rifornimenti = $lib->GetRifornimenti();
    $header = array();
    array_push($header,"ID","data","importo","tachimetro","costo","residuo","litri","stato");
    $settings = (object) array('add' => '<button type="button" class="float-right mr-1 mb-1 btn btn-md btn-primary" onclick="AddRifornimento()"><i class="far fa-plus-square"></i></button>',
    							'edit' => '<a type="button" class="action-link" onclick="EditRifornimento(%ID%)"><i class="far fa-edit"></i></a>',
                                'editfullrow' => 'onclick="EditRifornimento(%ID%)"',
                                'delete' => '<a type="button" class="action-link" onclick="DeleteRifornimento(%ID%)"><i class="fas fa-trash-alt"></i></a>',
                                'opt1' => '<a type="button" class="action-link" onclick="SetAsFirst(%ID%,event)"><i class="fas fa-step-forward"></i></a>',
                                'opt2' => '<a type="button" class="action-link" onclick="SetAsLast(%ID%,event)"><i class="fas fa-step-backward"></i></a>'
                                
                                );
    echo Utils::loadtext(dirname(__FILE__)."/rifornimentimodal.html");
    
    echo '<hr>
    <div class="container">
		<div class="row">
          <div class="col-md-3">
          	<div class="card p-2">
              <div class="row"><div class="col"><div class="digit-header"><strong>Consumo medio totale</strong></div></div></div>
              <div class="row"><div class="col digit consumo"><div class="lds-ripple"><div></div><div></div></div></div></div>
            </div>
          </div>
          <div class="col-md-3">
          	<div class="card p-2">
              <div class="row"><div class="col"><div class="digit-header"><strong>Chilometri mensili</strong></div></div></div>
              <div class="row"><div class="col digit km"><div class="lds-ripple"><div></div><div></div></div></div></div>
            </div>
          </div>
          <div class="col-md-3">
          	<div class="card p-2">
              <div class="row"><div class="col"><div class="digit-header"><strong>Costo totale</strong></div></div></div>
              <div class="row"><div class="col digit costo"><div class="lds-ripple"><div></div><div></div></div></div></div>
            </div>
          </div>
          <div class="col-md-3">
          	<div class="card p-2">
              <div class="row"><div class="col"><div class="digit-header"><strong>Costo Medio</strong></div></div></div>
              <div class="row"><div class="col digit costomedio"><div class="lds-ripple"><div></div><div></div></div></div></div>
            </div>
          </div>
    	</div>    
     </div>
    ';
    
    Utils::ploatTable($rifornimenti,$header,$settings);
    $App->Close();
?>