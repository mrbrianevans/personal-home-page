function outputlink(){
    var originalLink = document.getElementById("banggoodlinkbox").value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if(this.status===200){
            document.getElementById("referal-link").innerHTML = "<a href='"+this.responseText+"'>Affiliate link</a>";
        }
    };
    xhttp.open("GET", "request_handler.php?link="+originalLink, true);
    xhttp.send();
}


function suggest(part) {
    let typed = document.getElementById(part+"TextBox").value;
    let cursorPosition = document.getElementById(part + "TextBox").selectionStart;
    let className = document.getElementById("classTextBox").value;
    let url = "suggestions.php?part="+part+"&typed="+typed.substring(0, cursorPosition)+"&class="+className;
    let partRequest = new XMLHttpRequest();
    partRequest.onreadystatechange = () => {
        if(partRequest.readyState===4 && partRequest.status===200 && partRequest.response.length > 0){
            document.getElementById(part+"TextBox").value = partRequest.response;
            document.getElementById(part+"TextBox").selectionStart = cursorPosition;
            document.getElementById(part+"TextBox").selectionEnd = cursorPosition;
        }
    };

    partRequest.open("GET", url, true);
    partRequest.send();
}
function removeCharacter(part) {
    let textbox = document.getElementById(part+"TextBox");
    textbox.value = textbox.value.substring(0, textbox.selectionStart);
}

function showNewQuadForm(type){
    let addNewButton = document.getElementById("plus");
    addNewButton.removeEventListener("click", showNewQuadForm);
    addNewButton.style.fontSize = "medium";
    addNewButton.style.cursor = "default";
    addNewButton.innerText = "";
    if(type==="fantasy")
        addNewButton.style.backgroundColor = "#f07e7e";
    else if(type==="real")
        addNewButton.style.backgroundColor = "#7dfb7c";
    let formContainer = document.createElement("div");
    formContainer.style.maxWidth = addNewButton.clientWidth + "px";
    formContainer.style.maxHeight = addNewButton.clientHeight + "px";
    formContainer.style.padding = "0";


    let formHtml = document.createElement("form");
    formContainer.appendChild(formHtml);
    formHtml.style.textAlign = "right";
    formHtml.id = "newQuadForm";
    formHtml.method = "post";
    formHtml.style.height = "available";
    formHtml.style.margin = "0";
    formHtml.style.backgroundColor = "transparent";

    let built = document.createElement("input");
    built.type="hidden";
    built.name="built";
    built.value=type==="fantasy"?"0":"1";
    formHtml.appendChild(built);

    let classTextBox = document.createElement("input");
    classTextBox.type = "text";
    classTextBox.placeholder = "5 inch";
    classTextBox.id = "classTextBox";
    classTextBox.tabIndex = 1;
    classTextBox.autocomplete = "off";
    classTextBox.name = "class";
    let classLabel = document.createElement("label");
    classLabel.innerHTML = "Class: ";
    classLabel.for = "classTextBox";
    classLabel.appendChild(classTextBox);
    formHtml.appendChild(classLabel);
    classTextBox.addEventListener("keyup", function(){suggest("class")});
    classTextBox.addEventListener("keypress", function(){removeCharacter("class")});

    let motorTextBox = document.createElement("input");
    motorTextBox.type = "text";
    motorTextBox.placeholder = "2306 2200kV iFlight Xing-E";
    motorTextBox.id = "motorTextBox";
    motorTextBox.tabIndex = 2;
    motorTextBox.autocomplete = "off";
    motorTextBox.name = "motors";
    let motorLabel = document.createElement("label");
    motorLabel.innerHTML = "<br>Motors: ";
    motorLabel.for = "motorTextBox";
    motorLabel.appendChild(motorTextBox);
    formHtml.appendChild(motorLabel);
    motorTextBox.addEventListener("keyup", function(){suggest("motor")});
    motorTextBox.addEventListener("keypress", function(){removeCharacter("motor")});

    let escTextBox = document.createElement("input");
    escTextBox.type = "text";
    escTextBox.placeholder = "60A RacerStar Metal V2";
    escTextBox.id = "escTextBox";
    escTextBox.tabIndex = 3;
    escTextBox.autocomplete = "off";
    escTextBox.name = "esc";
    let escLabel = document.createElement("label");
    escLabel.innerHTML = "<br>ESC: ";
    escLabel.for = "escTextBox";
    escLabel.appendChild(escTextBox);
    formHtml.appendChild(escLabel);
    escTextBox.addEventListener("keyup", function(){suggest("esc")});
    escTextBox.addEventListener("keypress", function(){removeCharacter("esc")});

    let flightControllerTextBox = document.createElement("input");
    flightControllerTextBox.type = "text";
    flightControllerTextBox.placeholder = "Holybro F7";
    flightControllerTextBox.id = "flightControllerTextBox";
    flightControllerTextBox.tabIndex = 4;
    flightControllerTextBox.autocomplete = "off";
    flightControllerTextBox.name = "flightController";
    let flightControllerLabel = document.createElement("label");
    flightControllerLabel.innerHTML = "<br>Flight Controller: ";
    flightControllerLabel.for = "flightControllerTextBox";
    flightControllerLabel.appendChild(flightControllerTextBox);
    formHtml.appendChild(flightControllerLabel);
    flightControllerTextBox.addEventListener("keyup", function(){suggest("flightController")});
    flightControllerTextBox.addEventListener("keypress", function(){removeCharacter("flightController")});

    let frameTextBox = document.createElement("input");
    frameTextBox.type = "text";
    frameTextBox.placeholder = "Apex V2";
    frameTextBox.id = "frameTextBox";
    frameTextBox.tabIndex = 4;
    frameTextBox.autocomplete = "off";
    frameTextBox.name = "frame";
    let frameLabel = document.createElement("label");
    frameLabel.innerHTML = "<br>Frame: ";
    frameLabel.for = "frameTextBox";
    frameLabel.appendChild(frameTextBox);
    formHtml.appendChild(frameLabel);
    frameTextBox.addEventListener("keyup", function(){suggest("frame")});
    frameTextBox.addEventListener("keypress", function(){removeCharacter("frame")});

    formHtml.appendChild(document.createElement("br"));


    let cancelButton = document.createElement("input");
    cancelButton.type = "button";
    cancelButton.value = "Cancel";
    cancelButton.addEventListener("click", resetPlusBox);
    formHtml.appendChild(cancelButton);



    let submitButton = document.createElement("input");
    submitButton.type = "submit";
    submitButton.value = "Add";
    submitButton.id = "submitNewQuadButton";
    submitButton.tabIndex = 5;
    submitButton.name = "submitNewQuadButton";
    formHtml.appendChild(submitButton);


    addNewButton.appendChild(formContainer);
}

