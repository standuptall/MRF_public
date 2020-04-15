         $(function() {
           $("form[name='login']").validate({
             rules: {
               
               email: {
                 required: true,
                 email: true
               },
               password: {
                 required: true,
                 
               }
             },
              messages: {
               email: "Please enter a valid email address",
              
               password: {
                 required: "Please enter password",
                
               }
               
             },
             submitHandler: function(form) {
               form.submit();
             }
           });
         });
         


$(function() {
  
  $("form[name='registration']").validate({
    rules: {
      nomeutente: {
      	required: true,
        minlength: 6
      },
      email: {
        required: true,
        email: true
      },
      password: {
        required: true,
        minlength: 6
      }
    },
    
    messages: {
      nomeutente: {
        required: "Per favore inserisci un nome utente",
        minlength: "il nome utente deve essere di almeno  6 caratteri"
        },
      password: {
        required: "Per favore inserisci la password",
        minlength: "La password deve essere di almeno 6 caratteri"
      },
      email: "Inserire un indirizzo e-mail valido"
    },
  
    submitHandler: function(form) {
      form.submit();
    }
  });
});
