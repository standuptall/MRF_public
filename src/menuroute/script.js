$( document ).ready(function(){
	$('[data-toggle="popover"]').popover();
	$('#ricettalistmodal').on('show.bs.modal', function (e) {
      RicettaList();
    });    
    //aggiungo evento salvataggio ricetta
    $('#ricettamodal .modal-footer button').on('click', function(event) 
    {
    	$('#ricettamodal').modal('hide');
    	var $button = $(event.target);
        if ($button[0].type=="submit")
        {
          let myForm = document.getElementById('ricettamodalform');
          var obj = new Object();
          obj.nome = $('#ricettamodalform').find('[name="nome"]').val();
          obj.descrizione = $('#ricettamodalform').find('[name="descrizione"]').val();
          obj.ingredienti = new Array(6);
          obj.ingredienti[0] = {nome : $('#ricettamodalform').find('[name="ingrediente1"]').val()};
          obj.ingredienti[1] = {nome : $('#ricettamodalform').find('[name="ingrediente2"]').val()};
          obj.ingredienti[2] = {nome : $('#ricettamodalform').find('[name="ingrediente3"]').val()};
          obj.ingredienti[3] = {nome : $('#ricettamodalform').find('[name="ingrediente4"]').val()};
          obj.ingredienti[4] = {nome : $('#ricettamodalform').find('[name="ingrediente5"]').val()};
          obj.ingredienti[5] = {nome : $('#ricettamodalform').find('[name="ingrediente6"]').val()};
          obj.ID = $('#ricettamodalform').find('[name="ID"]').val();
          if (obj.ID>0)
          	urlx = "/FantaApp/api/ricetta/"+obj.ID;
          else
          	urlx = "/FantaApp/api/ricetta";
          objson = JSON.stringify(obj);
          $.ajax({
            type: "POST",
            url: urlx,
            data: objson,
            processData: false,
            contentType: "json",
            success: function(msg)
            {
              RicettaList();
            },
            error: function(err)
            {
              handle(err);
            }
          });
        }
      });
      /*
      $('#menumodal').modal({backdrop: 'static', keyboard: false});
      $('#ricettalistmodal').modal({backdrop: 'static', keyboard: false});
      $('#ricettamodal').modal({backdrop: 'static', keyboard: false});
      $('#elencoingredientimodal').modal({backdrop: 'static', keyboard: false});
      $('#dispensamodal').modal({backdrop: 'static', keyboard: false});
      $('#listaspesamodal').modal({backdrop: 'static', keyboard: false});
      $('#crealistaspesamodal').modal({backdrop: 'static', keyboard: false});
      $('#menumodal').modal("hide");
      $('#ricettalistmodal').modal("hide");
      $('#ricettamodal').modal("hide");
      $('#elencoingredientimodal').modal("hide");
      $('#dispensamodal').modal("hide");
      $('#listaspesamodal').modal("hide");
      $('#crealistaspesamodal').modal("hide");*/
      $.fn.modal.prototype.constructor.Constructor.Default.backdrop = 'static';
	  $.fn.modal.prototype.constructor.Constructor.Default.keyboard =  false;
});
function dynamicEventLoad()
{
	$('.form-control-range').on('input',function () {
        $(this).parent().find('.form-control-number').val($(this).val());
        $(this).parent().find('.form-control-number').change();
    });
	$('.form-control-number').on('input',function () {
        $(this).parent().find('.form-control-range').val($(this).val());
    });
    $('.form-control-number').on('change',function () {
        console.log("ciao");
    });
}
function handle(error){
	if (error.status==403)
    	alert("non hai i privilegi necessari per effettuare l'operazione");
}
function EditMenu(date){
    $('#menumodal').find('.modal-title').html(date);
    Blank("pranzo");Blank("cena");
	date = date.replace("-", "").replace("-", "");
    globaldata = date;
	$.ajax({
        	type: "GET",
            url: "/FantaApp/api/menu/"+ date,
			data: "",
	     	success: function(msg)
            {
              var obj = JSON.parse(msg); 
              obj.forEach(function(c){
              	LoadPasto(c);
              });
            },
            error: function(err)
            {
            	handle(err);
            }
          });
	$('#menumodal .modal-footer button').on('click', function(event) {
      var $button = $(event.target);
      $(this).closest('.modal').one('hidden.bs.modal', function() {
        if ($button[0].type=="submit"){
          if (pranzo)
          {
          	pranzo.idricetta = pranzo.ID;
          	let js = JSON.stringify(pranzo);
            $.ajax({
              type: "POST",
              url: "/FantaApp/api/menu/"+globaldata.replace("-", "").replace("-", ""),
              data: js,
              processData: false,
              contentType: "json",
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
          if (cena)
          {
          	cena.idricetta = cena.ID;
          	let js = JSON.stringify(cena);
            $.ajax({
              type: "POST",
              url: "/FantaApp/api/menu/"+globaldata.replace("-", "").replace("-", ""),
              data: js,
              processData: false,
              contentType: "json",
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
            
          /*
          var request = new XMLHttpRequest();
          request.open("POST", "/FantaApp/Add.php");
          request.send(formData);
          location.reload();*/
        }
      });
    });
    $('#menumodal').modal('show');
}
function DeleteMeal(date,pranzocena){
date = date.replace("-", "").replace("-", "");
	var objson = '{"pranzocena":'+pranzocena+'}';
	if (confirm("Vuoi davvero eliminare il record?")){
    	$.ajax({
        	type: "DELETE",
            url: "/FantaApp/api/menu/"+date,
			data: objson,
      		dataType: null,
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
function Blank(sel){
	for(cc=1;cc<=6;cc++)
    {
      if (cc==1)
			lb = "one";
		  if (cc==2)
			lb = "two";
		  if (cc==3)
			lb = "three";
		  if (cc==4)
			lb = "four";
		  if (cc==5)
			lb = "five";
		  if (cc==6)
			lb = "six";
      $('#'+sel).find('.ingredienti').find('.'+lb).find('input[type=text]').val("");
      $('#'+sel).find('.ingredienti').find('.'+lb).find('input[type=checkbox]').prop('checked', false);
      
      $('#'+sel).find(".ricetta").html("Aggiungi...");
      $('#'+sel).find(".ricetta-des").html("");
      $('#'+sel).find('.ingredienti').find('.'+lb).hide();
   }
}
function LoadPasto(c){
    var sel = "";
    if (c.pranzocena){
      sel = 'cena';
      cena = c;
    }
    else{
      sel = 'pranzo';
      pranzo = c;
    }
    $('#'+sel).find('.ricetta').html(c.nomericetta);
    $('#'+sel).find('.ricetta-des').html(c.descrizionericetta);
    var cc = 1;
    c.ingredienti.forEach(function(ing){
		  if (cc==1)
			lb = "one";
		  if (cc==2)
			lb = "two";
		  if (cc==3)
			lb = "three";
		  if (cc==4)
			lb = "four";
		  if (cc==5)
			lb = "five";
		  if (cc==6)
			lb = "six";
		  $('#'+sel).find('.ingredienti').find('.'+lb).find('input[type=text]').val(ing.nome);
		  $('#'+sel).find('.ingredienti').find('.'+lb).find('input[type=checkbox]').prop('checked', ing.presente);
		  $('#'+sel).find('.ingredienti').find('.'+lb).find('input[type=checkbox]').click(function(){
			ChangePresente(sel,ing.ID,$(this));
		  });
          
		  $('#'+sel).find('.ingredienti').find('.'+lb).show();
		  cc++;
		});
}
function DeleteRicetta(id){
	if (confirm("Vuoi davvero eliminare il record?")){
    	$.ajax({
        	type: "DELETE",
            url: "/FantaApp/api/ricetta/"+id,
			data: null,
      		dataType: null,
	     	success: function(msg)
            {
              	RicettaList();
            },
            error: function(err)
            {
            	handle(err);
            }
          });
    }
}
	var pranzo;
    var cena;
    var current;
    var globaldata;


function RicettaList(terms){
		var urlx = "/FantaApp/api/ricetta";
        if (typeof terms !== 'undefined')
        	urlx = "/FantaApp/api/ricetta?search="+terms;
    	$.ajax({
        	type: "GET",
            url: urlx,
			data: "",
            processData: false,
            contentType: false,
	     	success: function(msg)
            {
              var obj = JSON.parse(msg); 
              $('.ricetta-list').html("");
              obj.forEach(function(c){
              	$('.ricetta-list').html(				
				
                	$('.ricetta-list').html()+"<a class=\"list-group-item list-group-item-action\" id=\""+c.ID+"\" data-toggle=\"list\" >"+c.nome+"</a>");
              });
              
    			$('#ricettalistmodal').modal('show');
            },
            error: function(err)
            {
            	handle(err);
            }
          });
}
function Select(obj){
	obj.addClass("active");
}
function ChangePresente(pranzocena,id,elem){
	var obj;
    if (pranzocena=="pranzo")
    	obj = pranzo;
    if (pranzocena=="cena")
    	obj = cena;
    obj.ingredienti.forEach(function(c){
    	if (c.ID == id)
        {
        	c.presente = elem.prop('checked');
            return false;
        }
        return true;

    });
}
function ScegliRicetta(){
	idricetta = $('#ricettalistmodal').find('.active').prop("id");
    if (!idricetta)
      	return false;
    $.ajax({
      type: "GET",
      url: "/FantaApp/api/ricetta/"+idricetta,
      data: "",
      processData: false,
      contentType: false,
      success: function(msg)
      {
        var obj = JSON.parse(msg); 
        if(current=="cena")
          obj.pranzocena = true;
        Blank(current);
        LoadPasto(obj);
      },
      error: function(err)
      {
        handle(err);
      }
    });
}
function ModificaRicetta(){
	idricetta = $('#ricettalistmodal').find('.active').prop("id");
    if (!idricetta)
      	return false;
    $.ajax({
      type: "GET",
      url: "/FantaApp/api/ricetta/"+idricetta,
      data: "",
      processData: false,
      contentType: false,
      success: function(msg)
      {
        var obj = JSON.parse(msg); 
        $('#ricettamodalform').find('[name="ID"]').val(obj.ID);
        $('#ricettamodalform').find('[name="nome"]').val(obj.nomericetta);
        $('#ricettamodalform').find('[name="descrizione"]').val(obj.descrizionericetta);
        var i = 0;
        obj.ingredienti.forEach(function(v){
        	$('#ricettamodalform').find('[name="ingrediente'+(i+1)+'"]').val(obj.ingredienti[i].nome);
            i++;
        });
        $('#ricettamodal').modal("show");
      },
      error: function(err)
      {
        handle(err);
      }
    });
}
function SearchTerms(){
	var terms = $('#ricettalistmodal').find('[type="search"]').val();
    RicettaList(terms);
}
function OpenDispensa(){
	var urlx = "/FantaApp/api/dispensa";
    if (typeof terms !== 'undefined')
      urlx = "/FantaApp/api/dispensa"
    $.ajax({
      type: "GET",
      url: urlx,
      data: "",
      processData: false,
      contentType: false,
      success: function(msg)
      {
        var obj = JSON.parse(msg); 
        $('.dispensa-list').html("");
        obj.forEach(function(c){
          $('.dispensa-list').html(				
            $('.dispensa-list').html()+"<a class=\"list-group-item list-group-item-action\" id=\""+c.ID+"\" data-toggle=\"list\" >"+c.ingrediente+"<div class=\"delete-button\"><input type=\"range\" class=\"form-control-range\"><button class=\"btn\" onclick=\"DeleteFromDispensa("+c.ID+")\"><i class=\"fas fa-trash-alt\"></i></button><input type=\"number\" class=\"form-control-number\"></div></a>");
        });
		$('#dispensamodal').modal('show');
        dynamicEventLoad();
      },
      error: function(err)
      {
        handle(err);
      }
    });
    $.ajax({
      type: "GET",
      url: "/FantaApp/api/menu?costo=1&tipo=dispensa",
      data: "",
      processData: false,
      contentType: false,
      success: function(msg)
      {
      	var obj = JSON.parse(msg);
        var mancanti = obj.ingredientimancanti.length;
        var msg = obj.costo.toFixed(2)+"€";
        if (mancanti>0){
        	msg += " + "+mancanti+" senza prezzo";
            var ingredienti = obj.ingredientimancanti.join();
            $('.costo').attr("data-content",ingredienti);
        }
        $('.costo').html(msg);
      },
      error: function(err)
      {
        handle(err);
      }
    });
}
function DeleteFromDispensa(id,opendisp){
	var urlx = "/FantaApp/api/dispensa";
    if (typeof id !== 'undefined')
      urlx = "/FantaApp/api/dispensa/"+id;
    $.ajax({
      type: "DELETE",
      url: urlx,
      data: "",
      processData: false,
      contentType: false,
      success: function(msg)
      {
      	if (typeof opendisp !== 'undefined')
        	OpenControllo();
        else
        	OpenDispensa();
      },
      error: function(err)
      {
        handle(err);
      }
    });
}
function AggiungiDispensa(){
	var terms = $('#dispensamodal').find('input').val();
    var obj = new Object();
    obj.quantita = 0;
    obj.nome = terms;
    datax = JSON.stringify(obj);
    urlx = "/FantaApp/api/dispensa";
    $.ajax({
      type: "POST",
      url: urlx,
      data: datax,
      processData: false,
      contentType: "json",
      success: function(msg)
      {
        OpenDispensa();
		var terms = $('#dispensamodal').find('input').val("");
      },
      error: function(err)
      {
        handle(err);
      }
    });
}
function OrderDispensa(){
    urlx = "/FantaApp/api/dispensa?order=1"
    $.ajax({
      type: "GET",
      url: urlx,
      data: "",
      processData: false,
      contentType: false,
      success: function(msg)
      {
        var obj = JSON.parse(msg); 
        $('.dispensa-list').html("");
        obj.forEach(function(c){
          $('.dispensa-list').html(				
            $('.dispensa-list').html()+"<a class=\"list-group-item list-group-item-action\" id=\""+c.ID+"\" data-toggle=\"list\" >"+c.ingrediente+"<div class=\"delete-button\"><input type=\"range\" class=\"form-control-range\"><button class=\"btn\" onclick=\"DeleteFromDispensa("+c.ID+")\"><i class=\"fas fa-trash-alt\"></i></button><input type=\"number\" class=\"form-control-number\"></div></a>");
        });
		$('#dispensamodal').modal('show');
        dynamicEventLoad();
      },
      error: function(err)
      {
        handle(err);
      }
    });
    $.ajax({
      type: "GET",
      url: "/FantaApp/api/menu?costo=1&tipo=dispensa",
      data: "",
      processData: false,
      contentType: false,
      success: function(msg)
      {
      	var obj = JSON.parse(msg);
        var mancanti = obj.ingredientimancanti.length;
        var msg = obj.costo.toFixed(2)+"€";
        if (mancanti>0){
        	msg += " + "+mancanti+" senza prezzo";
            var ingredienti = obj.ingredientimancanti.join();
            $('.costo').attr("data-content",ingredienti);
        }
        $('.costo').html(msg);
      },
      error: function(err)
      {
        handle(err);
      }
    });
}
function OpenLista(){
	var urlx = "/FantaApp/api/listaspesa";
    $.ajax({
      type: "GET",
      url: urlx,
      data: "",
      processData: false,
      contentType: false,
      success: function(msg)
      {
        var obj = JSON.parse(msg); 
        $('.listaspesa-list').html("");
        obj.forEach(function(c){			
		  active = (c.prelevato > 0) ? "active" : "";
          $('.listaspesa-list').html(	
            $('.listaspesa-list').html()+"<a class=\"list-group-item list-group-item-action "+active+"\" id=\""+c.ID+"\" onclick=\"SetPrelevato($(this))\" ><span>"+c.ingrediente+"</span><button class=\"btn delete-button\" onclick=\"DeleteFromListaSpesa("+c.ID+")\"><i class=\"fas fa-trash-alt\"></i></button></a>");
        });
		$('#listaspesamodal').modal('show');
      },
      error: function(err)
      {
        handle(err);
      }
    });
    $.ajax({
      type: "GET",
      url: "/FantaApp/api/menu?costo=1&tipo=listaspesa",
      data: "",
      processData: false,
      contentType: false,
      success: function(msg)
      {
      	var obj = JSON.parse(msg);
        var mancanti = obj.ingredientimancanti.length;
        var msg = obj.costo.toFixed(2)+"€";
        if (mancanti>0){
        	msg += " + "+mancanti+" senza prezzo";
            var ingredienti = obj.ingredientimancanti.join();
            $('.costo').attr("data-content",ingredienti);
        }
        $('.costo').html(msg);
      },
      error: function(err)
      {
        handle(err);
      }
    });
}
function SetPrelevato(jobj){
	if (jobj.hasClass("active")){
    	val = 0;
    }
    else 
    	val = 1;
    idx = jobj.attr('id');
    if (idx < 0 )
    	return;
    urlx  = "/FantaApp/api/listaspesa/"+idx;
    var obj = new Object();
    obj.prelevato = val;
    obj.ingrediente = jobj.find('span').html();
    var objson = "{\"prelevato\":"+val+",\"ingrediente\":\""+obj.ingrediente+"\"}";
    //var objson = JSON.parse(obj); 
    $.ajax({
      type: "POST",
      url: urlx,
      data: objson,
      processData: false,
      contentType: "json",
      success: function(msg)
      {
        OpenLista();
      },
      error: function(err)
      {
        handle(err);
      }
    });
}
function AggiungiListaSpesa(){
	var terms = $('#listaspesamodal').find('input').val();
    var obj = new Array(1);
    obj[0] = new Object();
    obj[0].ingrediente = terms;
    obj[0].prelevato = 0;
    datax = JSON.stringify(obj);
    urlx = "/FantaApp/api/listaspesa";
    $.ajax({
      type: "POST",
      url: urlx,
      data: datax,
      processData: false,
      contentType: "json",
      success: function(msg)
      {
        OpenLista();
		var terms = $('#listaspesamodal').find('input').val("");
      },
      error: function(err)
      {
        handle(err);
      }
    });
}
function DeleteFromListaSpesa(id){
	var urlx = "/FantaApp/api/listaspesa";
    if (typeof id !== 'undefined')
      urlx = "/FantaApp/api/listaspesa/"+id;
    $.ajax({
      type: "DELETE",
      url: urlx,
      data: "",
      processData: false,
      contentType: false,
      success: function(msg)
      {
        OpenLista();
      },
      error: function(err)
      {
        handle(err);
      }
    });
}
function SalvaListaInDispensa(){
	if (confirm("Sei sicuro di voler salvare in dispensa?")){
      var urlx = "/FantaApp/api/listaspesa?savelist";
      $.ajax({
        type: "POST",
        url: urlx,
        data: "",
        processData: false,
        contentType: false,
        success: function(msg)
        {
          OpenAggiorna();
        },
        error: function(err)
        {
          handle(err);
        }
      });
    }
}
var ingdacomp;
function BuildLista(){
	var urlx = "/FantaApp/api/menu?ingredienti=1";
    $.ajax({
      type: "GET",
      url: urlx,
      data: "",
      processData: false,
      contentType: false,
      success: function(msg)
      {
        var obj = JSON.parse(msg); 
        ingdacomp = obj;
        $('.crealistaspesa-list').html("");
        obj.forEach(function(c){
          $('.crealistaspesa-list').html(				

            $('.crealistaspesa-list').html()+"<a class=\"list-group-item list-group-item-action\" id=\""+c.ID+"\" data-toggle=\"list\" >"+c.ingrediente+"<button class=\"btn\"><i class=\"fas fa-trash-alt\"></i></button></a>");
        });
        $('#crealistaspesamodal').modal("show");
      },
      error: function(err)
      {
        handle(err);
      }
    });
    $.ajax({
      type: "GET",
      url: "/FantaApp/api/menu?costo=1&tipo=dacomprare",
      data: "",
      processData: false,
      contentType: false,
      success: function(msg)
      {
      	var obj = JSON.parse(msg);
        var mancanti = obj.ingredientimancanti.length;
        var msg = obj.costo.toFixed(2)+"€";
        if (mancanti>0){
        	msg += " + "+mancanti+" senza prezzo";
            var ingredienti = obj.ingredientimancanti.join();
            $('.costo').attr("data-content",ingredienti);
        }
        $('.costo').html(msg);
      },
      error: function(err)
      {
        handle(err);
      }
    });
}
function CreaListaSpesa()
{
	if (!confirm("Sei sicuro di voler aggiungere gli ingredienti alla lista della spesa?"))
    	return;
    datax = JSON.stringify(ingdacomp);
    urlx = "/FantaApp/api/listaspesa";
    $.ajax({
      type: "POST",
      url: urlx,
      data: datax,
      processData: false,
      contentType: "json",
      success: function(msg)
      {
        
        $('#crealistaspesamodal').modal("hide");
      },
      error: function(err)
      {
        handle(err);
      }
    });
}
function OpenControllo(){
	var urlx = "/FantaApp/api/menu?controllo=1";
    $.ajax({
      type: "GET",
      url: urlx,
      data: "",
      processData: false,
      contentType: false,
      success: function(msg)
      {
        var obj = JSON.parse(msg); 
        $('.controllo-list').html("");
        obj.forEach(function(c){
          $('.controllo-list').html(				
            $('.controllo-list').html()+"<a class=\"list-group-item list-group-item-action\" id=\""+c.ID+"\" data-toggle=\"list\" >"+c.ingrediente+"<div class=\"delete-button\"><input type=\"range\" class=\"form-control-range\"><button class=\"btn\" onclick=\"DeleteFromDispensa("+c.ID+",1)\"><i class=\"fas fa-trash-alt\"></i></button><input type=\"number\" class=\"form-control-number\"></div></a>");
        });
		$('#controlloingredientimodal').modal('show');
        dynamicEventLoad();
      },
      error: function(err)
      {
        handle(err);
      }
    });
}

function OpenAggiorna(){
	if (confirm("Vuoi riaggiornare i menu della settimana in base al contenuto in dispensa?"))
    {
    	var urlx = "/FantaApp/api/menu?aggiorna=1";
        $.ajax({
        type: "POST",
        url: urlx,
        data: "",
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
}
function OpenProposte(){
	var urlx = "/FantaApp/api/menu?proposte=1";
    $.ajax({
      type: "GET",
      url: urlx,
      data: "",
      processData: false,
      contentType: false,
      success: function(msg)
      {
        var obj = JSON.parse(msg); 
        $('.proposta-list').html("");
        obj.forEach(function(c){
          $('.proposta-list').html(				
            $('.proposta-list').html()+"<a class=\"list-group-item "+c.ingredientipresenti+" list-group-item-action\" id=\""+c.idricetta+"\" data-toggle=\"list\" >"+c.nomericetta+"<div class=\"delete-button\"><input type=\"range\" class=\"form-control-range\"><button class=\"btn\" onclick=\"DeleteFromDispensa("+c.ID+",1)\"><i class=\"fas fa-trash-alt\"></i></button><input type=\"number\" class=\"form-control-number\"></div></a>");
        });
		$('#propostalistmodal').modal('show');
        dynamicEventLoad();
      },
      error: function(err)
      {
        handle(err);
      }
    });
}