function loadSicOptions() {
    // let sicRequest = new XMLHttpRequest();
    // sicRequest.onreadystatechange = () => {
    //     console.log(" state:" +sicRequest.readyState+" status:" + sicRequest.status)
    //     if(sicRequest.readyState === 4 && sicRequest.status===200){
    //         console.log(sicRequest.response);
    //     }
    // };
    // sicRequest.open("GET", "controller.php?action=sic-options", true);
    // sicRequest.send();

    fetch("controller.php?action=sic-options")
        .then(r => r.json())
        .then(listOfSic => {
            for(let sic in listOfSic){
                let newOption = document.createElement("option");
                newOption.value = sic;
                newOption.innerText = listOfSic[sic];
                document.getElementById("sic").appendChild(newOption);
            }
        })
}