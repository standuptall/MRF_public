<?php
	require_once('FantaApp.php');
	require_once('Utils.php');
	$App = FantaApp::getSingleTon();
    if (isset($App->userInfo))
		header("location: /FantaApp");
    $normalform = false;
    if ($_SERVER["REQUEST_METHOD"]=="POST"){
    	$nomeutente = $_POST["nomeutente"];
    	$password = $_POST["password"];
        if (isset($nomeutente,$password)){
        	if (!$App->UserLogin($nomeutente,$password)){
            	$testo = '<p>Username e/o password errati o account inattivo!</p>
                            <hr>
                            <p class="mb-0"><a href="/FantaApp/login">Riprova</a></p>';
                $h = "Ooops";
            }
            else {
              header("location: /FantaApp");
            }
            $App->Init(dirname(__file__));
            $content = '
                <div class="container ">
                    <div class="row align-middle mt-4 justify-content-center">
                        <div class="col-sm-12 col-md-7 col-lg-7">
                            <div class="alert alert-success" role="alert">
                              <h4 class="alert-heading">'.$h.'!</h4>'.$testo.'
                            </div>
                        </div>
                    </div>
                </div>
                ';
            echo $content;
            $App->Close();
        }
        else
        	$normalform = true;
	}
    else
    	$normalform = true;
    if ($normalform) {
      $App->Init(dirname(__file__));
      $content = '<div class="container">
      
          <div class="row">
              <div class="col-md-5 col-sm-12 mx-auto">
                <div id="first">
                    <div class="myform form ">
                         <div class="logo mb-3">
                             <div class="col-md-12 text-center">
                                <h1>Login</h1>
                             </div>
                        </div>
                       <form method="post" name="login">
                               <div class="form-group">
                                  <label for="exampleInputEmail1">Nome utente</label>
                                  <input type="text" name="nomeutente"  class="form-control" id="email" aria-describedby="emailHelp" placeholder="Inserisci utente">
                               </div>
                               <div class="form-group">
                                  <label for="exampleInputEmail1">Password</label>
                                  <input type="password" name="password" id="password"  class="form-control" aria-describedby="emailHelp" placeholder="Inserisci Password">
                               </div>
                               <div class="col-md-12 text-center ">
                                  <button type="submit" class=" btn btn-block mybtn btn-primary tx-tfm">Login</button>
                               </div>
                               <div class="form-group">
                                  <p class="text-center">Non  hai ancora un account? <a href="/FantaApp/signup" id="signup">Registrati!</a></p>
                               </div>
                            </form>

                    </div>
                </div>
          </div>
        </div>   
      </div>   ';
      echo $content;
      $App->Close();
	}
?>





