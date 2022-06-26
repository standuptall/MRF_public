<?php
	require_once('FantaApp.php');
	require_once('Utils.php');
	require_once('MenuLib.php');
    $lib = new MenuLib();
	$App = FantaApp::getSingleTon();
    $App->Init(dirname(__file__));
    echo Utils::loadtext(dirname(__FILE__)."/menumodal.html");
    echo Utils::loadtext(dirname(__FILE__)."/ricettalistmodal.html");
    echo Utils::loadtext(dirname(__FILE__)."/ricettamodal.html");
    echo Utils::loadtext(dirname(__FILE__)."/elencoingredientimodal.html");
    echo Utils::loadtext(dirname(__FILE__)."/dispensamodal.html");
    echo Utils::loadtext(dirname(__FILE__)."/listaspesamodal.html");
    echo Utils::loadtext(dirname(__FILE__)."/crealistaspesamodal.html");
    echo Utils::loadtext(dirname(__FILE__)."/controlloingredientimodal.html");
    echo Utils::loadtext(dirname(__FILE__)."/propostalistmodal.html");
    $contentprint = '';
    $contentprint .=  '
  <div class="container">
  <nav class="navbar navbar-expand navbar-light bg-light " style="overflow: auto;">
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item m-1">
        <button class="btn btn-success" onclick="OpenDispensa()" ><h5 class="fas fa-archive"></h2></button>
      </li>
      <li class="nav-item m-1">
        <button class="btn btn-warning" onclick="BuildLista()" ><h5 class="fas fa-clipboard-list"></h2></button>
      </li>
      <li class="nav-item m-1">
        <button class="btn btn-primary" onclick="OpenLista()" ><h5 class="fas fa-shopping-cart"></h2></button>
      </li>
      <li class="nav-item m-1">
        <button class="btn btn-primary" onclick="OpenControllo()" ><h5 class="fas fa-binoculars"></h2></button>
      </li>
      <li class="nav-item m-1">
        <button class="btn btn-primary" onclick="OpenAggiorna()" ><h5 class="fas fa-sync-alt"></h2></button>
      </li>
      <li class="nav-item m-1">
        <button class="btn btn-success" onclick="OpenProposte()" ><h5 class="fas fa-magic"></h2></button>
      </li>
      <li class="nav-item m-1">
        <button class="btn btn-primary" onclick="OpenListaFrequenti()" ><h5 class="fas fa-shopping-cart"></h2></button>
      </li>
    </ul>
  </div>
</nav>
<!--
	<div class="row">
    	<div class="col-3">
			</div>
    	<div class="col-3">
            </div>
    	<div class="col-3">
            </div>
    	<div class="col-3">
            </div>
    </div>
    
