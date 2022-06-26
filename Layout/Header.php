<?php
	require_once("FantaApp.php");
	function RenderHeader($user,$useragg){
    $active = 'active';
    $app = FantaApp::GetSingleTon();
    $lbHome = "Home";
    $lbGiocatori = $app->lb("giocatori");
    $lbProfili = $app->lb("profili");
    $lbContabNote = $app->lb("contabnote");
    $lbMenu = $app->lb("menu");
    $lbRifornimenti = $app->lb("rifornimenti");
    $lbCast = $app->lb("cast");
        
    	$content = '
    <header class="navbar navbar-expand-lg navbar-expand-md navbar-dark bg-primary sticky-top">
      <a class="navbar-brand" href="/FantaApp"><img style="width:50px;height:auto;" src="/FantaApp/etc/brand.png">'.$app->nomeApp.'</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item '.($app->pagina=="home" ? $active : "").'">
            <a class="nav-link" href="/FantaApp">Home</a>
          </li>
          <li class="nav-item '.($app->pagina=="giocatori" ? $active : "").'">
            <a class="nav-link" href="/FantaApp/giocatori">'.$lbGiocatori.'</a>
          </li>
          <li class="nav-item '.($app->pagina=="profiles" ? $active : "").'">
            <a class="nav-link" href="/FantaApp/profiles">'.$lbProfili.'</a>
          </li>
          <li class="nav-item '.($app->pagina=="contabnote" ? $active : "").'">
            <a class="nav-link" href="/FantaApp/contabnote">'.$lbContabNote.'</a>
          </li>
          <li class="nav-item '.($app->pagina=="menu" ? $active : "").'">
            <a class="nav-link" href="/FantaApp/menu">'.$lbMenu.'</a>
          </li>
          <li class="nav-item '.($app->pagina=="rifornimenti" ? $active : "").'">
            <a class="nav-link" href="/FantaApp/rifornimenti">'.$lbRifornimenti.'</a>
          </li>
          <li class="nav-item '.($app->pagina=="cast" ? $active : "").'">
            <a class="nav-link" href="/FantaApp/cast">'.$lbCast.'</a>
          </li>
        </ul>';
        if (!isset($user))
        	$content.= '<a class="btn btn-outline-light" href="/FantaApp/auth">
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
            $content.= '<div class="navbar-item">
            		<button class="btn" type="button">
                      <a class="text-dark " href="/FantaApp/messages"><i class="fas fa-envelope" ></i></a>
                    </button>
                  </div> ';
            $content.= '<div class=" navbar-item dropdown"><span>'.$nome.'</span>
                    <button class="btn dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-expanded="false">
                      <img src="'.$img.'" style="width:2em;height:auto;" class="img-thumbnail img-circle">
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                      <a class="dropdown-item" type="button" href="/FantaApp/profile">[[profilo]]</a>
                      <a class="dropdown-item" type="button" href="/FantaApp/signout">[[disconnetti]]</a>
                    </div>
                  </div>';
        }
        $content.= '
      </div>
    </header>';
    echo Utils::RenderContent($content,NULL,$app);

    }
?>