async function updateCoronavirusStats(){
    fetch("/coronavirus/data_handler.php?latest=true&country=usa&stat=deaths").then(async (r) => {
        document.getElementById("usacoronavirusdeathcount").innerText = await r.text();
    });
    fetch("/coronavirus/data_handler.php?latest=true&country=uk&stat=deaths").then(async (r) => {
        document.getElementById("ukcoronavirusdeathcount").innerText = await r.text();
    });
    await getTimeOfUpdate();
}
function continuouslyUpdateStats(){
    updateCoronavirusStats();
    setInterval(updateCoronavirusStats, 1_200_000); // every two minutes it will load the most recent data
}

async function getTimeOfUpdate(){
    let xhttps = new XMLHttpRequest();
    xhttps.onreadystatechange = function(){
        if(this.readyState===4 && this.status===200){
            document.getElementById("coronaviruslastupdated").innerText = "Last updated: " + this.response;
        }
    };
    xhttps.open("GET", "/coronavirus/data_handler.php?latest=true&date=true", true);
    xhttps.send();

}
function getStoredData(){
    if (document.getElementById("chinavirus").innerText !=""){
        document.getElementById("chinavirus").innerText = "";
    }else{
        let xhttps = new XMLHttpRequest();
        xhttps.onreadystatechange = function(){
            if(this.readyState===4){
                let data = JSON.parse(this.response);
                data = "UK deaths: " + data.uk_deaths + "\nUK cases: "+data.uk_cases+"\nUSA deaths: " + data.usa_deaths + "\nUSA cases: "+data.usa_cases;
                document.getElementById("chinavirus").innerText = data;
            }
        };
        xhttps.open("GET", "/coronavirus/data_handler.php?latest=true", true);
        xhttps.send();
    }
}