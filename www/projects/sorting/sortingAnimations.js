

function draw(canvas) {
    let sortCanvas = document.getElementById(canvas);
    let context = sortCanvas.getContext("2d");
    context.clearRect(0, 0, sortCanvas.clientWidth, sortCanvas.clientHeight);
    drawBars(context, 20, 40, 45);
}

function drawBars(context, qty=10, width=20, padding=25, fillColor="#2F302F", maxHeight=200, font="11px Monospace"){
    context.fillStyle = fillColor;
    context.font = font;
    context.textAlign = "center";
    for (let i = 1; i <= qty; i++) {
        let height = Math.round(Math.random()*qty)/qty*maxHeight;
        context.fillRect(padding*i, maxHeight-height+padding, width, height);
        context.fillText((height/maxHeight*qty).toString(), padding*i+(width/2), maxHeight-height+padding-3);
    }
}