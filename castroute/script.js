$(document).ready(()=>{
	setTimeout(()=>{
    	document.querySelector('#copy-input').value = "No way";
    },10000);
	$('#copy-button').bind('click', function() {
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
  });
});
