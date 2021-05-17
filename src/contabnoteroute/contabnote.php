<?php
	require_once('FantaApp.php');
	require_once('Utils.php');
	$App = FantaApp::getSingleTon();
    $App->Init(dirname(__file__));
    $contabnote = $App->Sql()->Fetch("ContabNote");
    $header = array();
    array_push($header,"ID","data","descrizione");
    $settings = (object) array('add' => '<button type="button" class="float-right mr-1 mb-1 btn btn-md btn-primary" onclick="AddContabNote()"><i class="far fa-plus-square"></i></button>',
    							'edit' => '<a type="button" class="action-link" onclick="EditContabNote(%ID%)"><i class="far fa-edit"></i></a>',
                                'editfullrow' => 'onclick="EditContabNote(%ID%)"',
                                'delete' => '<a type="button" class="action-link" onclick="DeleteContabNote(%ID%)"><i class="fas fa-trash-alt"></i></a>'
                                
                                );
    echo Utils::loadtext(dirname(__FILE__)."/contabnotemodal.html");
    Utils::ploatTable($contabnote,$header,$settings);
    $App->Close();
?>