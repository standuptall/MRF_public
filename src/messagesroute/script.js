var selectedElement = null;
function onMessageClick(received,msg){
	msg.classList.add("active");
    if (selectedElement)
    	selectedElement.classList.remove("active");
    selectedElement = msg;
	id = msg.getAttribute("id");
    var idmsg = id.substring(1,id.length  );
    if (received)
    	var collection = JSON.parse(msgreceivedcollection);
    else 
    	var collection = JSON.parse(msgsentcollection);
    var found = collection.find(function(element) {
      return element.id==idmsg;
    });
    var tit = document.getElementById("title");
    var con = document.getElementById("content");
	tit.innerHTML = found.oggetto;
    con.innerHTML = found.contenuto;
}
$( document ).ready(function() {
    console.log( "ready!" );
});