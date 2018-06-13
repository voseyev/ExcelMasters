$(document).ready(function () {
    //comments inside break it
    //number is column to action on
    $('#datatable').DataTable({
        columns: [
            {'type': 'num'},
            {'type': 'string'},
            {'type': 'string'},
            {'type': 'num'},
            {'type': 'num'},
            {'type': 'num'},
            {'type': 'num'},
            {'type': 'num'},
            {'type': 'num'},
        ],
        fixedHeader: true,
        pageLength: 50,
        order: [[ 2, "desc" ]],
        rowGroup: {
            dataSrc: 2
        }

    });
});


/*


var tableArray = [];
<repeat group="{{@ethnicity}}" value="{{@each}}">
    ethnicity.push("{{@each["Label"]}}");
total.push({{@each["Value"]}});
</repeat>

// Load the Visualization API and the piechart package.
google.charts.load('current', {'packages': ['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var jsonData = $.ajax({
        url: "/model/ajaxSelect.php",
        dataType: "json",
        async: false
    }).responseText;

    // Create our data table out of JSON data loaded from server.
    var data = new google.visualization.DataTable(jsonData);

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    chart.draw(data, {width: 400, height: 240});
}

/*
<repeat group="{{@data}}" value="{{@row}}">
                    <tr>
                        <td>{{@row['item_num']}}</td>
                        <td>{{@row['title']}}</td>
                        <td>{{@row['end_date']}}</td>
                        <td>{{@row['win']}}</td>
                        <td>{{@row['pristine']}}</td>
                        <td>{{@row['cosignor']}}</td>
                        <td>{{@row['cost']}}</td>
                        <td>{{@row['profit']}}</td>
                    </tr>
                </repeat>
 */