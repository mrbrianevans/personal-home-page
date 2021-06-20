google.charts.load('current', {'packages': ['corechart', 'controls', 'geochart']});
google.charts.setOnLoadCallback(createHomepageDashboard);
// google.charts.setOnLoadCallback(drawGenderPieChart);

function createHomepageDashboard(){
    //make this datatable switchable to please

    var datatable = new google.visualization.DataTable();
    datatable.addColumn("string", "Subject");
    datatable.addColumn({"type": "string", "role": "tooltip", "p":{"html":true}});
    datatable.addColumn("number", "Male");
    datatable.addColumn("number", "Female");
    function generateTooltip(subject, male, female){
        let totalParticipation = male + female;
        let percentageMale = Math.round(male/totalParticipation*100);
        let percentageFemale = Math.round(female/totalParticipation*100);
        return "<b>Participation in "+subject+"</b>: <br>" +
            "Total: <span class='totalParticipation highlight'>"+totalParticipation+"</span><br>" +
            "<div class='tooltipMaleFemaleStats'>Male <span class='percentageMale highlight'>"+percentageMale+"%</span> - " +
            "<span class='percentageFemale highlight'>"+percentageFemale+"%</span> Female</div>";
    }
    let countLimit = 0;
    for (let subject in data) {
        if(++countLimit>20) break;
        let tooltipText = generateTooltip(subject, data[subject].male, data[subject].female);
        datatable.addRow([subject, tooltipText, data[subject].male, data[subject].female]);
    }

    var columnChartOptions = {
        "title": "Enrollment in A Level subjects",
        width: 750,
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
        "containerId": "gradesChartContainer",
        "options": columnChartOptions,
        "dataTable": datatable
    };
    let columnChart = new google.visualization.ChartWrapper(columnChartConstructor);
    columnChart.draw();
}