function highlightPart(part, id){
    let specificQuad = document.getElementById(id);
    let partName = part.match(/^[a-zA-Z]+/g);
    let components = specificQuad.children;
    for(let component = 0; component < components.length; component++){
        let partTagsFormat = new RegExp(partName, 'g');
        if(components[component].id.match(partTagsFormat)){
            components[component].style.fillOpacity = "100%";
        }
    }

    let quadNumber = id.match(/[0-9]+/g);
    document.getElementById(partName+quadNumber).style.backgroundColor = "whitesmoke";
    document.getElementById(partName+quadNumber).style.border = "1px dotted whitesmoke";
}
function removeEventListeners(partName, id){
    let quadNumber = id.match(/[0-9]+/g);
    document.getElementById(partName+quadNumber).setAttribute("onmouseout", "");
    let specificQuad = document.getElementById(id);
    let components = specificQuad.children;
    for(let component = 0; component < components.length; component++){
        let partTagsFormat = new RegExp(partName, 'g');
        if(components[component].id.match(partTagsFormat)){
            components[component].setAttribute("onmouseout", "");
        }
    }
}
function reAddEventListeners(part, id){
    let partName = part.match(/^[a-zA-Z]+/g);
    let quadNumber = id.match(/[0-9]+/g);
    document.getElementById(partName+quadNumber).setAttribute("onmouseout", "unhighlightPart('"+part+"', '"+id+"')");
    let specificQuad = document.getElementById(id);
    let components = specificQuad.children;
    for(let component = 0; component < components.length; component++){
        let partTagsFormat = new RegExp(partName, 'g');
        if(components[component].id.match(partTagsFormat)){
            components[component].setAttribute("onmouseout", "unhighlightPart('"+part+"', '"+id+"')");
        }
    }
}
function clickOnPart(part, id){
    let partName = part.match(/^[a-zA-Z]+/g);
    let quadNumber = id.match(/[0-9]+/g);
    if(document.getElementById(partName+quadNumber).getAttribute("onmouseout").match(/^unhighlightPart.*/gi)){
        // this will be triggered if the part is not yet clicked
        removeEventListeners(partName, id);
    }else{
        // this will be triggered if the part is clicked already
        reAddEventListeners(part, id);
    }
}
function unhighlightPart(part, id){
    let specificQuad = document.getElementById(id);
    let partName = part.match(/^[a-zA-Z]+/g);

    let components = specificQuad.children;
    for(let component = 0; component < components.length; component++){
        let partTagsFormat = new RegExp(partName, 'g');
        if(components[component].id.match(partTagsFormat)){
            components[component].style.fillOpacity = "60%";
        }
    }

    let quadNumber = id.match(/[0-9]+/g);
    document.getElementById(partName+quadNumber).style.backgroundColor = "";
    document.getElementById(partName+quadNumber).style.border = "";

}
function pageLoad(){
    document.getElementById("plus").addEventListener("mousedown", showFantasyRealDecision);
    let svgCollection = document.getElementsByClassName("quadcopter");
    for(let svg=0; svg<svgCollection.length; svg++){
        let paths = svgCollection[svg].children;
        let id = svgCollection[svg].id;
        for(let path=0; path<paths.length; path++){
            paths[path].setAttribute("onmouseover", "highlightPart('"+paths[path].id+"', '"+id+"')");
            paths[path].setAttribute("onmouseout", "unhighlightPart('"+paths[path].id+"', '"+id+"')");
            paths[path].addEventListener("click", function(){clickOnPart(paths[path].id, id)});
        }
    }

    let componentCollection = document.getElementsByClassName("quadComponentText");
    for(let component=0; component<componentCollection.length; component++){
        let partText = componentCollection[component].id;
        let partName = partText.match(/^[a-zA-Z]+/g)+"-";
        if(partName!=="class-"){
            let id = "quadSVG"+partText.match(/[0-9]+/g);
            componentCollection[component].setAttribute("onmouseover", "highlightPart('"+partName+"', '"+id+"')");
            componentCollection[component].setAttribute("onmouseout", "unhighlightPart('"+partName+"', '"+id+"')");
            componentCollection[component].addEventListener("click", function(){clickOnPart(partName, id)});
        }
    }

    let panelCollection = document.getElementsByClassName("quadPanel");
    for(let panel=0; panel<panelCollection.length; panel++){
        if(panelCollection[panel].id!=="plus"){
            let id = panelCollection[panel].id.match(/[0-9]+/g);
            panelCollection[panel].addEventListener("mouseover", function(){displayArrow(id)});
            panelCollection[panel].addEventListener("mouseout", function(){hideArrow(id)});
        }
    }
}

