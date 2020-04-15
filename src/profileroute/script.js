function Submit(){
	obj = $( "#profileForm" ).serializeToJSON({
	});
	var jsonString = JSON.stringify(obj);
    let myForm = document.getElementById('profileForm');
    let formData = new FormData(myForm);
    formData.append("tabella", "UtentiAggiuntiva");
    let jsonjson = JSON.stringify(formData);
    $.ajax({
      type: "POST",
      url: "/FantaApp/profileroute/Edit.php",
      data: jsonjson,
      processData: false,
      contentType: "application/json; charset=utf-8",
      dataType: "json",
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
$( document ).ready(function() {
    $('.countrypicker').countrypicker();
});    