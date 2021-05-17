<?php
	require_once('FantaApp.php');
	require_once('Utils.php');
	$App = FantaApp::getSingleTon();
    $normalform = false;
    if ($_SERVER["REQUEST_METHOD"]=="POST"){
    	var_dump($_POST);
    	$nomeutente = $_POST["nomeutente"];
    	$email = $_POST["email"];
    	$password = $_POST["password"];
        if (isset($nomeutente,$email,$password)){
        	if (!$App->RegisterUser($email,$nomeutente,$password)){
            	$testo = '<p>L\'email '.$email.' esiste già.</p>
                            <hr>
                            <p class="mb-0">Premi il link per recuperare la password</p>';
            }
            else {
              $testo = '<p>Una mail è stata inviata all\'indirizzo '.$email.'</p>
                            <hr>
                            <p class="mb-0">Clicca sul link nel testo dell\'email per confermare la registrazione</p>';
            }
            $App->Init(dirname(__file__));
            $content = '
                <div class="container ">
                    <div class="row align-middle mt-4">
                        <div class="col-sm-12 col-md-9 col-lg-9">
                            <div class="alert alert-success" role="alert">
                              <h4 class="alert-heading">Fatto!</h4>'.$testo.'
                            </div>
                        </div>
                    </div>
                </div>
                ';
            echo $content;
            $App->Close();
        }
        else {
        	$normalform = true;
        }
    }
    else {
    	$normalform = true;
    }
    if ($normalform){
      $App->Init(dirname(__file__));
      $content = '<div class="container">
              <div class="col-md-5 mx-auto">
      <div id="second">
    <div class="myform form ">
      <div class="logo mb-3">
        <div class="col-md-12 text-center">
          <h1 >Signup</h1>
        </div>
      </div>
      <form method="post" name="registration">
        <div class="form-group">
          <label for="exampleInputEmail1">Nome utente</label>
          <input type="text"  name="nomeutente" class="form-control" id="username" aria-describedby="emailHelp" placeholder="Inserisci Nome utente">
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Indirizzo Email</label>
          <input type="email" name="email"  class="form-control" id="email" aria-describedby="emailHelp" placeholder="Inserisci e-mail">
        </div>
        <div class="form-group">
          <label for="exampleInputEmail1">Password</label>
          <input type="password" name="password" id="password"  class="form-control" aria-describedby="emailHelp" placeholder="Inserisci Password">
        </div>
        <div class="col-md-12 text-center mb-3">
          <button id="signup" type="submit" onclick="Registrazione()" class=" btn btn-block mybtn btn-primary tx-tfm">Registrati</button>
        </div>
        <div class="col-md-12 ">
          <div class="form-group">
            <p class="text-center"><a href="/FantaApp/auth" id="signin">Hai già un account?</a></p>
          </div>
        </div>
      </form>
  </div>
  </div>
  </div>
  </div>';
      echo $content;
      $App->Close();
	}
?>







