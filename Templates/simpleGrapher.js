google.charts.load('current', {'packages': ['corechart']});
google.charts.setOnLoadCallback(graph);
function graph() {
    let url = "controller.php?statistic=psc-age";
    var fileRequest = new XMLHttpRequest();
    var datatable = new google.visualization.DataTable();
    var details = {
        "title": "Age",
        height: 500,
        isStacked: true,
        legend: {
            position: "top"
        },
        areaOpacity: 0.7,
        backgroundColor: {fill: "antiquewhite"}, // add this for a border: , stroke: "black", strokeWidth: 2
        series: {},
        selectionMode: "single",
        tooltip: {trigger: "selection"},
        focusTarget: "category"
    };
    var series = {};
    datatable.addColumn("string", "Year");
    fileRequest.onreadystatechange = function () {
        if (this.status === 200 && this.readyState === 4) {
            var data = JSON.parse(this.response);
            console.log(data);
            datatable.addColumn("number", "Age of PSC");
            details.series = series;
            for (let day in data) {
                datatable.addRow([day, Number(data[day])]);
            }
            var graph = new google.visualization.SteppedAreaChart(document.getElementById('graph'));
            graph.draw(datatable, details);
        }
    };
    fileRequest.open("GET", url, true);
    fileRequest.send();
}
