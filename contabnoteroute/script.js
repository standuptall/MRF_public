function handle(error){
	if (error.status==403)
    	alert("non hai i privilegi necessari per effettuare l'operazione");
}
function AddContabNote(){
    $('#contabnotemodal .modal-footer button').on('click', function(event) {
      var $button = $(event.target);

      $(this).closest('.modal').one('hidden.bs.modal', function() {
        if ($button[0].type=="submit"){
          let myForm = document.getElementById('contabnotemodalform');
          let formData = new FormData(myForm);
          formData.append("tabella", "ContabNote");
          $.ajax({
        	type: "POST",
            url: "/FantaApp/contabnoteroute/Add.php",
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
    var today = new Date();
    var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
    $('input[name$="data"]').val(date);
    $('#contabnotemodal').modal('show');
}
function EditContabNote(id){
	$.ajax({
        	type: "GET",
            url: "/FantaApp/api/contabnote/"+ id,
			data: "",
	     	success: function(msg)
            {
              console.log(msg);
            	
              var obj = JSON.parse(msg); 
              $('input[name$="data"]').val(obj[0].data);
              $('input[name$="descrizione"]').val(obj[0].descrizione);
              $('input[name$="localita"]').val(obj[0].localita);
              $('input[name$="importo"]').val(obj[0].importo);
              $('input[name$="tipospesa"]').val(obj[0].tipospesa);
              $('input[name$="dacontab"]').val(obj[0].dacontab);
              $('input[name$="debitore"]').val(obj[0].debitore);
            },
            error: function(err)
            {
            	handle(err);
            }
          });
	$('#contabnotemodal .modal-footer button').on('click', function(event) {
      var $button = $(event.target);

      $(this).closest('.modal').one('hidden.bs.modal', function() {
        if ($button[0].type=="submit"){
          let myForm = document.getElementById('contabnotemodalform');
          let formData = new FormData(myForm);
          formData.append("tabella", "ContabNote");
          formData.append("ID", id);
          $.ajax({
        	type: "POST",
            url: "/FantaApp/contabnoteroute/Edit.php",
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
    $('#contabnotemodal').modal('show');
}
function DeleteContabNote(id){
	if (confirm("Vuoi davvero eliminare il record?")){
    	where = "ID = "+id;
    	$.ajax({
        	type: "POST",
            url: "/FantaApp/contabnoteroute/Delete.php",
			data: "tabella=ContabNote&where=" + where,
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