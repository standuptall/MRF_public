function handle(error){
	if (error.status==403)
    	alert("non hai i privilegi necessari per effettuare l'operazione");
}
function AddRifornimento(){
    $('#rifornimentimodal .modal-footer button').on('click', function(event) {
      var $button = $(event.target);

      $(this).closest('.modal').one('hidden.bs.modal', function() {
        if ($button[0].type=="submit"){
          let myForm = document.getElementById('rifornimentimodalform');
          let formData = {
          	data: $('#rifornimentimodalform').find('[name="data"]').val(),
          	importo: $('#rifornimentimodalform').find('[name="importo"]').val(),
          	tachimetro: $('#rifornimentimodalform').find('[name="tachimetro"]').val(),
          	costo: $('#rifornimentimodalform').find('[name="costo"]').val(),
          	residuo: $('#rifornimentimodalform').find('[name="residuo"]').val()
          };
          $.ajax({
        	type: "POST",
            url: "/FantaApp/api/rifornimenti",
			data: JSON.stringify(formData),
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
    var date = today.getFullYear()+'-'+("0" + (today.getMonth()+1)).slice(-2)+'-'+today.getDate();
    $('input[name$="data"]').val(date);
    $('#rifornimentimodal').modal('show');
}
function EditRifornimento(id){
	$.ajax({
        	type: "GET",
            url: "/FantaApp/api/rifornimenti/"+ id,
			data: "",
	     	success: function(msg)
            {
              console.log(msg);
            	
              var obj = JSON.parse(msg); 
              $('input[name$="data"]').val(obj[0].data);
              $('input[name$="importo"]').val(obj[0].importo);
              $('input[name$="tachimetro"]').val(obj[0].tachimetro);
              $('input[name$="costo"]').val(obj[0].costo);
              $('input[name$="residuo"]').val(obj[0].residuo);
            },
            error: function(err)
            {
            	handle(err);
            }
          });
	$('#rifornimentimodal .modal-footer button').on('click', function(event) {
      var $button = $(event.target);

      $(this).closest('.modal').one('hidden.bs.modal', function() {
        if ($button[0].type=="submit"){
          let myForm = document.getElementById('rifornimentimodalform');
          let formData = {
          	data: $('#rifornimentimodalform').find('[name="data"]').val(),
          	importo: $('#rifornimentimodalform').find('[name="importo"]').val(),
          	tachimetro: $('#rifornimentimodalform').find('[name="tachimetro"]').val(),
          	costo: $('#rifornimentimodalform').find('[name="costo"]').val(),
          	residuo: $('#rifornimentimodalform').find('[name="residuo"]').val()
          };
          $.ajax({
        	type: "PUT",
            url: "/FantaApp/api/rifornimenti/"+id,
			data: JSON.stringify(formData),
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
    $('#rifornimentimodal').modal('show');
}
function DeleteRifornimento(id){
	if (confirm("Vuoi davvero eliminare il record?")){
    	$.ajax({
        	type: "DELETE",
            url: "/FantaApp/api/rifornimenti/"+id,
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
function SetAsFirst(id,event){
	$.ajax({
        	type: "PUT",
            url: "/FantaApp/api/rifornimenti/"+id+"?first=1",
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
    
}
function SetAsLast(id,event){
	$.ajax({
        	type: "PUT",
            url: "/FantaApp/api/rifornimenti/"+id+"?last=1",
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
}

function DownloadExcel(){
	$.ajax({
        	type: "GET",
            url: "/FantaApp/api/rifornimenti?excel=1",
            processData: false,
            contentType: false,
	     	success: function(msg)
            {
              var obj = JSON.parse(msg);
              var str = "";
              var first = obj.data[0];
              for (const property in first) {
                str += `${property};`;
              }
              str += "\r\n";
              str += obj.data.map(i=>{
              		var rrw = "";
              		for (const property in i) {
                      rrw += `${i[property]};`;
                    }
              		rrw += "\r\n";
                    return rrw;
              	});
                var element = document.createElement('a');
                element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(str));
                element.setAttribute('download', "rifornimenti.csv");

                element.style.display = 'none';
                document.body.appendChild(element);

                element.click();

                document.body.removeChild(element);
            },
            error: function(err)
            {
            	handle(err);
            }
          });
}
$(document).ready(function(){
	//-------------------------CHILOMETRI
	$.ajax({
      type: "GET",
      url: "/FantaApp/api/rifornimenti?km",
      processData: false,
      contentType: false,
      success: function(msg)
      {
        $('.km').html(msg+" Km");
      },
      error: function(err)
      {
        handle(err);
      }
    });
    //-------------------------CONSUMO
    $.ajax({
      type: "GET",
      url: "/FantaApp/api/rifornimenti?consumo",
      processData: false,
      contentType: false,
      success: function(msg)
      {
        $('.consumo').html(msg+" L/100km");
      },
      error: function(err)
      {
        handle(err);
      }
    });
    //-------------------------COSTO
    $.ajax({
      type: "GET",
      url: "/FantaApp/api/rifornimenti?costo",
      processData: false,
      contentType: false,
      success: function(msg)
      {
        $('.costo').html(msg+" €");
      },
      error: function(err)
      {
        handle(err);
      }
    });
    //-------------------------COSTO MEDIO
    $.ajax({
      type: "GET",
      url: "/FantaApp/api/rifornimenti?costomedio",
      processData: false,
      contentType: false,
      success: function(msg)
      {
        $('.costomedio').html(msg+" €/100Km");
      },
      error: function(err)
      {
        handle(err);
      }
    });
    //-------------------------CHILOMETRI NEL RANGE
    $.ajax({
      type: "GET",
      url: "/FantaApp/api/rifornimenti?kmtotali",
      processData: false,
      contentType: false,
      success: function(msg)
      {
        $('.kmtotali').html(msg+" Km");
      },
      error: function(err)
      {
        handle(err);
      }
    });
});
