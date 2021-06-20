google.charts.load('current', {
    'packages':['geochart']
});
google.charts.setOnLoadCallback(drawMaps);

function drawMaps(){
    let divwidth = document.getElementById("bluebox").clientWidth;
    document.getElementById("mapofamerica").height = divwidth/2;
    document.getElementById("secondmapofamerica").height = divwidth/2;
    var declineDaysRequest = new XMLHttpRequest();
    declineDaysRequest.onreadystatechange = function(){
        if(declineDaysRequest.readyState===4){
            const data = JSON.parse(this.response);
            var datatable = google.visualization.arrayToDataTable([
                [{label:'State', type:'string'},   {label:'Average days of decline', type:'number'}]
            ]);
            for (var state in data){
                datatable.addRow([state, (data[state])]);
            }
            var options = {
                region: 'US',
                resolution: 'provinces',
                displayMode: 'regions',
                colorAxis: {colors: ['#f54e42', '#66f542']},
                backgroundColor: "#03a9fc",
                defaultColor: "#fc03e8",
                datalessRegionColor: "#3b3b3b",
                height:divwidth/2
            };

            var map = new google.visualization.GeoChart(document.getElementById("mapofamerica"));
            map.draw(datatable, options);
        }
    };
    declineDaysRequest.open("GET", "request_handler.php?usa-stored-data=true", true);
    declineDaysRequest.send();

    var totalDeathsRequest = new XMLHttpRequest();
    totalDeathsRequest.onreadystatechange = function(){
        if(totalDeathsRequest.readyState===4){
            const data = JSON.parse(totalDeathsRequest.response);
            var deathsDatatable = google.visualization.arrayToDataTable([
                [{label:'State', type:'string'},   {label:'Total COVID deaths', type:'number'}]
            ]);
            var totalCounted = 0;
            for (var state in data){
                deathsDatatable.addRow([state, (data[state])]);
                totalCounted += data[state];
            }
            var options = {
                title: "Distibution of the "+totalCounted+" recorded deaths in America",
                region: 'US',
                resolution: 'provinces',
                displayMode: 'regions',
                colorAxis: {colors: ['#32a852', '#ffd91c', '#f54e42']},
                backgroundColor: "#03a9fc",
                defaultColor: "#fc03e8",
                datalessRegionColor: "#3b3b3b",
            };

            var map = new google.visualization.GeoChart(document.getElementById("secondmapofamerica"));
            map.draw(deathsDatatable, options);
        }
    };
    totalDeathsRequest.open("GET", "request_handler.php?usa-map-deaths=true", true);
    totalDeathsRequest.send();

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(xhttp.readyState===4){
            const data = JSON.parse(this.response);
            var newCasesDatatable = google.visualization.arrayToDataTable([
                [{label:'State', type:'string'},   {label:'Average days of decline in new cases', type:'number'}]
            ]);
            for (var state in data){
                newCasesDatatable.addRow([state, (data[state])]);
            }

            var options = {
                region: 'US',
                resolution: 'provinces',
                displayMode: 'regions',
                colorAxis: {colors: ['#f54e42', '#66f542']},
                backgroundColor: "#03a9fc",
                defaultColor: "#fc03e8",
                datalessRegionColor: "#3b3b3b"
            };

            var map = new google.visualization.GeoChart(document.getElementById("thirdmapofamerica"));
            map.draw(newCasesDatatable, options);
        }
    };
    xhttp.open("GET", "request_handler.php?usa-new-case-stored-data=true", true);
    xhttp.send();
}