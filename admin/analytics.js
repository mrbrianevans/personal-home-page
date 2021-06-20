function pagesbyip(){
    var ip = document.getElementById('pagesbyipdropdown').value;
    let xhttps = new XMLHttpRequest();
    xhttps.onreadystatechange = function(){
        if(this.readyState===4){
            document.getElementById("specificiphistory").innerHTML = this.responseText;
        }
    };
    xhttps.open("GET", "controller.php?ip="+ip, true);
    xhttps.send();
}
function pagesbyusername(){
    var username = document.getElementById('pagesbyusernamedropdown').value;
    let xhttps = new XMLHttpRequest();
    xhttps.onreadystatechange = function(){
        if(this.readyState===4){
            document.getElementById("usernamespecifichistory").innerHTML = this.responseText;
        }
    };
    xhttps.open("GET", "controller.php?username="+username, true);
    xhttps.send();
}

function analyticschosen() {
    var analytics = document.getElementById("analyticsoptions").options;
    for (let i = 0; i < analytics.length; i++) {
        document.getElementById(analytics[i].value).hidden = !analytics[i].selected;
    }
}

