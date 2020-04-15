<?php
	require_once("FantaApp.php");
	require_once("Utils.php");
    $app = FantaApp::GetSingleTon();
    $app->Init(dirname(__file__));
    $id = $app->userInfo["ID"];
    $obj["elencomessaggi"] = '';
    require_once("messagesroute/messagerepo.php");
    $messages = GetMessages($id);
    foreach($messages as $msg){
    	$obj["elencomessaggiricevuti"] .= '<a id="a'.$msg->id.'"onclick="onMessageClick(true,this)" href="#" class="list-group-item list-group-item-action flex-column align-items-start">
	  				<div class="d-flex w-100 justify-content-between">
                    	<h5 class="mb-1">'.$msg->oggetto.'</h5>
                    	<small>'.$msg->dataposta.'</small>
                    </div>
                    <p class="mb-1 text-truncate">'.$msg->contenuto.'</p>
                    	<small>'.$msg->mittente.'</small>
                 </a>';
    }
    $messages = GetMessagesSent($id);
    $obj["elencomessaggiinviati"] = "";
    foreach($messages as $msg){
    	$obj["elencomessaggiinviati"] .= '<a id="a'.$msg->id.'"onclick="onMessageClick(false,this)" href="#" class="list-group-item list-group-item-action flex-column align-items-start">
	  				<div class="d-flex w-100 justify-content-between">
                    	<h5 class="mb-1">'.$msg->oggetto.'</h5>
                    	<small>'.$msg->dataposta.'</small>
                    </div>
                    <p class="mb-1 text-truncate">'.$msg->contenuto.'</p>
                    	<small>'.$msg->mittente.'</small>
                 </a>';
    }
    $content = '<div class="container bootstrap snippet">
    <div class="row justify-content-center">
    	<div class="col-12" >
          <div class="tab-content">
            <div class="tab-pane active" id="home">
                <hr>
                  <div class="row "style="height: 85vh;  ">
                  <!--device-->
                    <div class="d-none d-sm-block col-md-1 col-lg-1">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                          <a class="nav-link active" id="v-ricevuti-tab" data-toggle="pill" href="#v-ricevuti" role="tab" aria-controls="v-ricevuti" aria-selected="true"><i class="fas fa-inbox"></i><small>Ricevuti</small>
                          <a class="nav-link" id="v-inviati-tab" data-toggle="pill" href="#v-inviati" role="tab" aria-controls="v-inviati" aria-selected="false"><i class="fas fa-sign-out-alt"></i><small>Inviati</small></a>
                        </div>
                    </div>
                    <div class="col-md-11 col-lg-11">
                    	<div class="row tab-pane fade show active" id="v-ricevuti" role="v-ricevuti" aria-labelledby="v-ricevuti-tab">
                          <div class="d-none d-sm-block col-md-4 col-lg-4">
                              <div class="list-group" style="max-height:85vh;overflow:scroll;">
                                {{elencomessaggiricevuti}}
                              </div>
                          </div>
                          <div class="bg-light col-sm-12 col-md-8 col-lg-8">
                              <h2 id="title"></h2>
                              <p class="lead" id="content"></p>
                          </div>
                       </div>
                       <div class="row tab-pane fade" id="v-inviati" role="v-inviati" aria-labelledby="v-inviati-tab">
                          <div class="d-none d-sm-block col-md-4 col-lg-4">
                              <div class="list-group" style="max-height:85vh;overflow:scroll;">
                                {{elencomessaggiinviati}}
                              </div>
                          </div>
                          <div class="bg-light col-sm-12 col-md-8 col-lg-8">
                              <h2 id="title"></h2>
                              <p class="lead" id="content"></p>
                          </div>
                       </div>
                     </div>
                  </div><!--row-->
                <hr>
            </div><!--/tab-pane-->
          </div><!--/tab-content-->
        </div><!--/col-9-->
    </div><!--/row-->
</div>';
    $content = Utils::RenderContent($content,$obj);
    echo $content;
    $app->Close();
?>