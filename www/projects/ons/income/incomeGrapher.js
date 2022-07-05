google.charts.load("current");
google.charts.setOnLoadCallback(drawIncomeChart);

function drawIncomeChart(){
    let optionsRequest = new XMLHttpRequest();
    optionsRequest.onreadystatechange = () => {
        if(optionsRequest.readyState===4&&optionsRequest.status===200){
            var options = JSON.parse(optionsRequest.response);
            console.log('Options:', options)
            var columnDatatable = new google.visualization.DataTable();
            columnDatatable.addColumn("string", "Percentile");
            columnDatatable.addColumn("number", "Income");
            var poundFormatter = new google.visualization.NumberFormat({pattern: "£#,###.##"});
            for(let option in options){
                columnDatatable.addRow([options[option], 29079]);
            }
            poundFormatter.format(columnDatatable, 1);
            var columnOptions = {
                title: "Annual income in England",
                animation:{ease: "in", duration: 500},
                height: 700,
                vAxis:{maxValue: 60000, format: "£#,###.##"},
                colors: ["#8c7ae6"],
                backgroundColor: "#dcdde1",
                legend: {position: "none"}
            };
            let columnConstructor = {
                chartType: "ColumnChart",
                containerId: "incomePercentileChartContainer",
                dataTable: columnDatatable,
                "options": columnOptions
            };
            var columnWrapper = new google.visualization.ChartWrapper(columnConstructor);
            google.visualization.events.addOneTimeListener(columnWrapper, "ready", fetchData);
            columnWrapper.draw();
            function fetchData(){
                for(let option in options){
                    retrieveObservation(option, options[option]);
                }
            }
            function retrieveObservation(dimensionOption, dimensionLabel){
                let row = dimensionLabel.match(/^[1-9]/)-1;
                let observationRequest = new XMLHttpRequest();
                observationRequest.onreadystatechange = () => {
                    if(observationRequest.readyState===4 && observationRequest.status===200){
                        let value=observationRequest.response;
                        columnDatatable.setCell(row, 1, Number(value));
                        poundFormatter.format(columnDatatable, 1);
                        columnWrapper.draw();
                    }
                };
                observationRequest.open("GET", "controller.php?option="+dimensionOption, true);
                observationRequest.send();
            }
        }
    };
    optionsRequest.open("GET", "controller.php?option=list", true);
    optionsRequest.send();


}