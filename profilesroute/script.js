$(document).ready(function() {
    $('#example').DataTable({
    responsive: true
});
    $("tr").click(function(e) {
        window.document.location = ("/FantaApp/profile?id="+e.target.parentElement.id);
    });
} );
