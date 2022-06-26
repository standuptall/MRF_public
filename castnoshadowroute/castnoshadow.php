<?php
    require_once('FantaApp.php');
	require_once('Utils.php');
	$App = FantaApp::getSingleTon();
    $App->Init(dirname(__file__));
	require_once('passwordroute/PasswordLib.php');
    $lib = new PasswordLib();
    $ret = $lib->GetPasswordsSearch($_GET["search"]);
    $oo= 'No way';
    echo '<div class="card px-1 py-5" style="background-color: #17a2b857!important;margin-top:5%!important;width:70%;margin:auto;">
     <div class="input-group" style="top:30%;width:70%;margin:auto;opacity:1">
     <input type="text" class="form-control" 
         placeholder="key" id="key"><input type="text" class="form-control" 
         placeholder="Search" id="search"> <span class="input-group-btn">
      <button class="btn btn-secondary" type="button" id="copy-button"
          data-toggle="tooltip" data-placement="button"
          title="Copy to Clipboard">
        Search
      </button>
    </span>
    
  </div></div>';
    
    
    $header = array();
    array_push($header,"ID","nome","descrizione","nomeutente");
    $settings = (object) array('add' => '<button type="button" class="float-right mr-1 mb-1 btn btn-md btn-primary" onclick="addPassword()"><i class="far fa-plus-square"></i></button>',
    							'edit' => '<a type="button" class="action-link" onclick="showPassword(%ID%)"><i class="far fa-edit"></i></a>'
                                
                                );
    echo Utils::loadtext(dirname(__FILE__)."/passwordmodal.html");
    Utils::ploatTable($ret,$header,$settings);
    $App->Close();
 
     
?>