
google.charts.load('current', {'packages': ['corechart', 'bar']});

let commonOptions = {
    height: 500,
    animation: {
        startup: true,
        duration: 4000,
        easing: 'out',
    },
    legend: {position: "top"},
    vAxis: {gridlines:{color: "transparent"}, minValue: 0},
    focusTarget: "category",
    backgroundColor: "whitesmoke",
    colors: ["tomato", "dodgerblue"],
    crosshair: {orientation: "vertical", trigger: "both",
        focused: {color: "#000", opacity: 0.3},
        selected: {color: "#000", opacity: 0.6}},
    hAxis: {format: "MMM"}
}
// this is the daily and cumulative charts at the bottom
function drawCharts() {
    let graphWidth = document.getElementsByClassName("singlebox").width;
    const details = {
        ...commonOptions,
        hAxis: {
            format: 'd MMM'
        },
        bar: {
            groupWidth: '80%'
        },
        legend:{
            position: "none"
        },
        chart:{
            title: "title",
            subtitle: "sub",
        },
        chartArea:{
            backgroundColor: "whitesmoke"
        },
        vAxis: {gridlines:{color: "transparent"}, minValue: 0, format: "short"},
    };
    if(graphWidth < 762){
        details.bar.groupWidth = '100%';
        details.legend.position = 'none';
    }

    let xhttps = new XMLHttpRequest();

    xhttps.onreadystatechange = function(){
        if(this.readyState ===4 && this.status===200){
            let totalDeathsdata = JSON.parse(xhttps.response);
            let totalDeathsdatatable = new google.visualization.DataTable(totalDeathsdata);
            let totalDeathsDetails = details;
            totalDeathsDetails.chart.title = "Cumulative deaths";
            totalDeathsDetails.chart.subtitle = "Total deaths so far on each day";
            let totalDeathsGraph = new google.charts.Bar(document.getElementById("deathsgraph"));
            totalDeathsGraph.draw(totalDeathsdatatable, google.charts.Bar.convertOptions(totalDeathsDetails));
        }
    };
    xhttps.open("GET", "historical.php?type=cumulative", true);
    xhttps.send();

    let xhtml = new XMLHttpRequest();

    xhtml.onreadystatechange = function(){
        if(xhtml.readyState ===4 && xhtml.status===200) {
            let dailyOptions = details;
            let dailyDeathsData = JSON.parse(xhtml.response);
            let dailyDeathsDatatable = new google.visualization.DataTable(dailyDeathsData);

            dailyOptions.chart.title = "Daily deaths";
            dailyOptions.chart.subtitle = "The number of deaths on each day";
            dailyOptions.chartArea.width = "60%";
            let dailyDeathsGraph = new google.charts.Bar(document.getElementById("dailydeathsgraph"));
            dailyDeathsGraph.draw(dailyDeathsDatatable, google.charts.Bar.convertOptions(dailyOptions));
        }
    };
    xhtml.open("GET", "historical.php?type=daily", true);
    xhtml.send();

    let weeklyDataRequest = new XMLHttpRequest();
    weeklyDataRequest.onreadystatechange = function() {
        if(weeklyDataRequest.readyState===4 && weeklyDataRequest.status===200){
            let weeklyData = JSON.parse(weeklyDataRequest.response);
            let weeklyDatatable = new google.visualization.DataTable(weeklyData);
            let weeklyDetails = {...details, ...commonOptions};
            weeklyDetails.title = "Deaths per week";
            weeklyDetails.height = 500;
            weeklyDetails.legend.position = "top";
            weeklyDetails.chartArea.width = "80%";
            let weeklyConstructor = {
                "chartType": "ColumnChart",
                "dataTable": weeklyDatatable,
                "options": weeklyDetails,
                "containerId": "lastTwoWeeksDaily"
            };
            let weeklyDeathsWrapper = new google.visualization.ChartWrapper(weeklyConstructor);
            weeklyDeathsWrapper.draw();
        }
    };
    weeklyDataRequest.open("GET", "historical.php?type=weekly", true);
    weeklyDataRequest.send();


}
google.charts.setOnLoadCallback(drawCharts);
google.charts.setOnLoadCallback(drawCombinedDeathsAndCasesGraph);
google.charts.setOnLoadCallback(drawMovingAverageGraph);
google.charts.setOnLoadCallback(drawFatalityRateGraph);
google.charts.setOnLoadCallback(drawRollingCaseFatalityRateGraphUK);
google.charts.setOnLoadCallback(drawRollingCaseFatalityRateGraphUSA);
google.charts.setOnLoadCallback(drawWeeklyGeoGraph);
google.charts.setOnLoadCallback(drawWeeklyEnglandWalesDeaths);
google.charts.setOnLoadCallback(drawProportionOfDeathsThatAreCovid);
function drawCombinedDeathsAndCasesGraph(){
    let diseaseRequest = new XMLHttpRequest();
    diseaseRequest.onreadystatechange = () => {
        if(diseaseRequest.readyState===4){
            switch (diseaseRequest.status) {
                case 200:
                    const apiResponse = JSON.parse(diseaseRequest.response);
                    const casesTimeline = apiResponse.timeline.cases;
                    const deathsTimeline = apiResponse.timeline.deaths;
                    let timeline = [];
                    for (let day in casesTimeline){
                        timeline[day] = {
                            cases: casesTimeline[day],
                            deaths: deathsTimeline[day]
                        }
                    }
                    let datatable = new google.visualization.DataTable();
                    datatable.addColumn("date", "Date");
                    datatable.addColumn("number", "Daily Deaths");
                    datatable.addColumn("number", "Daily Cases");
                    for(let day in timeline){
                        let dateObject = new Date(day);
                        let sevenDaysAhead = new Date(day);
                        sevenDaysAhead.setDate(dateObject.getDate()+7);
                        let seventhDay = (sevenDaysAhead.getMonth()+1) + "/" + sevenDaysAhead.getDate() + "/" +
                            (sevenDaysAhead.getFullYear().toString().slice(2, 4));
                        if(timeline[seventhDay] === undefined) break; // leave when there is no more data seven days ahead
                        let caseDifference = timeline[seventhDay].cases - timeline[day].cases;
                        let deathDifference = timeline[seventhDay].deaths - timeline[day].deaths;
                        sevenDaysAhead.setDate(dateObject.getDate()+8);
                        datatable.addRow([new Date(seventhDay), Math.round(deathDifference / 7), Math.round(caseDifference / 7)]);
                    }
                    let options = {
                        ...commonOptions,
                        title: "7 Day Moving Average of Daily Deaths and Daily Cases",
                        series: {0: {targetAxisIndex: 0}, 1: {targetAxisIndex: 1}},
                        vAxes: {0: {title: "Deaths", maxValue: 3000}, 1: {title: "Cases"}},
                        curveType: "function",
                        height: 1000
                    };
                    options.vAxis.format = "decimal";
                    let constructor = {
                        chartType: "LineChart",
                        dataTable: datatable,
                        options: options,
                        containerId: "casesAndDeathsCombined"
                    };
                    let wrapper = new google.visualization.ChartWrapper(constructor);
                    wrapper.draw();
                    break;
                case 501:
                    console.log("Internal server error occurred in the API provider of statistics");
                    break;
                case 404:
                    console.log("URL not set correctly");
                    break;
                default:
                    console.log("Unknown error occured with status code: "+diseaseRequest.status);
                    break;
            }
        }
    };
    let beginningOfPandemic = new Date (2020, 2, 10);
    let days;
    for (let i = 0; i < 500; i++) {
        let tempDate = new Date();
        tempDate.setDate(tempDate.getDate()-i);
        if(tempDate.toDateString()===beginningOfPandemic.toDateString()){
            days = i;
            break;
        }
    }
    diseaseRequest.open("GET", "https://disease.sh/v3/covid-19/historical/uk?lastdays="+days, true);
    diseaseRequest.send();
}
function drawRollingCaseFatalityRateGraphUK(){
    let diseaseRequest = new XMLHttpRequest();
    diseaseRequest.onreadystatechange = () => {
        if(diseaseRequest.readyState===4){
            switch (diseaseRequest.status) {
                case 200:
                    const apiResponse = JSON.parse(diseaseRequest.response);
                    const casesTimeline = apiResponse.timeline.cases;
                    const deathsTimeline = apiResponse.timeline.deaths;
                    let timeline = [];
                    for (let day in casesTimeline){
                        timeline[day] = {
                            cases: casesTimeline[day],
                            deaths: deathsTimeline[day]
                        }
                    }
                    let datatable = new google.visualization.DataTable();
                    datatable.addColumn("date", "Date");
                    datatable.addColumn("number", "Case Fatality Rate");
                    console.log(timeline);
                function convertDateObjectToTimelineKey(dObject){
                    return (dObject.getMonth()+1) + "/" + dObject.getDate() + "/" +
                        (dObject.getFullYear().toString().slice(2, 4));
                }
                    for(let day in timeline){
                        let dateObject = new Date(day);
                        let sevenDaysAhead = new Date(day);
                        sevenDaysAhead.setDate(dateObject.getDate()+7);
                        let seventhDay = convertDateObjectToTimelineKey(sevenDaysAhead);
                        let twentyDaysAhead = new Date(day);
                        twentyDaysAhead.setDate(dateObject.getDate()+20);
                        let twentiethDay = convertDateObjectToTimelineKey(twentyDaysAhead);
                        let twentySevenDaysAhead = new Date(day);
                        twentySevenDaysAhead.setDate(dateObject.getDate()+27);
                        let twentySeventhDay = convertDateObjectToTimelineKey(twentySevenDaysAhead);
                        if(timeline[twentySeventhDay] === undefined) break; // leave when there is no more data seven days ahead
                        let caseDifference = timeline[seventhDay].cases - timeline[day].cases;
                        let deathDifference = timeline[twentySeventhDay].deaths - timeline[twentiethDay].deaths;
                        datatable.addRow([new Date(twentySeventhDay), Math.round(deathDifference / caseDifference * 10000)/10000]);
                    }
                    let percentFormatter = new google.visualization.NumberFormat({pattern: "#,###.##%"});
                    percentFormatter.format(datatable, 1);
                    let options = {
                        title: "7 Day Rolling Average of Case Fatality Rate in United Kingdom",
                        curveType: "function",
                        ...commonOptions,
                        colors: ["dodgerblue"]
                    };
                    options.vAxis.format = "percent";
                    let constructor = {
                        chartType: "LineChart",
                        dataTable: datatable,
                        options: options,
                        containerId: "rollingCaseFatalityRateUK"
                    };
                    let wrapper = new google.visualization.ChartWrapper(constructor);
                    wrapper.draw();
                    break;
                case 501:
                    console.log("Internal server error occurred in the API provider of statistics");
                    break;
                case 404:
                    console.log("URL not set correctly");
                    break;
                default:
                    console.log("Unknown error occured with status code: "+diseaseRequest.status);
                    break;
            }
        }
    };
    let beginningOfPandemic = new Date (2020, 3, 10);
    let days;
    for (let i = 0; i < 500; i++) {
        let tempDate = new Date();
        tempDate.setDate(tempDate.getDate()-i);
        if(tempDate.toDateString()===beginningOfPandemic.toDateString()){
            days = i;
            break;
        }
    }
    diseaseRequest.open("GET", "https://disease.sh/v3/covid-19/historical/uk?lastdays="+days, true);
    diseaseRequest.send();
}
function drawRollingCaseFatalityRateGraphUSA(){
    let diseaseRequest = new XMLHttpRequest();
    diseaseRequest.onreadystatechange = () => {
        if(diseaseRequest.readyState===4){
            switch (diseaseRequest.status) {
                case 200:
                    const apiResponse = JSON.parse(diseaseRequest.response);
                    const casesTimeline = apiResponse.timeline.cases;
                    const deathsTimeline = apiResponse.timeline.deaths;
                    let timeline = [];
                    for (let day in casesTimeline){
                        timeline[day] = {
                            cases: casesTimeline[day],
                            deaths: deathsTimeline[day]
                        }
                    }
                    let datatable = new google.visualization.DataTable();
                    datatable.addColumn("date", "Date");
                    datatable.addColumn("number", "Case Fatality Rate");
                    console.log(timeline);
                    let skipCounter = 0;
                    const skipDays = 20;
                function convertDateObjectToTimelineKey(dObject){
                    return (dObject.getMonth()+1) + "/" + dObject.getDate() + "/" +
                        (dObject.getFullYear().toString().slice(2, 4));
                }
                    for(let day in timeline){
                        // if(skipCounter++==skipDays) continue;
                        let dateObject = new Date(day);
                        let sevenDaysAhead = new Date(day);
                        sevenDaysAhead.setDate(dateObject.getDate()+7);
                        let seventhDay = convertDateObjectToTimelineKey(sevenDaysAhead);
                        let twentyDaysAhead = new Date(day);
                        twentyDaysAhead.setDate(dateObject.getDate()+20);
                        let twentiethDay = convertDateObjectToTimelineKey(twentyDaysAhead);
                        let twentySevenDaysAhead = new Date(day);
                        twentySevenDaysAhead.setDate(dateObject.getDate()+27);
                        let twentySeventhDay = convertDateObjectToTimelineKey(twentySevenDaysAhead);
                        if(timeline[twentySeventhDay] === undefined) break; // leave when there is no more data seven days ahead
                        let caseDifference = timeline[seventhDay].cases - timeline[day].cases;
                        let deathDifference = timeline[twentySeventhDay].deaths - timeline[twentiethDay].deaths;
                        datatable.addRow([new Date(twentySeventhDay), Math.round(deathDifference / caseDifference * 10000)/10000]);
                        // console.log([new Date(seventhDay), Math.round(deathDifference / 7), Math.round(caseDifference / 7)])
                    }
                    let percentFormatter = new google.visualization.NumberFormat({pattern: "#,###.##%"});
                    percentFormatter.format(datatable, 1);
                    let options = {
                        title: "7 Day Rolling Average of Case Fatality Rate in United States of America",
                        curveType: "function",
                        ...commonOptions
                    };
                    options.vAxis.format = "percent";
                    let constructor = {
                        chartType: "LineChart",
                        dataTable: datatable,
                        options: options,
                        containerId: "rollingCaseFatalityRateUSA"
                    };
                    let wrapper = new google.visualization.ChartWrapper(constructor);
                    wrapper.draw();
                    break;
                case 501:
                    console.log("Internal server error occurred in the API provider of statistics");
                    break;
                case 404:
                    console.log("URL not set correctly");
                    break;
                default:
                    console.log("Unknown error occured with status code: "+diseaseRequest.status);
                    break;
            }
        }
    };
    let beginningOfPandemic = new Date (2020, 3, 10);
    let days;
    for (let i = 0; i < 500; i++) {
        let tempDate = new Date();
        tempDate.setDate(tempDate.getDate()-i);
        if(tempDate.toDateString()===beginningOfPandemic.toDateString()){
            days = i;
            break;
        }
    }
    diseaseRequest.open("GET", "https://disease.sh/v3/covid-19/historical/usa?lastdays="+days, true);
    diseaseRequest.send();
}
function drawMovingAverageGraph(){
    let modelRequest = new XMLHttpRequest();
    modelRequest.onreadystatechange = () => {
        if(modelRequest.readyState===4){
            switch (modelRequest.status) {
                case 200:
                    let datatable = new google.visualization.DataTable(JSON.parse(modelRequest.response));
                    let options = {
                        title: "7 Day Moving Average of Daily Deaths",
                        ...commonOptions
                    };
                    let constructor = {
                        chartType: "LineChart",
                        dataTable: datatable,
                        options: options,
                        containerId: "movingAverage"
                    };
                    options.vAxis.format = "decimal";
                    let wrapper = new google.visualization.ChartWrapper(constructor);
                    wrapper.draw();
                    break;
                case 501:
                    alert("Internal server error occurred");
                    break;
                case 404:
                    alert("URL not set correctly");
                    break;
                default:
                    alert("Unknown error occured with status code: "+modelRequest.status);
                    break;
            }
        }
    };
    modelRequest.open("GET", "historical.php?type=moving-average", true);
    modelRequest.send();
}
function drawFatalityRateGraph(){
    let modelRequest = new XMLHttpRequest();
    modelRequest.onreadystatechange = () => {
        if(modelRequest.readyState===4){
            switch (modelRequest.status) {
                case 200:
                    let datatable = new google.visualization.DataTable(JSON.parse(modelRequest.response));
                    let percentFormatter = new google.visualization.NumberFormat({pattern: "#,###.##%"});
                    percentFormatter.format(datatable, 1);
                    percentFormatter.format(datatable, 2);
                    let options = {
                        title: "Total Case fatality rate (Deaths per case)",
                        ...commonOptions
                    };
                    options.vAxis.format = "percent";
                    let constructor = {
                        chartType: "LineChart",
                        dataTable: datatable,
                        options: options,
                        containerId: "caseFatalityRate"
                    };
                    let wrapper = new google.visualization.ChartWrapper(constructor);
                    wrapper.draw();
                    break;
                case 501:
                    alert("Internal server error occurred");
                    break;
                case 404:
                    alert("URL not set correctly");
                    break;
                default:
                    alert("Unknown error occured with status code: "+modelRequest.status);
                    break;
            }
        }
    };
    modelRequest.open("GET", "historical.php?type=fatality-rate", true);
    modelRequest.send();
}
function drawWeeklyGeoGraph(){
    let modelRequest = new XMLHttpRequest();
    modelRequest.onreadystatechange = () => {
        if(modelRequest.readyState===4){
            switch (modelRequest.status) {
                case 200:
                    let parsedData = JSON.parse(modelRequest.response);
                    let datatable = new google.visualization.DataTable();
                    datatable.addColumn("string", "State");
                    datatable.addColumn("number", "Deaths last week");
                    for(let state in parsedData){
                        datatable.addRow([state, parsedData[state]]);
                    }
                    let options = {
                        height: 500,
                        title: "Deaths in the last week by state",
                        region: "US",
                        displayMode: "regions",
                        resolution: "provinces",
                        colorAxis: {colors: ["navajowhite", "tomato"]},
                        backgroundColor: {fill:"whitesmoke"},
                        legend: {position: "top"}
                    };
                    let constructor = {
                        chartType: "GeoChart",
                        dataTable: datatable,
                        options: options,
                        containerId: "stateWeeklyMap"
                    };
                    let wrapper = new google.visualization.ChartWrapper(constructor);
                    wrapper.draw();
                    break;
                case 501:
                    alert("Internal server error occurred");
                    break;
                case 404:
                    alert("URL not set correctly");
                    break;
                default:
                    alert("Unknown error occured with status code: "+modelRequest.status);
                    break;
            }
        }
    };
    modelRequest.open("GET", "historical.php?type=state-weekly", true);
    modelRequest.send();
}
function drawWeeklyEnglandWalesDeaths(){
    let datatable = new google.visualization.DataTable();
    datatable.addColumn("string", "Age group");
    datatable.addColumn("number", "Other deaths");
    datatable.addColumn("number", "COVID deaths");
    datatable.addRows([
        ["0-1", 0, 0],
        ["1-4", 0, 0],
        ["5-9", 0, 0],
        ["10-14", 0, 0],
        ["15-19", 0, 0],
        ["20-24", 0, 0],
        ["25-29", 0, 0],
        ["30-34", 0, 0],
        ["35-39", 0, 0],
        ["40-44", 0, 0],
        ["45-49", 0, 0],
        ["50-54", 0, 0],
        ["55-59", 0, 0],
        ["60-64", 0, 0],
        ["65-69", 0, 0],
        ["70-74", 0, 0],
        ["75-79", 0, 0],
        ["80-84", 0, 0],
        ["85-89", 0, 0],
        ["90+", 0, 0]
    ])
    let options = {
        ...commonOptions,
        title: "Deaths in the last week in England and Wales",
        isStacked: true,
        colors: ["dodgerblue", "tomato"],
        hAxis: {"title":"Age Group"}
    };
    options.vAxis.format = "decimal";
    let constructor = {
        chartType: "ColumnChart",
        dataTable: datatable,
        options: options,
        containerId: "englandWeeklyMap"
    };
    let wrapper = new google.visualization.ChartWrapper(constructor);
    wrapper.draw();
    let modelRequest = new XMLHttpRequest();
    modelRequest.onreadystatechange = () => {
        if(modelRequest.readyState===4){
            switch (modelRequest.status) {
                case 200:
                    let parsedData = JSON.parse(modelRequest.response);
                    datatable.removeRows(0, 21);
                    for(let ageGroup in parsedData){
                        if(ageGroup !== 'all-ages')
                        datatable.addRow([ageGroup, Number(parsedData[ageGroup].total), Number(parsedData[ageGroup].covid)]);
                    }
                    options.vAxis.format = "decimal";
                    wrapper.draw();
                    break;
                case 501:
                    alert("Internal server error occurred");
                    break;
                case 404:
                    alert("URL not set correctly");
                    break;
                default:
                    alert("Unknown error occured with status code: "+modelRequest.status);
                    break;
            }
        }
    };
    modelRequest.open("GET", "historical.php?type=england-weekly", true);
    modelRequest.send();
}
function drawProportionOfDeathsThatAreCovid(){
    let modelRequest = new XMLHttpRequest();
    modelRequest.onreadystatechange = () => {
        if(modelRequest.readyState===4){
            switch (modelRequest.status) {
                case 200:
                    let parsedData = JSON.parse(modelRequest.response);
                    let datatable = new google.visualization.DataTable();
                    datatable.addColumn("string", "Cause of death");
                    datatable.addColumn("number", "Number of deaths");
                    datatable.addRow(["Other", Number(parsedData.other)]);
                    datatable.addRow(["COVID", Number(parsedData.covid)]);
                    let options = {
                        height: 500,
                        title: "Deaths in the last week in England and Wales",
                        animation: {
                            startup: true,
                            duration: 4000,
                            easing: 'out',
                        },
                        colors: ["dodgerblue", "tomato"],
                        pieHole: 0.5,
                        legend: {position: "top"}
                    };
                    let constructor = {
                        chartType: "PieChart",
                        dataTable: datatable,
                        options: options,
                        containerId: "englandCOVIDproportion"
                    };
                    let wrapper = new google.visualization.ChartWrapper(constructor);
                    wrapper.draw();
                    break;
                case 501:
                    alert("Internal server error occurred");
                    break;
                case 404:
                    alert("URL not set correctly");
                    break;
                default:
                    alert("Unknown error occured with status code: "+modelRequest.status);
                    break;
            }
        }
    };
    modelRequest.open("GET", "historical.php?type=proportion", true);
    modelRequest.send();
}

