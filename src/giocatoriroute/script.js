function handle(error){
	if (error.status==403)
    	alert("non hai i privilegi necessari per effettuare l'operazione");
}
function AddGiocatore(){
    $('#giocatorimodal .modal-footer button').on('click', function(event) {
      var $button = $(event.target);

      $(this).closest('.modal').one('hidden.bs.modal', function() {
        if ($button[0].type=="submit"){
          let myForm = document.getElementById('giocatorimodalform');
          let formData = new FormData(myForm);
          formData.append("tabella", "Giocatori");
          $.ajax({
        	type: "POST",
            url: "/FantaApp/giocatoriroute/Add.php",
			data: formData,
            processData: false,
            contentType: false,
	     	success: function(msg)
            {
              location.reload();
            },
            error: function(err)
            {
            	handle(err);
            }
          });
          /*
          var request = new XMLHttpRequest();
          request.open("POST", "/FantaApp/Add.php");
          request.send(formData);
          location.reload();*/
        }
      });
    });
    $('#giocatorimodal').modal('show');
}
function EditGiocatore(id){
	$.ajax({
        	type: "GET",
            url: "/FantaApp/giocatoriroute/Get.php",
			data: "tabella=Giocatori&where=ID=" + id,
	     	success: function(msg)
            {
              console.log(msg);
            	
              var obj = JSON.parse(msg); 
              $('input[name$="Nome"]').val(obj[0].Nome);
            },
            error: function(err)
            {
            	handle(err);
            }
          });
	$('#giocatorimodal .modal-footer button').on('click', function(event) {
      var $button = $(event.target);

      $(this).closest('.modal').one('hidden.bs.modal', function() {
        if ($button[0].type=="submit"){
          let myForm = document.getElementById('giocatorimodalform');
          let formData = new FormData(myForm);
          formData.append("tabella", "Giocatori");
          formData.append("ID", id);
          $.ajax({
        	type: "POST",
            url: "/FantaApp/giocatoriroute/Edit.php",
			data: formData,
            processData: false,
            contentType: false,
	     	success: function(msg)
            {
              location.reload();
            },
            error: function(err)
            {
            	handle(err);
            }
          });
          /*
          var request = new XMLHttpRequest();
          request.open("POST", "/FantaApp/Add.php");
          request.send(formData);
          location.reload();*/
        }
      });
    });
    $('#giocatorimodal').modal('show');
}
function DeleteGiocatore(id){
	if (confirm("Vuoi davvero eliminare il record?")){
    	where = "ID = "+id;
    	$.ajax({
        	type: "POST",
            url: "/FantaApp/giocatoriroute/Delete.php",
			data: "tabella=Giocatori&where=" + where,
      		dataType: "html",
	     	success: function(msg)
            {
              	location.reload();
            },
            error: function(err)
            {
            	handle(err);
            }
          });
    }
}