// Common JS functions
// Confirm before delete
function confirmDelete(url) {
    if (confirm("Are you sure you want to delete this record?")) {
        window.location.href = url;
    }
}

// AJAX example for marking attendance
$(document).ready(function(){
    $(".att-status").change(function(){
        var sid = $(this).data("sid");
        var status = $(this).val();
        var date = $("#att_date").val();
        $.post("attendance.php", {ajax:1, sid:sid, status:status, date:date}, function(res){
            $("#msg-"+sid).html('<span class="text-success">Saved!</span>').fadeOut(2000);
        });
    });
});
