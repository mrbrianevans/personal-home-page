google.charts.load('current', {'packages': ['corechart']});
google.charts.setOnLoadCallback(graph);
function graph() {
    let folder = document.cookie.match(/folder=([^;]+)/)[1];
    let fileName = "/dailyPortfolioValue";
    fileName = encodeURIComponent(fileName);
    let url = "/investing/file_handler.php?graphDataFile=" + folder + fileName;
    var fileRequest = new XMLHttpRequest();
    var datatable = new google.visualization.DataTable();
    var details = {
        "title": "Portfolio performance",
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
    var series = {};
    var columnIndices = [];
    datatable.addColumn("date", "Date");
    fileRequest.onreadystatechange = function () {
        if (this.status === 200 && this.readyState === 4) {
            var data = JSON.parse(this.response);
            for (column in data[Object.keys(data)[0]]) {
                columnIndex = datatable.addColumn("number", column);
                columnIndices.push(columnIndex);

                let colourRequest = new XMLHttpRequest();
                let colourURL = "/investing/file_handler.php?colour="+column;
                colourRequest.open("GET", colourURL, false);
                colourRequest.send();
                let colorCode = colourRequest.response;
                if(colorCode.length > 1){
                    series[columnIndex-1] = {"color": colorCode}; //TODO: Add labelInLegend to the company name
                }else{
                    series[columnIndex-1] = {};
                }
            }
            details.series = series;
            datatable.addRows(Object.keys(data).length);
            let row = 0;
            for (let day in data) {
                let column = 0;
                for (let property in data[day]) {
                    datatable.setCell(row, ++column, data[day][property]); // add this to format dollars: {v: data[day][property], f:"$"+data[day][property].toString+""}
                }
                datatable.setCell(row++, 0, new Date(day));
            }
            let percentFormatter = new google.visualization.NumberFormat({pattern: "#,###.##%"});
            let dollarFormatter = new google.visualization.NumberFormat({pattern: "$#,###.##"});
            if(columnIndices.length===1){
                percentFormatter.format(datatable, 1);
            }else{
                details.vAxis.format = "currency";
                for (let i = 1; i <= columnIndices.length; i++) {
                    dollarFormatter.format(datatable, i);
                }
            }
            var graph = new google.visualization.SteppedAreaChart(document.getElementById('portfolioGraph'));
            graph.draw(datatable, details);
        }
    };
    fileRequest.open("GET", url, true);
    fileRequest.send();


}

function changeGraph(mode){

}