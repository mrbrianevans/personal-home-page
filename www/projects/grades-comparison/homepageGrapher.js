google.charts.load('current', {'packages': ['corechart', 'controls', 'geochart']});
google.charts.setOnLoadCallback(createHomepageDashboard);
google.charts.setOnLoadCallback(drawGenderPieChart);
google.charts.setOnLoadCallback(drawGeoChartOfParticipation);
google.charts.setOnLoadCallback(drawSubjectColumnChartByGrade);

function createHomepageDashboard(){
    //make this datatable switchable to please
    let subjectsByGradeDatatable = new google.visualization.DataTable();
    subjectsByGradeDatatable.addColumn("string", "Subject");
    subjectsByGradeDatatable.addColumn({role: "tooltip", p: {"html":true}});
    subjectsByGradeDatatable.addColumn("number", "A*");
    subjectsByGradeDatatable.addColumn("number", "A");
    subjectsByGradeDatatable.addColumn("number", "B");
    subjectsByGradeDatatable.addColumn("number", "C");
    subjectsByGradeDatatable.addColumn("number", "D");
    subjectsByGradeDatatable.addColumn("number", "E");
    subjectsByGradeDatatable.addColumn("number", "U");
    let generateGradeTooltip = function(aStar, A, B, C, D, E, U, total){
        let message = "<table>" +
            "<tr><th>Grade</th><th>Percent</th></tr>" +
            "<tr><td><span class='highlight'>A*</span></td><td>"+Math.round((A/total*100))+"%</td></tr>" +
            "<tr><td><span class='highlight'>A</span></td><td>"+(B/total*100)+"%</td></tr>" +
            "<tr><td><span class='highlight'>B</span></td><td>"+(C/total*100)+"%</td></tr>" +
            "<tr><td><span class='highlight'>C</span></td><td>"+(D/total*100)+"%</td></tr>" +
            "<tr><td><span class='highlight'>D</span></td><td>"+(E/total*100)+"%</td></tr>" +
            "<tr><td><span class='highlight'>E</span></td><td>"+(U/total*100)+"%</td></tr>" +
            "<tr><td><span class='highlight'>U</span></td><td>"+(aStar/total*100)+"%</td></tr>" +
            "</table>";
        return message;
    };
    for (let subject in totalGradeParticipationData) {
        let gradeArray = [
            totalGradeParticipationData[subject]["A*"],
            totalGradeParticipationData[subject]["A"],
            totalGradeParticipationData[subject]["B"],
            totalGradeParticipationData[subject]["C"],
            totalGradeParticipationData[subject]["D"],
            totalGradeParticipationData[subject]["E"],
            totalGradeParticipationData[subject]["U"]
        ];
        let tooltipText = generateGradeTooltip(...gradeArray.concat(totalGradeParticipationData[subject]["total"]));
        subjectsByGradeDatatable.addRow([subject, tooltipText].concat(gradeArray));
    }
    var datatable = new google.visualization.DataTable();
    datatable.addColumn("string", "Subject");
    datatable.addColumn({"type": "string", "role": "tooltip", "p":{"html":true}});
    datatable.addColumn("number", "Male");
    datatable.addColumn("number", "Female");
    datatable.addColumn("number", "total");
    function generateTooltip(subject, male, female){
        let totalParticipation = male + female;
        let percentageMale = Math.round(male/totalParticipation*100);
        let percentageFemale = Math.round(female/totalParticipation*100);
        let button = "<a href='?subject="+encodeURIComponent(subject)+"'><button class='bigButton tooltipButton'>View</button></a>";
        return "<b>Participation in "+subject+"</b>: <br>" +
            "Total: <span class='totalParticipation highlight'>"+totalParticipation+"</span><br>" +
            "<div class='tooltipMaleFemaleStats'>Male <span class='percentageMale highlight'>"+percentageMale+"%</span> - " +
            "<span class='percentageFemale highlight'>"+percentageFemale+"%</span> Female</div>"
            +button;
    }
    let countLimit = 0;
    for (let subject in data) {
        let tooltipText = generateTooltip(subject, data[subject].male, data[subject].female);
        datatable.addRow([subject, tooltipText, data[subject].male, data[subject].female, data[subject].total]);
    }
    let dashboardDiv = document.createElement("div");
    let subjectsColumnChart = document.createElement("div");
    subjectsColumnChart.id = "columnChartContainer";
    let subjectsFilterContainer = document.createElement("div");
    subjectsFilterContainer.id = "subjectsFilterContainer";
    subjectsFilterContainer.className = "filterTwo";
    let participationFilterContainer = document.createElement("div");
    participationFilterContainer.id = "participationFilterContainer";
    participationFilterContainer.className = "filterTwo";

    let filtersTab = document.createElement("div");
    filtersTab.id = "filtersContainer";
    filtersTab.appendChild(participationFilterContainer);
    filtersTab.appendChild(subjectsFilterContainer);
    dashboardDiv.appendChild(filtersTab);
    dashboardDiv.appendChild(subjectsColumnChart);
    dashboardDiv.id = "homepageDashboard";
    document.getElementById("genderParticipationGraph").appendChild(dashboardDiv);


    let switch100PercentViewButton = document.createElement("button");
    switch100PercentViewButton.innerText = "Switch view to 100%";
    switch100PercentViewButton.id = "switchTo100PercentViewButton";
    switch100PercentViewButton.addEventListener("click", () => {
        columnChartOptions.isStacked = columnChartOptions.isStacked==="percent"?true:"percent";
        mainDashboard.draw(datatable);
        switch100PercentViewButton.innerText = columnChartOptions.isStacked==="percent" ? "Switch view to nominal":"Switch view to 100%";
    });
    switch100PercentViewButton.className = "bigButton";
    let switch100PercentViewButtonContainer = document.createElement("div");
    switch100PercentViewButtonContainer.appendChild(switch100PercentViewButton);
    switch100PercentViewButtonContainer.className = "filterOne";
    filtersTab.appendChild(switch100PercentViewButtonContainer);


    var mainDashboard = new google.visualization.Dashboard(dashboardDiv);

    var columnChartOptions = {
        "title": "Participation in A Levels by subject",
        height: 500,
        legend: {
            position: "none"
        },
        backgroundColor: "#ffd98f",
        isStacked: true,
        hAxis: {textStyle:{fontSize:10}},
        focusTarget: "category",
        tooltip:{trigger: "selection", isHtml: true},
        colors:["dodgerblue", "hotpink"]
    };
    let columnChartConstructor = {
        "chartType": "ColumnChart",
        "containerId": "columnChartContainer",
        "options": columnChartOptions,
        "view": {"columns": [0, 1, 2, 3]}
    };
    let columnChart = new google.visualization.ChartWrapper(columnChartConstructor);
    let switchDatatableToGrades = function(){
        columnChart.setDataTable(subjectsByGradeDatatable);
        columnChart.setOption("colors", null);
        columnChart.setView(null);
        columnChart.draw();
    };
    google.visualization.events.addListener(columnChart, "ready", ()=>{
        document.getElementById("switchToGrades").addEventListener("click", switchDatatableToGrades);
    });
    let subjectFilterOptions = {
        "controlType": "StringFilter",
        "containerId": "subjectsFilterContainer",
        "options": {
            "filterColumnLabel": "Subject",
            "ui":{
                "label": "Search for a subject"
            }
        }
    };
    let subjectSearcher = new google.visualization.ControlWrapper(subjectFilterOptions);
    mainDashboard.bind(subjectSearcher, columnChart);
    let participationFilterOptions = {
        "controlType": "NumberRangeFilter",
        "containerId": "participationFilterContainer",
        "options": {
            "filterColumnLabel": "total",
            "minValue":0,
            "maxValue":96000,
            "ui":{
                "showRangeValues":false,
                "label": "Total participation",
                "step": 1000,
                "labelStacking":"vertical"
            }
        },
        "state":{
            "lowValue":36000,
            "highValue": 94900,
        }
    };
    var participationNumberFilter = new google.visualization.ControlWrapper(participationFilterOptions);
    mainDashboard.bind(participationNumberFilter, columnChart);
    mainDashboard.draw(datatable);

    window.addEventListener("resize", reDrawGraphs);

    let maximiseRange = function(){
        participationNumberFilter.setState({'lowValue':0, 'highValue': 94900});
        participationNumberFilter.draw();
    };
    let top10Range = function(){
        participationNumberFilter.setState({'lowValue':36000, 'highValue': 94900});
        participationNumberFilter.draw();
    };
    let top5Range = function(){
        participationNumberFilter.setState({'lowValue':44000, 'highValue': 94900});
        participationNumberFilter.draw();
    };
    google.visualization.events.addListener(participationNumberFilter, 'ready', ()=>{
        let topSubjectsSpan = document.createElement("span");
        let top10SubjectsButton = document.createElement("button");
        top10SubjectsButton.innerText = "Top 10";
        top10SubjectsButton.className = "smallButton";
        top10SubjectsButton.style.marginRight = "5px";
        top10SubjectsButton.addEventListener("click", top10Range);
        topSubjectsSpan.appendChild(top10SubjectsButton);

        let top5SubjectsButton = document.createElement("button");
        top5SubjectsButton.innerText = "Top 5";
        top5SubjectsButton.className = "smallButton";
        top5SubjectsButton.style.marginRight = "5px";
        top5SubjectsButton.addEventListener("click", top5Range);
        topSubjectsSpan.appendChild(top5SubjectsButton);

        let allSubjectsButton = document.createElement("button");
        allSubjectsButton.innerText = "All";
        allSubjectsButton.className = "smallButton";
        allSubjectsButton.addEventListener("click", maximiseRange);
        topSubjectsSpan.appendChild(allSubjectsButton);

        document.getElementById("participationFilterContainer").firstElementChild.firstElementChild.insertAdjacentElement("afterend", topSubjectsSpan);
    });
    //add text box event listener
    google.visualization.events.addListener(mainDashboard, 'ready', ()=>{
        document.getElementById("subjectsFilterContainer").firstElementChild.lastElementChild.firstElementChild.addEventListener("keydown", maximiseRange);
    });
}

