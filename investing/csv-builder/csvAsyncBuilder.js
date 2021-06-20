function addNewTransaction(){
    let folder = document.cookie.match(/PHPSESSID=([^;]+)/)[1];
    //sanitize input
    let instrument = document.getElementById("newInstrument").value;
    let price = document.getElementById("newPrice").value;
    let date = document.getElementById("newDate").value;
    let direction = document.getElementById("newDirection").value;
    let quantity = document.getElementById("newQuantity").value;
    // alert("Transaction: "+direction+" "+quantity+" "+instrument+"'s for $"+price+" each on "+date);
    displayTransactionForm();
    let additionRequest = new XMLHttpRequest();
    additionRequest.onreadystatechange = function(){
        if(additionRequest.readyState === 4 && additionRequest.status !== 200){
            alert("Failed to add transaction");
        }
        else if(additionRequest.readyState === 4 && additionRequest.status === 200){
            let newRow = document.getElementById("transactionTable").insertRow(1);
            newRow.insertCell(0).innerHTML = instrument;
            newRow.insertCell(1).innerHTML = quantity;
            newRow.insertCell(2).innerHTML = direction;
            newRow.insertCell(3).innerHTML = price;
            newRow.insertCell(4).innerHTML = date;
        }
    };
    let url = "additionHandler.php?folder="+folder+"&instrument="+instrument+"&price="+
                price+"&quantity="+quantity+"&direction="+direction+"&datetime="+date;
    additionRequest.open("GET", url, true);
    additionRequest.send();

}

function displayTransactionForm(){
    document.getElementById("showFormButton").style.display = "none";
    //this massive block sets the default date
    let aWeekAgo = new Date();
    aWeekAgo.setDate(aWeekAgo.getDate()-3); // three days ago
    //TODO: Zero pad month
    //TODO: If date is 0 or 6 then move it back one
    let month = (1+aWeekAgo.getMonth()).toString();
    if(month.length === 1)
        month = "0" + month;
    let day = aWeekAgo.getDay();
    let date =  aWeekAgo.getDate();
    if(day===0)
        aWeekAgo.setDate(date-2);
    if(day===6)
        aWeekAgo.setDate(date-1);
    date =  aWeekAgo.getDate().toString();
    if(date.length === 1)
        date = "0" + date;
    aWeekAgo = aWeekAgo.getFullYear()+"-"+month+"-"+date;

    document.getElementById("transactionForm").innerHTML = "" +
        "<form id='newTransactionForm' method='get' action='index.php'>" +
        "<div id='recommendations'></div>"+
        "<input id='newInstrument' type='text' placeholder='Instrument' oninput='offerInstrumentOptions()'/>" +
        "<input id='newDate' type='date' value='"+aWeekAgo+"' oninput='fillPrice()'/>" +
        "<input id='newPrice' type='text' placeholder='Price' />" +
        "<input id='newQuantity' type='text' placeholder='Quantity' />" +
        "<select id='newDirection'>" +
        "<option>Buy</option>" +
        "<option>Sell</option>" +
        "</select>" +
        "<input type='button' onclick='addNewTransaction()' value='Add to list'/>" +
        "</form>";
}

function offerInstrumentOptions(){
    fillPrice();
    let instr = document.getElementById("newInstrument").value;
    if(instr!=="Instrument" && instr!==""){
        let requestURL = "../predictive.php?instr="+instr;
        let instrRequest = new XMLHttpRequest();
        instrRequest.onreadystatechange = () => {
            if(instrRequest.readyState===4&&instrRequest.status===200){
                let recommendations = JSON.parse(instrRequest.response);
                document.getElementById("recommendations").innerHTML = "";
                for(let index in recommendations){
                    let newRec = document.createElement("SPAN");
                    newRec.className  = "recommendation";
                    newRec.innerHTML = recommendations[index];
                    newRec.setAttribute("onclick", "fillRecommendation('"+recommendations[index]+"')");
                    document.getElementById("recommendations").appendChild(newRec);
                }
            }
        };
        instrRequest.open("GET", requestURL, true);
        instrRequest.send();
    }
}

function fillRecommendation(recommendation){
    document.getElementById("recommendations").innerHTML = "";
    document.getElementById("newInstrument").value = recommendation;
    fillPrice(recommendation);
}

function fillPrice(){
    let priceRequest = new XMLHttpRequest();
    let instrument = document.getElementById("newInstrument").value;
    let date = document.getElementById("newDate").value;
    let requestURL = "../predictive.php?instrument="+instrument+"&date="+date;
    priceRequest.onreadystatechange = () => {
        if (priceRequest.readyState === 4 && priceRequest.status === 200) {
            let price = priceRequest.response;
            if(price.toString().length > 2)
                document.getElementById("newPrice").value = price;
        }
    };
    priceRequest.open("GET", requestURL, true);
    priceRequest.send();
}