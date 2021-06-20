google.charts.load('current');
google.charts.setOnLoadCallback(drawDailyVisitsGraph);
google.charts.setOnLoadCallback(drawVisitsMap);

function drawDailyVisitsGraph(){
    let dailyVisitsDataTable = new google.visualization.DataTable();
    dailyVisitsDataTable.addColumn("date", "Date");
    dailyVisitsDataTable.addColumn("number", "Visits");

    for(let day in dailyVisitsData){
        dailyVisitsDataTable.addRow([new Date(day), dailyVisitsData[day]]);
    }
    let dailyVisitsOptions = {
        title: "Unique daily visitors",
        height: 500,
        legend: {position: "none"},
        chartArea:{width: "94%", height: "90%"},
        backgroundColor: "#ffe755",
        colors: ["black"],
        trendlines: {
            0: {
                type: "linear",
                lineWidth: 3,
                color: "tomato",
                pointsVisible: false
            }
        }
    };
    let dailyVisitsChartConstructor = {
        containerId: "dailyVisitsGraph",
        chartType: "ColumnChart",
        dataTable: dailyVisitsDataTable,
        options: dailyVisitsOptions
    };
    let dailyVisitsChart = new google.visualization.ChartWrapper(dailyVisitsChartConstructor);
    dailyVisitsChart.draw();
}
function drawVisitsMap(){
    let geoDatatable = new google.visualization.DataTable();
    geoDatatable.addColumn("string", "Location");
    geoDatatable.addColumn("number", "Frequency");
    for(let location in locationFrequencyData){
        geoDatatable.addRow([location, Number(locationFrequencyData[location])]);
    }

    let geoOptions = {
        height: 500,
        displayMode: "regions",
        colorAxis: {colors: ["lightcoral", "crimson"]}
    };
    let geoConstructor = {
        containerId: "mapOfVisitsContainer",
        dataTable: geoDatatable,
        chartType: "GeoChart",
        options: geoOptions
    };
    let geoWrapper = new google.visualization.ChartWrapper(geoConstructor);
    geoWrapper.draw();
}