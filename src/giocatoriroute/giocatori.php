<?php
	require_once('FantaApp.php');
	require_once('Utils.php');
	$App = FantaApp::getSingleTon();
    $App->Init(dirname(__file__));
    $giocatori = $App->Sql()->Fetch("Giocatori");
    $header = array();
    array_push($header,"ID","Nome");
    $settings = (object) array('add' => '<button type="button" class="float-right mr-1 mb-1 btn btn-md btn-primary" onclick="AddGiocatore()"><i class="far fa-plus-square"></i></button>',
    							'edit' => '<a type="button" class="action-link" onclick="EditGiocatore(%ID%)"><i class="far fa-edit"></i></a>', 
                                'delete' => '<a type="button" class="action-link" onclick="DeleteGiocatore(%ID%)"><i class="fas fa-trash-alt"></i></a>'
                                
                                );
    echo Utils::loadtext(dirname(__FILE__)."/giocatorimodal.html");
    Utils::ploatTable($giocatori,$header,$settings);
    $App->Close();
?>