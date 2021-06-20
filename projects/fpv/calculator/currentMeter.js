function showCurrentMeterInputs(){
    document.getElementById("inputArea").innerHTML = "";
    let currentBlock = document.createElement("form");
    document.getElementById("inputArea").appendChild(currentBlock);
    currentBlock.id = "currentCalibrationContainer";

    let addNewRowButton = document.createElement("button");
    addNewRowButton.id = "addNewRow";
    addNewRowButton.type = "button";
    addNewRowButton.addEventListener("click", addRowOfCurrentInputs);
    addNewRowButton.innerText = "Add new measurement";
    currentBlock.appendChild(addNewRowButton);
    addNewRowButton.insertAdjacentHTML("afterend", "<br>");

    let finishButton = document.createElement("button");
    finishButton.id = "finishButton";
    finishButton.addEventListener("click", submitCurrent);
    finishButton.innerText = "Calculate";
    finishButton.type = "submit"; //TODO: Make this submit for production (only button for testing purposes)
    currentBlock.appendChild(finishButton);

    let scaleBox = document.createElement("input");
    let scaleLabel = document.createElement("label");
    scaleBox.id = "scaleInput";
    scaleBox.name = "scale";
    scaleLabel.for = "scaleInput";
    scaleLabel.id = "scaleLabel";
    scaleBox.placeholder = "127";
    scaleLabel.innerText = "Current scale in Betaflight: ";
    finishButton.insertAdjacentElement("beforebegin", scaleLabel);
    finishButton.insertAdjacentElement("beforebegin", scaleBox);
    addRowOfCurrentInputs();

}
function addRowOfCurrentInputs(){
    let formBox = document.getElementById("currentCalibrationContainer");
    let rowNumber = (formBox.childElementCount+4)/7;

    let measuredBox = document.createElement("input");
    let measuredLabel = document.createElement("label");
    measuredBox.id = "measuredCurrentInput"+rowNumber;
    measuredLabel.for = "measuredCurrentInput"+rowNumber;
    measuredBox.placeholder = "2056";
    measuredLabel.innerText = "Measured Current draw from OSD (mAh): ";

    let chargedBox = document.createElement("input");
    let chargedLabel = document.createElement("label");
    chargedBox.id = "chargedCurrentInput"+rowNumber;
    chargedLabel.for = "chargedCurrentInput"+rowNumber;
    chargedBox.placeholder = "1273";
    chargedLabel.innerText = "Amps charged (mAh): ";

    let finishButton = document.getElementById("scaleLabel");
    finishButton.insertAdjacentElement("beforebegin", measuredLabel);
    finishButton.insertAdjacentElement("beforebegin", measuredBox);
    finishButton.insertAdjacentElement("beforebegin", chargedLabel);
    finishButton.insertAdjacentElement("beforebegin", chargedBox);
    finishButton.insertAdjacentHTML("beforebegin", "<br>");
}

function submitCurrent(){
    let formBox = document.getElementById("currentCalibrationContainer");
    for(let element in formBox.children){
        if(formBox.children[element].id!=null && formBox.children[element].id.match(/chargedCurrentInput[0-9]*/)){
            formBox.children[element].name = formBox.children[element-2].value;
        }
    }
}