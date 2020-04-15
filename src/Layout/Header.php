<?php
	require_once("FantaApp.php");
	function RenderHeader($user,$useragg){
    	$app = FantaApp::GetSingleTon();
    	echo '
    <header class="navbar navbar-expand-lg navbar-expand-md navbar-dark bg-primary sticky-top">
      <a class="navbar-brand" href="/FantaApp"><img style="width:50px;height:auto;" src="/FantaApp/etc/brand.png">'.$app->nomeApp.'</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="/FantaApp/giocatori">Giocatori <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Funzionalità 2</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Funzionalità 3</a>
          </li>
        </ul>';
        if (!isset($user))
        	echo '<a class="btn btn-outline-light" href="/FantaApp/auth">
                    Login
                  </a>';
        else 
        {
        	$img = "/FantaApp/etc/profiles/".$user["nomeutente"].".png";
            if (isset($useragg) && !empty($useragg["nome"])){
            	$nome = $useragg["nome"];
             }
             else{
             	$nome = $user["nomeutente"];
                $img = "/FantaApp/etc/profiles/".$user["nomeutente"].".png";
            }
            if (isset($useragg) && !empty($useragg["immagine"])){
                $img  = $useragg["immagine"];
            }else{
                $img = "/FantaApp/etc/empty_profile.png";
            }
            /*
            if (!file_exists($_SERVER["DOCUMENT_ROOT"].$img))
            	$img = "/FantaApp/etc/empty_profile.png";
        	*/
            echo '<div class="navbar-item">
            		<button class="btn" type="button">
                      <a class="text-dark " href="/FantaApp/messages"><i class="fas fa-envelope" ></i></a>
                    </button>
                  </div> ';
            echo '<div class=" navbar-item dropdown"><span>'.$nome.'</span>
                    <button class="btn dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-expanded="false">
                      <img src="'.$img.'" style="width:2em;height:auto;" class="img-thumbnail img-circle">
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                      <a class="dropdown-item" type="button" href="/FantaApp/profile">Profilo</a>
                      <a class="dropdown-item" type="button" href="/FantaApp/signout">Disconnetti</a>
                    </div>
                  </div>';
        }
        echo '
      </div>
    </header>';

    }
?>