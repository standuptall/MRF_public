<?php
	require_once('FantaApp.php');
	require_once('Utils.php');
	$App = FantaApp::GetSingleTon();
    $normalform = false;
    $id = $_GET["id"];
    if (!isset($id)){
    	header("location: /FantaApp");
    }
    if ($App->ValidRegistration($id)){
    	$App->Init(dirname(__file__));
            $content = '
                <div class="container ">
                    <div class="row align-middle mt-4">
                        <div class="col-sm-12 col-md-9 col-lg-9">
                            <div class="alert alert-success" role="alert">
                              <h4 class="alert-heading">Fatto!</h4><p>Conferma avvenuta con successo!</p>
                            
                            <p class="mb-0"><a href="/FantaApp/login">Vai alla login</a></p>
                            </div>
                        </div>
                    </div>
                </div>';
    echo $content;
    $App->Close();
    }
    else {
    	header("Location: /FantaApp");
        return;
    }
    
    
?>







