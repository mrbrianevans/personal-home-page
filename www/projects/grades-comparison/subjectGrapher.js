google.charts.load('current', {'packages': ['corechart', 'controls']});
google.charts.setOnLoadCallback(drawGradesColumnChart);
google.charts.setOnLoadCallback(drawGenderPieChart);

function drawGenderPieChart(){
    var datatable = new google.visualization.DataTable();
    datatable.addColumn("string", "Gender");
    datatable.addColumn("number", "Participation");
    let subjectName;
    for (let subject in genderData) {
        subjectName = decodeURIComponent(subject);
        datatable.addRow(["Male", genderData[subject].male]);
        datatable.addRow(["Female", genderData[subject].female]);
    }
    let subjectsPieChart = document.createElement("div");
    subjectsPieChart.id = "pieChartContainer";
    document.getElementById("subjectDashboard").appendChild(subjectsPieChart);
    var pieChartOptions = {
        "title": "Gender participation",
        height: 500,
        legend: {
            position: "top"
        },
        backgroundColor: "#ffd98f",
        pieHole: 0.5,
        tooltip:{trigger: "selection", isHtml: true, showColorCode: true},
        colors:["dodgerblue", "hotpink"],
        chartArea:{width:"100%"},
        focusTarget: "category"
    };
    let pieChartConstructor = {
        "chartType": "PieChart",
        "containerId": "pieChartContainer",
        "dataTable": datatable,
        "options": pieChartOptions
    };
    let pieChart = new google.visualization.ChartWrapper(pieChartConstructor);
    pieChart.draw();
}

function drawGradesColumnChart(){
    var datatable = new google.visualization.DataTable();
    datatable.addColumn("string", "Grade");
    datatable.addColumn("number", "Male");
    datatable.addColumn("number", "Female");
    for (let grade in gradeData) {
        datatable.addRow([grade, gradeData[grade].male, gradeData[grade].female]);
    }
    let percentFormatter = new google.visualization.NumberFormat({pattern: "#,###%"});
    percentFormatter.format(datatable, 1);
    percentFormatter.format(datatable, 2);
    let gradeColumnChartContainer = document.createElement("div");
    gradeColumnChartContainer.id = "gradeColumnChartContainer";
    document.getElementById("subjectDashboard").appendChild(gradeColumnChartContainer);
    var columnChartOptions = {
        "title": "Results by gender",
        height: 500,
        legend: {
            position: "none"
        },
        backgroundColor: "#ffd98f",
        tooltip:{trigger: "selection", isHtml: false},
        colors:["dodgerblue", "hotpink"],
        focusTarget: "category",
        hAxis:{"title":"Grade"},
        isStacked: "false",
        vAxis:{format:"percent"}
    };
    let pieChartConstructor = {
        "chartType": "ColumnChart",
        "containerId": "gradeColumnChartContainer",
        "dataTable": datatable,
        "options": columnChartOptions
    };
    let columnChart = new google.visualization.ChartWrapper(pieChartConstructor);
    columnChart.draw();
}