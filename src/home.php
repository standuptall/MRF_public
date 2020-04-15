<?php
	require_once('FantaApp.php');
	require_once('Utils.php');
	$App = FantaApp::getSingleTon();
    $App->Init();
    echo '<div class="page-header" data-parallax="true" style="
  background-repeat: no-repeat;
  background-attachment: fixed;
  background-position: center; background-image: url(\'./etc/landing.jpg\');">
    <div class="filter"></div>
    <div class="container">
        <div class="motto text-center text-light">
            <h1>Fantacalcio</h1>
            <h3>Idea bellissima da sviluppare</h3>
            <br />
            <a href="/FantaApp/fantacalcio" class="btn btn-outline-light btn-round">Vai</a>
        </div>
    </div>
</div>
    <div class="section landing-section">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mr-auto ml-auto">
                    <h2 class="text-center">Contattaci</h2>
                    <form class="contact-form">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Nome</label>
                                <div class="input-group" [ngClass]="{\'input-group-focus\':focus===true}">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="nc-icon nc-single-02"></i></span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Nome" (focus)="focus=true" (blur)="focus=false" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Email</label>
                                <div class="input-group" [ngClass]="{\'input-group-focus\':focus1===true}">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">  <i class="nc-icon nc-email-85"></i></span>
                                    </div>
                                    <input type="text" class="form-control" placeholder="Email" (focus)="focus1=true" (blur)="focus1=false" >
                                </div>
                            </div>
                        </div>
                        <label>Messaggio</label>
                        <textarea class="form-control" rows="4" placeholder="Chiedi supporto"></textarea>
                        <div class="row">
                            <div class="col-md-4 mr-auto ml-auto">
                                <button class="btn btn-danger btn-lg btn-fill">Invia</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
';
    $App->Close();
?>