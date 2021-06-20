google.charts.load('current', {'packages': ['corechart']});
google.charts.setOnLoadCallback(grapher);
function grapher() {
    var datatable = new google.visualization.DataTable();
    var details = {
        "title": "Company incorperation dates",
        height: 500,
        legend: {
            position: "none"
        },
        backgroundColor: "#ffd98f",
        colors: ["black"]
    };
    datatable.addColumn("date", "Date");
    datatable.addColumn("number", query + " companies started");
    for (let day in data) {
        datatable.addRow([{v: new Date(data[day]["year"], 1, 1), f: "Year "+data[day]["year"]}, data[day]["Companies started"]]);
    }
    var graph = new google.visualization.SteppedAreaChart(document.getElementById('companyFoundingGraph'));
    graph.draw(datatable, details);
}