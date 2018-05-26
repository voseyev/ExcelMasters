$(document).ready(function () {
    $('#datatable').DataTable();
});

function myFunction() {
    var startDate = document.getElementById("startDate").value;
    var endDate = document.getElementById("endDate").value;
    document.getElementById("showDate").innerHTML = startDate;
    document.getElementById("showDate2").innerHTML = endDate;
} 