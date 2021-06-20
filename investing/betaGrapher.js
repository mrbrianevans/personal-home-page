google.charts.load('current', {'packages': ['corechart']});
const MONTHS = ["December", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November"];
let graphPreference = document.cookie.match(/graph=([^;]+)/)[1];
switch (graphPreference) {
    case "drawInstrumentsValue":
        google.charts.setOnLoadCallback(drawInstrumentsValue);
        break;
    case "drawTimeWeightedRateOfReturn":
        google.charts.setOnLoadCallback(drawTimeWeightedRateOfReturn);
        break;
    case "drawPortfolioValue":
        google.charts.setOnLoadCallback(drawPortfolioValue);
        break;
    default:
        google.charts.setOnLoadCallback(drawPortfolioValue);
        break;
}

function drawInstrumentsValue(){
    let expirationDate = new Date();
    expirationDate.setTime(expirationDate.getTime()+86400*1000*30);
    document.cookie = "graph=drawInstrumentsValue; expires="+expirationDate.toUTCString()+"; path=/investing";

    let folder = document.cookie.match(/folder=([^;]+)/)[1];
    let fileName = "/dailyInstrumentsValues";
    fileName = encodeURIComponent(fileName);
    let url = "/investing/file_handler.php?graphDataFile=" + folder + fileName;
    var fileRequest = new XMLHttpRequest();
    fileRequest.open("GET", url, true);
    var datatable = new google.visualization.DataTable();
    var details = {
        "title": "Instruments value over time",
        height: 500,
        isStacked: true,
        legend: {
            position: "top"
        },
        vAxis: {
            format: "currency"
        },
        areaOpacity: 0.7,
        backgroundColor: {fill: "antiquewhite"}, // add this for a border: , stroke: "black", strokeWidth: 2
        series: {},
        selectionMode: "single",
        tooltip: {trigger: "focus"},
        focusTarget: "category",
        lineWidth: 0
    };
    var series = {};
    var columnIndices = [];
    datatable.addColumn("date", "Date");
    fileRequest.onreadystatechange = function () {
        if (this.status === 200 && this.readyState === 4) {
            document.getElementById("portfolioGraph").innerHTML = "<a href='"+url + "'>"+url+"</a> returned this: \n" + fileRequest.response;
            var data = JSON.parse(this.response);
            for (column in data[Object.keys(data)[Object.keys(data).length-1]]) {
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
            if(columnIndices.length > 10)
                alert("Sorry, only 10 instruments can be viewed in this mode and your portfolio contains "+columnIndices.length);
            details.series = series;
            datatable.addRows(Object.keys(data).length);
            let row = 0;
            for (let day in data) {
                let column = 0;
                for (let instrument in data[day]) {
                    datatable.setCell(row, ++column, data[day][instrument]);
                }
                datatable.setCell(row++, 0, new Date(day));
            }
            let dollarFormatter = new google.visualization.NumberFormat({pattern: "$#,###.##"});
            for (let i = 1; i <= columnIndices.length; i++) {
                dollarFormatter.format(datatable, i);
            }
            var graph = new google.visualization.AreaChart(document.getElementById('portfolioGraph'));
            graph.draw(datatable, details);
        }
    };
    fileRequest.send();


}

function drawTimeWeightedRateOfReturn(){
    let expirationDate = new Date();
    expirationDate.setTime(expirationDate.getTime()+86400*1000*30);
    document.cookie = "graph=drawTimeWeightedRateOfReturn;expires=" + expirationDate.toUTCString() + "; path=/investing;";

    let folder = document.cookie.match(/folder=([^;]+)/)[1];
    let fileName = "/monthlyTimeWeightedReturns";
    fileName = encodeURIComponent(fileName);
    let url = "/investing/file_handler.php?graphDataFile=" + folder + fileName;
    var fileRequest = new XMLHttpRequest();
    var datatable = new google.visualization.DataTable();
    var details = {
        "title": "Time Weighted Rate of Return",
        legend: {
            position: "top"
        },
        vAxis: {
            format: "percent"
        },
        backgroundColor: {fill: "antiquewhite"},
        tooltip: {trigger: "selection"},
        theme: "maximized"
    };
    var series = {};
    datatable.addColumn("date", "Date");
    fileRequest.onreadystatechange = function () {
        if (this.status === 200 && this.readyState === 4) {
            var data = JSON.parse(this.response);
            for (column in data[Object.keys(data)[0]]) {
                datatable.addColumn("number", column);
            }
            datatable.addColumn({type: "string", role: "tooltip"});
            details.series = series;
            datatable.addRows(Object.keys(data).length);
            let row = 0;
            for (let day in data) {
                let column = 0;
                for (let property in data[day]) {
                    datatable.setCell(row, ++column, data[day][property]); // add this to format dollars: {v: data[day][property], f:"$"+data[day][property].toString+""}
                    let month = new Date(day);
                    datatable.setCell(row, column+1,
                        Math.round((data[day][property]*1000))/10+
                        "% return in "+
                        MONTHS[(month.getMonth()+1)%12]+ " of "+
                        month.getUTCFullYear());
                }
                datatable.setCell(row++, 0, new Date(day));
            }
            let percentFormatter = new google.visualization.NumberFormat({pattern: "#,###.##%"});
            percentFormatter.format(datatable, 1);
            var graph = new google.visualization.SteppedAreaChart(document.getElementById('portfolioGraph'));
            graph.draw(datatable, details);
        }
    };
    fileRequest.open("GET", url, true);
    fileRequest.send();


}

function drawPortfolioValue(){
    let expirationDate = new Date();
    expirationDate.setTime(expirationDate.getTime()+86400*1000*30);
    document.cookie = "graph=drawPortfolioValue; expires="+expirationDate.toUTCString()+"; path=/investing";

    let folder = document.cookie.match(/folder=([^;]+)/)[1];
    let fileName = "/dailyPortfolioValue";
    fileName = encodeURIComponent(fileName);
    let url = "/investing/file_handler.php?graphDataFile=" + folder + fileName;
    var fileRequest = new XMLHttpRequest();
    var datatable = new google.visualization.DataTable();
    var details = {
        "title": "Portfolio value over time",
        height: 500,
        legend: {
            position: "none"
        },
        vAxis: {
            format: "currency"
        },
        backgroundColor: {fill: "antiquewhite"},
        selectionMode: "single",
        tooltip: {trigger: "selection"},
        curveType: "function",
        theme: "maximized"
    };
    datatable.addColumn("date", "Date");
    fileRequest.onreadystatechange = function () {
        if (fileRequest.status === 200 && fileRequest.readyState === 4) {
            document.getElementById("portfolioGraph").innerHTML = "<a href='"+url + "'>"+url+"</a> returned this: \n" + fileRequest.response;
            var data = JSON.parse(fileRequest.response);
            for (column in data[Object.keys(data)[0]]) {
                datatable.addColumn("number", column);
            }
            datatable.addRows(Object.keys(data).length);
            let row = 0;
            for (let day in data) {
                let column = 0;
                for (let property in data[day]) {
                    datatable.setCell(row, ++column, data[day][property]); // add this to format dollars: {v: data[day][property], f:"$"+data[day][property].toString+""}
                }
                datatable.setCell(row++, 0, new Date(day));
            }
            let dollarFormatter = new google.visualization.NumberFormat({pattern: "$#,###.##"});
            dollarFormatter.format(datatable, 1);
            var graph = new google.visualization.LineChart(document.getElementById('portfolioGraph'));
            graph.draw(datatable, details);
        }
    };
    fileRequest.open("GET", url, true);
    fileRequest.send();


}