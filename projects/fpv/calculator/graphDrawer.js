
google.charts.load('current');
google.charts.setOnLoadCallback(drawScatterChart);

function drawScatterChart(){
    let scatterDatatable = new google.visualization.DataTable();
    scatterDatatable.addColumn("number", "Amps drawn on OSD");
    scatterDatatable.addColumn("number", "Amps charged");
    for(let datum in scatterData){
        scatterDatatable.addRow([Number(datum), Number(scatterData[datum])]);
    }
    let scatterOptions = {
        height: 700,
        trendlines: {0:{type: "linear"}},
        legend: {position: "none"},
        tooltip: {trigger: "none"},
        hAxis:{"title": "Amps measured"},
        vAxis:{"title": "Amps charged"},
        backgroundColor: "#ffe755"
    };
    let scatterConstructor = {
        "chartType": "ScatterChart",
        "dataTable": scatterDatatable,
        "options": scatterOptions,
        "containerId": "scatterChartContainer"
    };
    let scatterWrapper = new google.visualization.ChartWrapper(scatterConstructor);
    scatterWrapper.draw();
}
google.charts.setOnLoadCallback(drawColumnChart);

function drawColumnChart(){
    let columnDatatable = new google.visualization.DataTable();
    columnDatatable.addColumn("string", "Measurement number");
    columnDatatable.addColumn("number", "Adjusted scale");
    columnDatatable.addColumn("number", "Average ("+columnData.Average+")");
    for(let datum in columnData){
        if(datum !== "Average")
            columnDatatable.addRow([datum, columnData[datum], columnData.Average]);
    }
    let columnOptions = {
        height: 700,
        trendlines: {0:{type: "linear"}},
        legend: {position: "top"},
        tooltip: {trigger: "none"},
        seriesType: "bars",
        series: {1:{type: "line"}},
        vAxis: {minValue: 0},
        backgroundColor: "#ffe755"
    };
    let columnConstructor = {
        "chartType": "ComboChart",
        "dataTable": columnDatatable,
        "options": columnOptions,
        "containerId": "columnChartContainer"
    };
    let columnWrapper = new google.visualization.ChartWrapper(columnConstructor);
    columnWrapper.draw();
}

google.charts.setOnLoadCallback(drawTable);

function drawTable(){
    let table = new google.visualization.DataTable();
    table.addColumn("string", "Measurement number");
    table.addColumn("number", "Adjusted scale");
    for(let datum in columnData){
        table.addRow([datum, columnData[datum]]);
    }
    let tableConstructor = {
        "chartType": "Table",
        "dataTable": table,
        "options": {},
        "containerId": "tableContainer"
    };
    let tableWrapper = new google.visualization.ChartWrapper(tableConstructor);
    tableWrapper.draw();
}