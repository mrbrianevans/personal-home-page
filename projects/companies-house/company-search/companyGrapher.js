google.charts.load('current', {'packages': ['corechart']});
google.charts.setOnLoadCallback(graph);
function graph() {
    let url = "/investing/file_handler.php?graphDataFile=";
    var fileRequest = new XMLHttpRequest();
    var datatable = new google.visualization.DataTable();
    var details = {
        "title": "Company incorperation dates",
        height: 500,
        isStacked: true,
        legend: {
            position: "top"
        },
        vAxis: {
            format: "percent"
        },
        areaOpacity: 0.7,
        backgroundColor: {fill: "antiquewhite"}, // add this for a border: , stroke: "black", strokeWidth: 2
        series: {},
        selectionMode: "single",
        tooltip: {trigger: "selection"},
        focusTarget: "category"
    };
    datatable.addColumn("date", "Date");
    fileRequest.onreadystatechange = function () {
        if (this.status === 200 && this.readyState === 4) {
            var data = JSON.parse(this.response);
            for (column in data[Object.keys(data)[0]]) {
                datatable.addColumn("number", column);
            }
            datatable.addRows(Object.keys(data).length);
            let row = 0;
            for (let day in data) {
                let column = 0;
                for (let property in data[day]) {
                    datatable.setCell(row, ++column, data[day][property]);
                }
                datatable.setCell(row++, 0, new Date(day));
            }

            var graph = new google.visualization.SteppedAreaChart(document.getElementById('companyFoundingGraph'));
            graph.draw(datatable, details);
        }
    };
    fileRequest.open("GET", url, true);
    fileRequest.send();
}