function drawGenderPieChart(){
    let datatable = new google.visualization.DataTable();
    datatable.addColumn("string", "Gender");
    datatable.addColumn("number", "Participation");
    datatable.addRow(["Male", totalGenderParticipationData.male]);
    datatable.addRow(["Female", totalGenderParticipationData.female]);
    let subjectsPieChart = document.createElement("div");
    subjectsPieChart.id = "homepagePieChartContainer";
    document.getElementById("genderParticipationGraph").appendChild(subjectsPieChart);
    let pieChartOptions = {
        "title": "Overall A Level gender participation",
        height: 500,
        legend: {
            position: "top"
        },
        backgroundColor: "#ffd98f",
        pieHole: 0.5,
        tooltip:{trigger: "selection", isHtml: false},
        colors:["dodgerblue", "hotpink"]
    };
    let pieChartConstructor = {
        "chartType": "PieChart",
        "containerId": "homepagePieChartContainer",
        "dataTable": datatable,
        "options": pieChartOptions
    };
    let pieChart = new google.visualization.ChartWrapper(pieChartConstructor);
    pieChart.draw();
    window.addEventListener("resize", reDrawGraphs);
}

function drawGeoChartOfParticipation(){
    let geoDatatable = new google.visualization.DataTable();
    geoDatatable.addColumn("string", "Country");
    geoDatatable.addColumn("number", "Participation");
    geoDatatable.addRow(["England", totalCountryParticipationData.england]);
    geoDatatable.addRow(["Northern Ireland", totalCountryParticipationData.northernIreland]);
    geoDatatable.addRow(["Wales", totalCountryParticipationData.wales]);
    geoDatatable.addRow(["Total", totalCountryParticipationData.uk]);
    let geoChartOptions = {
        "title": "Overall A Level country participation",
        height: 500,
        backgroundColor: "#ffd98f",
        resolution: 'provinces',
        displayMode: 'regions',
        tooltip:{trigger: "selection", isHtml: true},
        colorAxis:{minValue: 0, colors:["orange", "orangered"], maxValue: 1000000},
        datalessRegionColor: "#ffd98f",
        region: "GB"
    };
    let geoChartConstructor = {
        "chartType": "GeoChart",
        "containerId": "geoChartContainer",
        "dataTable": geoDatatable,
        "options": geoChartOptions
    };
    let tableChartConstructor = {
        "chartType": "Table",
        "containerId": "geoTableContainer",
        "dataTable": geoDatatable,
    };
    let geoChart = new google.visualization.ChartWrapper(geoChartConstructor);
    let geoTable = new google.visualization.ChartWrapper(tableChartConstructor);
    geoChart.draw();
    geoTable.draw();
}

