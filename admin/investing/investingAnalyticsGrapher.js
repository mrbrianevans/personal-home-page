google.charts.load('current', {'packages': ['corechart']});
google.charts.setOnLoadCallback(graph);
function graph() {
    let url = "/admin/investing/graphdata.php?uploads=daily";
    var fileRequest = new XMLHttpRequest();
    var datatable = new google.visualization.DataTable();
    var details = {
        "title": "Daily uploads",
        height: 500,
        legend: {
            position: "top"
        },
        areaOpacity: 0.7,
        backgroundColor: {fill: "antiquewhite"},
        selectionMode: "single",
        bar: {groupWidth:"95%"},
        tooltip: {trigger: "selection"},
        colors: ["#E84B3C"]
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
            var graph = new google.visualization.ColumnChart(document.getElementById('uploadsGraph'));
            graph.draw(datatable, details);
        }
    };
    fileRequest.open("GET", url, true);
    fileRequest.send();


}
