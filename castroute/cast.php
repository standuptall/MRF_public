<?php
    require_once('FantaApp.php');
	require_once('Utils.php');
	$App = FantaApp::getSingleTon();
    $App->Init(dirname(__file__));
	require_once('CastLib.php');
    $lib = new CastLib();
    $p = $lib->GetP();
    if (!$p){
    	$oo= 'No way';
    }
    else
    	$oo= $p;
     echo '<div class="card px-1 py-5" style="background-color: #17a2b857!important;top:30%;width:70%;margin:auto;">
     <div class="input-group" style="top:30%;width:70%;margin:auto;opacity:1">
    <input type="text" class="form-control" 
        value="'.$oo.'" placeholder="Some path" id="copy-input">
    <span class="input-group-btn">
      <button class="btn btn-secondary" type="button" id="copy-button"
          data-toggle="tooltip" data-placement="button"
          title="Copy to Clipboard">
        Copia
      </button>
    </span>
  </div></div>';
    $App->Close();
?>