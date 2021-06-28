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