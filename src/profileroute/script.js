function Submit(event){
    let myForm = document.getElementById('profileForm');
    $("profileForm").serializeObject();
    let formData = new FormData(myForm);
    formData.append("tabella", "UtentiAggiuntiva");    
    let jsonjson = JSON.stringify($("#profileForm").serializeObject());//JSON.stringify(formData);
    $.ajax({
      type: "POST",
      url: "http://cantirsi.altervista.org/FantaApp/api/profile",
      data: jsonjson,
      processData: false,
      contentType: "application/json; charset=utf-8",
      dataType: "json",
      success: function(msg)
      {
        toastr.info(lbprofilo_salvato);
      },
      error: function(err,ytv)
      {
        toastr.error(err.responseText);
      }
    });
}
$( document ).ready(function() {
	document.querySelector("#submit").addEventListener("click", function(event) {
                     event.preventDefault();
                     Submit(event);
            }, false);
	if (profileData)
    {
      var profileObj = JSON.parse(profileData);
      document.getElementById('lingua').value = profileObj.cdlingua;
      document.getElementById('nome').value = profileObj.nome;
      document.getElementById('cognome').value = profileObj.cognome;
      document.getElementById('localita').value = profileObj.localita;
      document.getElementById('telefono').value = profileObj.telefono;
      document.getElementById('cdnazione').value = profileObj.cdnazione;
      document.getElementById('mondo').value = profileObj.idmondo;
      $('.avatar').attr("src",profileObj.immagine);
      $('.countrypicker').countrypicker();
    }
});  

function SendMessage(event) {
		  event.preventDefault();
          formData = $("#sendmessagemodalform").serializeObject();
          console.log(formData);
          jsonjson = JSON.stringify(formData);
          console.log(jsonjson);
          $.ajax({
        	type: "POST",
            url: "/FantaApp/api/messages",
			data: jsonjson,
            processData: false,
            contentType: "application/json",
	     	success: function(msg)
            {
              toastr.success("messaggio inviato");
            },
            error: function(err)
            {
            	toastr.error(err);
            }
          });

}