--!>
        <hr>
	<div class="header-navigation border border-dark">
    	<div class="row">
        	<div class="col-3">
            	<a href=/FantaApp/menu?from={backmonth}><i class="fas fa-chevron-circle-left"></i></a>
            </div>
        	<div class="col-6">
            	{mese} {anno}
            </div>
        	<div class="col-3">
            	<a href=/FantaApp/menu?from={forwardmonth}><i class="fas fa-chevron-circle-right"></i></a>
            </div>
        </div>
    </div>
	<div class="header-navigation border border-dark">
    	<div class="row">
        	<div class="col-3">
            	<a href=/FantaApp/menu?from={backweek}><i class="fas fa-chevron-circle-left"></i></a>
            </div>
        	<div class="col-6">
            	Settimana
            </div>
        	<div class="col-3">
            	<a href=/FantaApp/menu?from={forwardweek}><i class="fas fa-chevron-circle-right"></i></a>
            </div>
        </div>
    </div>';
    if (isset($_GET["from"]))
		$date = new DateTime($_GET["from"],new DateTimeZone("Europe/Rome"));
    else
		$date = new DateTime("now",new DateTimeZone("Europe/Rome"));
    $datebackmonth = new DateTime($date->format("Y-m-d"));
    $datebackmonth->sub(new DateInterval('P1M'));
    $dateforwardmonth = new DateTime($date->format("Y-m-d"));
    $dateforwardmonth->add(new DateInterval('P1M'));
    $datebackweek = new DateTime($date->format("Y-m-d"));
    $datebackweek->sub(new DateInterval('P7D'));;
    $dateforwardweek = new DateTime($date->format("Y-m-d"));
    $dateforwardweek->add(new DateInterval('P7D'));;
    $contentprint = str_replace("{backmonth}",$datebackmonth->format("Ymd"),$contentprint);
    $contentprint = str_replace("{backweek}",$datebackweek->format("Ymd"),$contentprint);
    $contentprint = str_replace("{forwardmonth}",$dateforwardmonth->format("Ymd"),$contentprint);
    $contentprint = str_replace("{forwardweek}",$dateforwardweek->format("Ymd"),$contentprint);
    $mese = $date->format("F");
    $anno = $date->format("Y");
    $contentprint = str_replace("{mese}",$mese,$contentprint);
    $contentprint = str_replace("{anno}",$anno,$contentprint);
    $contentprint = str_replace("{now}",$date->format("Ymd"),$contentprint);
    
    $dayofweek = $date->format("l");
    $interval = new DateInterval('P1D');
    $day = $date->format("d");
    switch(strtolower($dayofweek)){
    	case "monday":
        	$interval = new DateInterval('P0D');
        	break;
        case "tuesday":
        	$interval = new DateInterval('P1D');
            break;
        case "wednesday":
        	$interval = new DateInterval('P2D');
            break;
        case "thursday":
        	$interval = new DateInterval('P3D');
            break;
        case "friday":
        	$interval = new DateInterval('P4D');
            break;
        case "saturday":
        	$interval = new DateInterval('P5D');
            break;
        case "sunday":
        	$interval = new DateInterval('P6D');
            break;
    }
    $olddate = new DateTime("now",new DateTimeZone("Europe/Rome"));
    $date->sub($interval);
    
    //echo $dayofweek . ' '.$day  //es. Monday 22
    for ($i = 0; $i<7; $i++) {
    	$newdate = new DateTime($date->format('Y-m-d'));
    	date_add($newdate, date_interval_create_from_date_string($i.' days'));
        $newdayofweek = $newdate->format("l");
    	$newday = $newdate->format("d");
    	$newmonth = $newdate->format("F");
        $pasti = $lib->GetMealByDay($newdate);
        if ($newdate->format('Y-m-d') == $olddate->format('Y-m-d'))
        {
        	$pre = "<strong><u>";
          	$post = "</u></strong>";
            $bg = "bg-info";
        }
        else
        {
        	$pre = "";
          	$post = "";
            $bg = "";
        }
          
 		$contentprint .= '
    <div class="row text-center mb-1 "><div class="col-12">
      <div class="card " style="width: 100%;">
        <div class="card-body '.$bg.' ">
          <h5 class="card-title">'.$pre.$newdayofweek.' '.$newday.', '.$newmonth.$post.'</h5>';
          $cena = false;
          $pranzo = false;
          foreach($pasti as $pasto)
          {
          	$PC = $pasto->pranzocena ? "Cena" : "Pranzo";
            $PCINT = $pasto->pranzocena ? 1 : 0;
            if($PC == "Cena")
            	$cena = true;
            if($PC == "Pranzo")
            	$pranzo = true;
            $contentprint .= '<p class="card-text no-center"><span class="badge badge-primary">'.$PC.'</span>
            	<span class="'.$pasto->ingredientipresenti.'"><a draggable=true ondragstart="drag(event)"  onclick="EditMenu(\''.$pasto->data.'\')">'.$pasto->nomericetta.'</a></span><button class="btn" onclick="DeleteMeal(\''.$pasto->data.'\','.$PCINT.')"><i class="fas fa-trash-alt"></i></button></p>';
          }
          
          if (!$pranzo)
          {  
          	$contentprint .= '<p class="card-text no-center"><span class="badge badge-primary">Pranzo</span>
            	<span ondragover="allowDrop(event)" ondrop="drop(event)" ><a onclick="EditMenu(\''.$newdate->format("Y-m-d").'\')">Aggiungi</a></span></p>';
          }
          if (!$cena)
          {
            $contentprint .= '<p class="card-text no-center"><span class="badge badge-primary">Cena</span>
            	<span ondragover="allowDrop(event)" ondrop="drop(event)" ><a onclick="EditMenu(\''.$newdate->format("Y-m-d").'\')">Aggiungi</a></span></p>';
          }
          $contentprint .= '
        </div></div></div>
</div>';
		
    }
    echo $contentprint;
		
    $App->Close();
?>