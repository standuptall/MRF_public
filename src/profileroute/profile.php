<?php
	require_once("FantaApp.php");
	require_once("Utils.php");
    $app = FantaApp::GetSingleTon();
    $app->Init(dirname(__file__));
    $id = $app->userInfo["ID"];
    $obj = $app->Sql()->Fetch("ProfiliAggiuntiva"," ID=".$id);
    /****************mondi--->*********************/
    $mondi = $app->Sql()->Fetch("Mondi");
    array_unshift($mondi,array("ID"=>"0","Nome"=>"Nessuno"));
    $mondiselect = '<div class="form-group">
    <label for="mondi">Mondo</label>
    <select class="form-control" id="mondi" value="'.$obj[0]["IdMondo"].'">';
    foreach($mondi as $mondo){
    	$mondiselect = $mondiselect . '<option value="'.$mondo["ID"].'">'.$mondo["Nome"].'</option>';
    }
    $mondiselect = $mondiselect . '</select>
  </div>';
    $obj[0]["mondiselect"] = $mondiselect;
    /****************<<<---mondi*********************/
    /****************nazioni--->*********************/
    $obj[0]["nazioniselect"] = '
    <div class="form-group">
      <label for="nazioni">Nazionalità</label> 
      <select id="nazioni" class="form-control countrypicker" data-flag="true" ></select>    	
    </div>';
    /****************<<<---nazioni*********************/
    
    
    
    
    
    $content = '
<div class="container bootstrap snippet">
    <div class="row">
  		<div class="col-sm-3"><!--left col-->
			<div class="text-center">
				<img src="{{immagine}}" class="avatar img-circle img-thumbnail" alt="avatar">
				<h6>Cambia foto profilo</h6>
				<input type="file" class="text-center center-block file-upload">
			</div>
			<br>
			<ul class="list-group">
			  <li class="list-group-item text-muted">Activity <i class="fa fa-dashboard fa-1x"></i></li>
			  <li class="list-group-item text-right"><span class="pull-left"><strong>Condivisioni</strong></span> 125</li>
			  <li class="list-group-item text-right"><span class="pull-left"><strong>Likes</strong></span> 13</li>
			  <li class="list-group-item text-right"><span class="pull-left"><strong>Post</strong></span> 37</li>
			  <li class="list-group-item text-right"><span class="pull-left"><strong>Followers</strong></span> 78</li>
			</ul> 
        </div><!--/col-3-->
    	<div class="col-sm-9">
          <div class="tab-content">
            <div class="tab-pane active" id="home">
                <hr>
                  <form class="form" id="profileForm">
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                              <label for="first_name"><h4>Nome</h4></label>
                              <input type="text" class="form-control" name="first_name" value="{{nome}}" id="first_name" placeholder="first name" title="inserisci nome">
                          </div>
                      </div>
                      <div class="form-group">
                          
                          <div class="col-xs-6">
                            <label for="last_name"><h4>Cognome</h4></label>
                              <input type="text" class="form-control" name="last_name" id="last_name" value="{{cognome}}" placeholder="last name" title="inserisci cognome">
                          </div>
                      </div>
          
                      <div class="form-group">
                          <div class="col-xs-6">
                             <label for="mobile"><h4>Telefono</h4></label>
                              <input type="text" class="form-control" name="mobile" id="mobile" value="{{telefono}}" placeholder="inserisci numero di telefono" title="enter your mobile number if any.">
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="col-xs-6">
                              {{mondiselect}}
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="col-xs-6">
                              {{nazioniselect}}
                          </div>
                      </div>
                      <div class="form-group">
                          <div class="col-xs-6">
                              <label for="location"><h4>Località</h4></label>
                              <input type="text" class="form-control" id="location" placeholder="ovunque" value="{{localita}}" title="enter a location">
                          </div>
                      </div>
                      <div class="form-group">
                           <div class="col-xs-12">
                                <br>
                              	<button class="btn btn-lg btn-success" onclick="Submit()" type="submit"><i class="glyphicon glyphicon-ok-sign"></i> Salva</button>
                               	<button class="btn btn-lg" type="reset"><i class="glyphicon glyphicon-repeat"></i> Reset</button>
                            </div>
                      </div>
              	</form>
              
              <hr>
              
             </div><!--/tab-pane-->
          </div><!--/tab-content-->

        </div><!--/col-9-->
    </div><!--/row-->
</div>
                                                  ';
    
    $content = Utils::RenderContent($content,$obj[0]);
    echo $content;
    $app->Close();
?>