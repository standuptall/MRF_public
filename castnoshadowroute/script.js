</script>
<script type="text/javascript" src="castnoshadowroute/crypto-js/crypto-js.js"></script>
<script>
function copy() {
    var input = document.querySelector('#copy-input');
    input.select();
    input.setSelectionRange(0, 9999);
    try {
      var success = document.execCommand('copy');
      if (success) {
        // Change tooltip message to "Copied!"
      } else {
        // Handle error. Perhaps change tooltip message to tell user to use Ctrl-c
        // instead.
      }
    } catch (err) {
      // Handle error. Perhaps change tooltip message to tell user to use Ctrl-c
      // instead.
    }
}
	
function addPassword(){
    $('input[name$="Nome"]').val("");
    $('input[name$="descrizione"]').val("");
    $('input[name$="nomeutente"]').val("");
    $('input[name$="passwordx"]').val("");
    $('#passwordmodal .modal-footer button').unbind('click');
    $('#passwordmodal .modal-footer button').on('click', function(event) {
      var $button = $(event.target);
      $(this).closest('.modal').one('hidden.bs.modal', function() {
        if ($button[0].type=="submit"){
          let myForm = document.getElementById('passwordmodalform');
          const ret = encrypt($('#key').val(),$('input[name$="passwordx"]').val());
          const obj = JSON.stringify({
          	nome:$('input[name$="Nome"]').val(),
          	descrizione:$('input[name$="descrizione"]').val(),
          	nomeutente:$('input[name$="nomeutente"]').val(),
          	password:ret.crypt,
          	IV:ret.iv
          });
          $.ajax({
        	type: "POST",
            url: "/FantaApp/api/password",
			data: obj,
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
    $('#passwordmodal').modal('show');
}
	
function updatePassword(id){
    $('input[name$="Nome"]').val("");
    $('input[name$="descrizione"]').val("");
    $('input[name$="nomeutente"]').val("");
    $('input[name$="passwordx"]').val("");
    $('#passwordmodal .modal-footer button').unbind('click');
    $('#passwordmodal .modal-footer button').on('click', function(event) {
      var $button = $(event.target);
      $(this).closest('.modal').one('hidden.bs.modal', function() {
        if ($button[0].type=="submit"){
          let myForm = document.getElementById('passwordmodalform');
          const ret = encrypt($('#key').val(),$('input[name$="passwordx"]').val());
          const obj = JSON.stringify({
          	nome:$('input[name$="Nome"]').val(),
          	descrizione:$('input[name$="descrizione"]').val(),
          	nomeutente:$('input[name$="nomeutente"]').val(),
          	password:ret.crypt,
          	IV:ret.iv
          });
          $.ajax({
        	type: "PUT",
            url: "/FantaApp/api/password/"+id,
			data: obj,
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
    //$('#passwordmodal').modal('show');
}
function showPassword(id){
	updatePassword(id);
	$.ajax({
        	type: "GET",
            url: "/FantaApp/api/password?id="+ id,
	     	success: function(msg)
            {
              console.log(msg);
            	
              var obj = JSON.parse(msg); 
              $('input[name$="Nome"]').val(obj.nome);
              $('input[name$="descrizione"]').val(obj.descrizione);
              $('input[name$="nomeutente"]').val(obj.nomeutente);
              $('input[name$="passwordx"]').val(decrypt($('#key').val(),obj.IV,obj.password));
            },
            error: function(err)
            {
            	handle(err);
            }
          });
    
    $('#passwordmodal').modal('show');
}


function decrypt(keybase,ivbase,textb64){
    var key = CryptoJS.enc.Base64.parse(keybase);   //16 bytes
    var iv  = CryptoJS.enc.Base64.parse(ivbase);  //16 bytes
    var anzi = CryptoJS.enc.Base64.parse(textb64);


    var bytes  = CryptoJS.AES.decrypt({
                ciphertext: anzi
            }
            , key, { iv: iv });
    return bytes.toString(CryptoJS.enc.Utf8);
}

function encrypt(keybase,textb64){
    var key = CryptoJS.enc.Base64.parse(keybase);   //16 bytes
    var anzi = CryptoJS.enc.Utf8.parse(textb64);
    const genRanHex = size => [...Array(size)].map(() => Math.floor(Math.random() * 16).toString(16)).join('');
    var iv = CryptoJS.enc.Hex.parse(genRanHex(32));
    var bytes  = CryptoJS.AES.encrypt(anzi,key,{ iv: iv });
    let c =
    {
        iv: bytes.iv.toString(CryptoJS.enc.Base64),
        crypt:  bytes.ciphertext.toString(CryptoJS.enc.Base64)
    };
    return c;
}