function drawSubjectColumnChartByGrade(){
    let subjectsByGradeDatatable = new google.visualization.DataTable();
    subjectsByGradeDatatable.addColumn("string", "Subject");
    subjectsByGradeDatatable.addColumn({role: "tooltip", p: {"html":true}});
    subjectsByGradeDatatable.addColumn("number", "A*");
    subjectsByGradeDatatable.addColumn("number", "A");
    subjectsByGradeDatatable.addColumn("number", "B");
    subjectsByGradeDatatable.addColumn("number", "C");
    subjectsByGradeDatatable.addColumn("number", "D");
    subjectsByGradeDatatable.addColumn("number", "E");
    subjectsByGradeDatatable.addColumn("number", "U");
    let generateGradeTooltip = function(aStar, A, B, C, D, E, U, total, subjectName){
        let message = "<h3 class='subjectNameTooltip'>"+subjectName+"</h3>" + "<table class='gradesTable'>" +
            "<tr><th>Grade</th><th>Percent</th></tr>" +
            "<tr><td><span class='highlight aStar'>A*</span></td><td>"+Math.round((aStar/total*100))+"%</td></tr>" +
            "<tr><td><span class='highlight A'>A</span></td><td>"+Math.round(A/total*100)+"%</td></tr>" +
            "<tr><td><span class='highlight B'>B</span></td><td>"+Math.round(B/total*100)+"%</td></tr>" +
            "<tr><td><span class='highlight C'>C</span></td><td>"+Math.round(C/total*100)+"%</td></tr>" +
            "<tr><td><span class='highlight D'>D</span></td><td>"+Math.round(D/total*100)+"%</td></tr>" +
            "<tr><td><span class='highlight E'>E</span></td><td>"+Math.round(E/total*100)+"%</td></tr>" +
            "<tr><td><span class='highlight U'>U</span></td><td>"+Math.round(U/total*100)+"%</td></tr>" +
            "</table>";
        return message;
    };
    for (let subject in totalGradeParticipationData) {
        let gradeArray = [
            totalGradeParticipationData[subject]["A*"],
            totalGradeParticipationData[subject]["A"],
            totalGradeParticipationData[subject]["B"],
            totalGradeParticipationData[subject]["C"],
            totalGradeParticipationData[subject]["D"],
            totalGradeParticipationData[subject]["E"],
            totalGradeParticipationData[subject]["U"]
        ];
        let tooltipText = generateGradeTooltip(...gradeArray.concat(totalGradeParticipationData[subject]["total"], subject));
        gradeArray.reverse();
        subjectsByGradeDatatable.addRow([subject, tooltipText].concat(gradeArray));
    }
    let containterId = "gradesColumnChartContainer";
    let columnGradeChartOptions = {
        "title": "A Level achievement by subject",
        height: 800,
        isStacked: "percent",
        legend: {
            position: "top"
        },
        focusTarget: "category",
        backgroundColor: "#ffd98f",
        tooltip:{trigger: "selection", isHtml: true},
        chartArea:{width: "90%"},
        colors:["#4c874c", "#5c9b5c", "#66af66", "#6cc36c", "#74d774", "#88eb88", "#9aff9a"]
    };
    let gradesColumnChartConstructor = {
        "chartType": "ColumnChart",
        "containerId": containterId,
        "dataTable": subjectsByGradeDatatable,
        "options": columnGradeChartOptions
    };
    let gradesColumnChart = new google.visualization.ChartWrapper(gradesColumnChartConstructor);
    gradesColumnChart.draw();
}
function reDrawGraphs(){
    redrawPieChart();
    redrawHomeDashboard();
}