function showFantasyRealDecision(){
    let addNewButton = document.getElementById("plus");
    addNewButton.removeEventListener("mousedown", showFantasyRealDecision);
    addNewButton.onclick = null;
    addNewButton.style.fontSize = "medium";
    addNewButton.style.textAlign = "right";
    addNewButton.innerHTML = "";
    let height = addNewButton.clientHeight;
    let width = addNewButton.clientWidth;

    let fantasyButton = document.createElement("div");
    fantasyButton.id = "fantasyButton";
    fantasyButton.style.display = "flex";
    fantasyButton.style.height = "100%";
    fantasyButton.style.width = "50%";

    let fantasyQuadTextForButton = document.createElement("div");
    fantasyQuadTextForButton.innerHTML = "Fantasy";
    fantasyQuadTextForButton.style.margin = "auto";
    fantasyButton.appendChild(fantasyQuadTextForButton);

    let realQuadButton = document.createElement("div");
    realQuadButton.id = "realQuadButton";
    realQuadButton.style.display = "flex";
    realQuadButton.style.height = "100%";
    realQuadButton.style.width = "50%";

    let realQuadTextForButton = document.createElement("div");
    realQuadTextForButton.innerHTML = "Real";
    realQuadTextForButton.style.margin = "auto";
    realQuadButton.appendChild(realQuadTextForButton);

    let divider = document.createElement("div");
    divider.style.display = "flex";
    divider.style.height = "100%";

    //event listeners
    realQuadButton.addEventListener("click", realQuad);
    fantasyButton.addEventListener("click", fantasyQuad);

    divider.appendChild(fantasyButton);
    divider.appendChild(realQuadButton);

    addNewButton.appendChild(divider);
    addNewButton.style.padding = "0";
    addNewButton.style.height = height+"px";
    addNewButton.style.width = width+"px";
}

function fantasyQuad(){
    showNewQuadForm("fantasy");
}

function realQuad(){
    showNewQuadForm("real");
}

function resetPlusBox(){
    let addNewButton = document.getElementById("plus");
    addNewButton.innerHTML = "+";
    addNewButton.removeAttribute("style");
    addNewButton.addEventListener("mousedown", showFantasyRealDecision);
}

function displayDetails(id){
    let quadPanel = document.getElementById("panel"+id);
    if(!quadPanel.className.match(/largePanel/gi)){
        let panelCollection = document.getElementsByClassName("quadPanel");
        for(let panel=0; panel<panelCollection.length; panel++){
            if(panelCollection[panel].id!=="plus"){
                let panelId = panelCollection[panel].id.match(/[0-9]+/g);
                if(id==panelId) enlargeArrow(panelCollection[panel]); // I WANT TYPE COERCION HERE!!
                else shrinkArrow(panelCollection[panel]);
            }
        }
    }
    else{
        shrinkArrow(quadPanel);
    }
}

function displayArrow(id){
    document.getElementById("expand_arrows"+id).style.opacity = "100%";
    document.getElementById("expand_arrows"+id).style.animationName = "arrowAppear";
    document.getElementById("expand_arrows"+id).style.animationDuration = "1s";
}
function hideArrow(id){
    document.getElementById("expand_arrows"+id).style.opacity = "0";
    document.getElementById("expand_arrows"+id).style.animationName = "";
    document.getElementById("expand_arrows"+id).style.animationDuration = "";
    document.getElementById("expand_arrows"+id).style.animationDirection = "";
}
function enlargeArrow(quadPanel){
    quadPanel.className += " largePanel";
    quadPanel.style.height = window.innerHeight + "px";
    quadPanel.scrollIntoView();
}
function shrinkArrow(quadPanel){
    quadPanel.className = "quadPanel "+quadPanel.className.match(/fantasy|real/gi);
    quadPanel.style.height = "300px";
}