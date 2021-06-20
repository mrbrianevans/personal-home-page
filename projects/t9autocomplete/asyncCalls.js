function getPrediction(){
    let typed = document.getElementById("input").value;
    let url = "responder.php?typed="+typed;
    url = "database/api.php?typed="+typed;
    let outputBox = document.getElementById("predictionWord");
    let predictionCall = new XMLHttpRequest();
    predictionCall.onreadystatechange = () => {
        // console.log("State: "+predictionCall.readyState+" status: "+predictionCall.status);
        if(predictionCall.readyState===4 && predictionCall.status===200){
            outputBox.innerText = predictionCall.response;
        }
    };
    predictionCall.open("GET", url, true);
    predictionCall.send();
}
function appendNumber(number){
    let typed = document.getElementById("input");
    typed.value += number;
    getPrediction();
}

function backspace(){
    let typed = document.getElementById("input");
    typed.value = typed.value.substring(0, typed.value.length-1);
    getPrediction();
}