//these are legacy functions that should never have to be repeated, and at some point could be phased out
function redrawPieChart(){
    var datatable = new google.visualization.DataTable();
    datatable.addColumn("string", "Gender");
    datatable.addColumn("number", "Participation");
    datatable.addRow(["Male", totalGenderParticipationData.male]);
    datatable.addRow(["Female", totalGenderParticipationData.female]);
    var pieChartOptions = {
        "title": "Overall A Level gender participation",
        height: 500,
        legend: {
            position: "top"
        },
        backgroundColor: "#ffd98f",
        pieHole: 0.5,
        tooltip:{trigger: "selection", isHtml: false},
        colors:["dodgerblue", "hotpink"]
    };
    let pieChartConstructor = {
        "chartType": "PieChart",
        "containerId": "homepagePieChartContainer",
        "dataTable": datatable,
        "options": pieChartOptions
    };
    let pieChart = new google.visualization.ChartWrapper(pieChartConstructor);
    pieChart.draw();
}
function redrawHomeDashboard(){
    var datatable = new google.visualization.DataTable();
    datatable.addColumn("string", "Subject");
    datatable.addColumn({"type": "string", "role": "tooltip", "p":{"html":true}});
    datatable.addColumn("number", "Male");
    datatable.addColumn("number", "Female");
    datatable.addColumn("number", "total");
    function generateTooltip(subject, male, female){
        let totalParticipation = male + female;
        let percentageMale = Math.round(male/totalParticipation*100);
        let percentageFemale = Math.round(female/totalParticipation*100);
        let button = "<a href='?subject="+encodeURIComponent(subject)+"'><button class='bigButton'>View</button></a>";
        return "<b>Participation in "+subject+"</b>: <br>" +
            "Total: <span class='totalParticipation highlight'>"+totalParticipation+"</span><br>" +
            "<div class='tooltipMaleFemaleStats'>Male <span class='percentageMale highlight'>"+percentageMale+"%</span> - " +
            "<span class='percentageFemale highlight'>"+percentageFemale+"%</span> Female</div>"
            +button;
    }
    let countLimit = 0;
    for (let subject in data) {
        let tooltipText = generateTooltip(subject, data[subject].male, data[subject].female);
        datatable.addRow([subject, tooltipText, data[subject].male, data[subject].female, data[subject].total]);
    }
    let dashboardDiv = document.getElementById("homepageDashboard");
    var mainDashboard = new google.visualization.Dashboard(dashboardDiv);

    var columnChartOptions = {
        "title": "Gender participation rates in Edexcel A Level subjects",
        height: 500,
        legend: {
            position: "none"
        },
        backgroundColor: "#ffd98f",
        isStacked: true,
        hAxis: {textStyle:{fontSize:10}},
        focusTarget: "category",
        tooltip:{trigger: "selection", isHtml: true},
        colors:["dodgerblue", "hotpink"]
    };
    let columnChartConstructor = {
        "chartType": "ColumnChart",
        "containerId": "columnChartContainer",
        "options": columnChartOptions,
        "view": {"columns": [0, 1, 2, 3]}
    };
    let columnChart = new google.visualization.ChartWrapper(columnChartConstructor);

    let subjectFilterOptions = {
        "controlType": "StringFilter",
        "containerId": "subjectsFilterContainer",
        "options": {
            "filterColumnLabel": "Subject",
            "ui":{
                "label": "Search for a subject"
            }
        }
    };
    let subjectSearcher = new google.visualization.ControlWrapper(subjectFilterOptions);
    mainDashboard.bind(subjectSearcher, columnChart);
    let participationFilterOptions = {
        "controlType": "NumberRangeFilter",
        "containerId": "participationFilterContainer",
        "options": {
            "filterColumnLabel": "total",
            "minValue":0,
            "ui":{
                "showRangeValues":false,
                "label": "Total participation",
                "step": 1000,
                "labelStacking":"vertical"
            }
        },
        "state":{
            "lowValue":4000,
            "highValue": 15000,
        }
    };
    let participationNumberFilter = new google.visualization.ControlWrapper(participationFilterOptions);
    mainDashboard.bind(participationNumberFilter, columnChart);
    mainDashboard.draw(datatable);
}