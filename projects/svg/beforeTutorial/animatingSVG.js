function changeColor(objectID, color){
    let drawing = document.getElementById(objectID);
    drawing.style.fill = color;
}

function move(objectID, pixelsDown, pixelsRight){
    let drawing = document.getElementById(objectID);
    let cy = Number(drawing.getAttribute("cy")) + pixelsDown;
    let cx = Number(drawing.getAttribute("cx")) + pixelsRight;
    // alert("Setting cx to "+cx + " and cy to "+cy);
    drawing.setAttribute("cy", cy);
    drawing.setAttribute("cx", cx);
}