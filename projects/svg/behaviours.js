function changeColor(id, color){
    let svg = document.getElementById(id);
    svg.style.fill = color;
}

function moveCircle(id, pixelsDown, pixelsRight){
    let svg = document.getElementById(id);

    let cx = Number(svg.getAttribute("cx")) + pixelsRight;
    svg.setAttribute("cx", cx);

    let cy = Number(svg.getAttribute("cy")) + pixelsDown;
    svg.setAttribute("cy", cy);
}

function animateMoveCircle(id, pixelsDown, pixelsRight, microseconds){
    let eachPixelTime = microseconds/(pixelsRight*pixelsDown);
    let numberOfSteps = pixelsDown*pixelsRight;
    let horizontalSteps = pixelsRight/numberOfSteps;
    let verticalSteps = pixelsDown/numberOfSteps;
    let repeat = setInterval(moveCircle, eachPixelTime, [id, verticalSteps, horizontalSteps]);
    if(document.getElementById(id).getAttribute("cx"));
}