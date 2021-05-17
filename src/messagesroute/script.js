var selectedElement = null;
function onMessageClick(received,msg){
	msg.classList.add("active");
    if (selectedElement)
    	selectedElement.classList.remove("active");
    selectedElement = msg;
	id = msg.getAttribute("id");
    var idmsg = id.substring(1,id.length  );
    if (received){
    	var collection = JSON.parse(msgreceivedcollection);
        //$('#v-ricevuti').css("display", "block");
        //$('#v-inviati').css("display", "none");
    }
    else{ 
    	var collection = JSON.parse(msgsentcollection);
        //$('#v-inviati').css("display", "block");
        //$('#v-ricevuti').css("display", "none");
    }
    var found = collection.find(function(element) {
      return element.id==idmsg;
    });
    var tit = document.getElementById("title");
    var con = document.getElementById("content");
	tit.innerHTML = found.oggetto;
    con.innerHTML = found.contenuto;
}
$( document ).ready(function() {
    $('.chat_list').click(function() {
    	console.log(this.id);
        });
});