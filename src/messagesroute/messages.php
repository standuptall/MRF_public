<?php
	require_once("FantaApp.php");
	require_once("Utils.php");
    $app = FantaApp::GetSingleTon();
    $app->Init(dirname(__file__));
    $id = $app->userInfo["ID"];
    $obj["elencomessaggiricevuti"] = '';
    require_once("messagesroute/messagerepo.php");
    $messages = GetConversations($id);
    foreach($messages as $msg){
    	$obj["elencomessaggiricevuti"] .= '<div class="chat_list" id="'.$msg['idprofilo'].'">
              <div class="chat_people" >
                <div class="chat_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
                <div class="chat_ib">
                  <h5>'.$msg['Profilo'].'<span class="chat_date">'.$msg['dataposta'].'</span></h5>
                  <p>'.$msg['testo'].'</p>
                </div>
              </div>
            </div>';
        /*
        '<a id="a'.$msg->id.'"onclick="onMessageClick(true,this)" href="#" class="list-group-item list-group-item-action flex-column align-items-start">
	  				<div class="d-flex w-100 justify-content-between">
                    	<h5 class="mb-1">'.$msg->oggetto.'</h5>
                    	<small>'.$msg->dataposta.'</small>
                    </div>
                    <p class="mb-1 text-truncate">'.$msg->contenuto.'</p>
                    	<small>'.$msg->mittente.'</small>
                 </a>';*/
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
    $content = '<div class="container">
<h3 class=" text-center">Messaging</h3>
<div class="messaging">
      <div class="inbox_msg">
        <div class="inbox_people">
          <div class="headind_srch">
            <div class="recent_heading">
              <h4>Recent</h4>
            </div>
            <div class="srch_bar">
              <div class="stylish-input-group">
                <input type="text" class="search-bar"  placeholder="Search" >
                <span class="input-group-addon">
                <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                </span> </div>
            </div>
          </div>
          <div class="inbox_chat">
            {{elencomessaggiricevuti}}
          </div>
        </div>
        <div class="mesgs">
          <div class="msg_history">
            <div class="incoming_msg">
              <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
              <div class="received_msg">
                <div class="received_withd_msg">
                  <p>Test which is a new approach to have all
                    solutions</p>
                  <span class="time_date"> 11:01 AM    |    June 9</span></div>
              </div>
            </div>
            <div class="outgoing_msg">
              <div class="sent_msg">
                <p>Test which is a new approach to have all
                  solutions</p>
                <span class="time_date"> 11:01 AM    |    June 9</span> </div>
            </div>
            <div class="incoming_msg">
              <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
              <div class="received_msg">
                <div class="received_withd_msg">
                  <p>Test, which is a new approach to have</p>
                  <span class="time_date"> 11:01 AM    |    Yesterday</span></div>
              </div>
            </div>
            <div class="outgoing_msg">
              <div class="sent_msg">
                <p>Apollo University, Delhi, India Test</p>
                <span class="time_date"> 11:01 AM    |    Today</span> </div>
            </div>
            <div class="incoming_msg">
              <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>
              <div class="received_msg">
                <div class="received_withd_msg">
                  <p>We work directly with our designers and suppliers,
                    and sell direct to you, which means quality, exclusive
                    products, at a price anyone can afford.</p>
                  <span class="time_date"> 11:01 AM    |    Today</span></div>
              </div>
            </div>
          </div>
          <div class="type_msg">
            <div class="input_msg_write">
              <input type="text" class="write_msg" placeholder="Type a message" />
              <button class="msg_send_btn" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
            </div>
          </div>
        </div>
      </div>
      
      
      <p class="text-center top_spac"> Design by <a target="_blank" href="#">Sunil Rajput</a></p>
      
    </div></div>';
    $content = Utils::RenderContent($content,$obj);
    echo $content;
    $app->Close();
?>