function showEntryBox(contestID, action){
    var funcName;
    if(action=="Edit entry")
        funcName = 'editPrediction';
    else if(action=="Enter contest")
        funcName = 'enterPrediction';
    else
        alert("A function name error has occurred. Please speak to Brian");

    let username = document.getElementById("usernametop").innerText;
    let prediction = document.getElementById(username+contestID);
    if(prediction)
        prediction = prediction.innerText;
    else
        prediction = "Prediction";

    document.getElementById(contestID + "td").innerHTML =
        "<form class='green'>" +
        "<input id='predictionEntry"+contestID+"' type='text' value='"+prediction+"' onclick='clearBox(\"predictionEntry"+contestID+"\")' onblur='setToDefaultIfEmpty(\"predictionEntry"+contestID+"\")'/>" +
        "<input type='submit' onclick='"+funcName+"(\""+contestID+"\")' value='Submit prediction'/></form>";
}

function enterPrediction(contestID){
    let username = document.getElementById("usernametop").innerText;
    var prediction = document.getElementById("predictionEntry"+contestID).value;
    var urlToGet = "/predictions/request_handler.php?newentry=True&contestID="+contestID+"&username="+username+"&prediction="+prediction;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(this.readyState===4 && this.status===500){
            window.alert("Sorry, an error occurred please ask Brian for some help. "+this.response+"\nRequest was made out to "+urlToGet);
        }else if(this.readyState===4 && this.status===200){
            // window.alert(this.response);
            document.getElementById(contestID + "td").innerHTML = "Contest entered";
        }
    };
    xhttp.open("GET", urlToGet, true);
    xhttp.send();
}

function editPrediction(contestID){
    let username = document.getElementById("usernametop").innerText;
    var prediction = document.getElementById("predictionEntry"+contestID).value;
    var urlToGet = "/predictions/request_handler.php?edit-entry=True&contestID="+contestID+"&username="+username+"&prediction="+prediction;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(this.readyState===4 && this.status===500){
            window.alert("Sorry, an error occurred please ask Brian for some help. "+this.response+"\nRequest was made out to "+urlToGet);
        }else if(this.readyState===4 && this.status===200){
            // window.alert(this.response);
            document.getElementById(contestID + "td").innerHTML = "Entry updated successfully";
        }
    };
    xhttp.open("GET", urlToGet, true);
    xhttp.send();
}
function showRequestForm(){
    let form = "<input id='requestBox' type='text'/><button class='green' onclick='submitContestRequest()'>Submit request</button>";
    document.getElementById('showRequestFormForm').innerHTML = form;
}
function submitContestRequest(){
    let username = document.getElementById("usernametop").innerText;
    let requestDesc = document.getElementById("requestBox").value;
    let form = "<button class='green' onclick='showRequestForm()'>Request a new contest</button>";
    document.getElementById('showRequestFormForm').innerHTML = "Requesting...";
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        if (this.response === "Success"){
            document.getElementById('showRequestFormForm').innerHTML = form+this.response;
        }
    };
    xhttp.open("POST", "/predictions/request_handler.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("username="+username+"&requested-contest="+requestDesc);
}