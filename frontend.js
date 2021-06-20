function changeColor(ele,color){
	var elem = document.getElementById(ele);
	elem.style.borderBottomColor = color;
	elem.style.color = color;

}
function getVal(){
	return document.getElementById("hac").value;
}
function setBox(box, tex){
	if(document.getElementById(box).value === document.getElementById(box).defaultValue){
	document.getElementById(box).value = tex;
	}
}
function clearBox(box){
	if(document.getElementById(box).value === document.getElementById(box).defaultValue){
		document.getElementById(box).value = "";
	}
}
function setToDefaultIfEmpty(box){
	if(document.getElementById(box).value == ""){
		document.getElementById(box).value = document.getElementById(box).defaultValue;
	}
	
}
function invertColor(ele){
	var elem = document.getElementById(ele);
	var originalColor = elem.style.color;
	elem.style.color = elem.style.backgroundColor;
	elem.style.backgroundColor = originalColor;
}

function like(post){
	//document.getElementById("likebut" + post).getElementById("uparrow").style.fill = 'blue';
	let xhttps = new XMLHttpRequest(); //change this to asynchronous
	xhttps.onreadystatechange = function(){
		if(this.readyState===4){
			document.getElementById("like"+post).innerHTML = String(Number(document.getElementById("like"+post).innerHTML)+1);
		}
	};
	xhttps.open("GET", "/socialmedia/controller.php?like="+post, true);
	xhttps.send();
}
function logout(){
	let xhttps = new XMLHttpRequest();
	xhttps.onreadystatechange = function(){
		if(this.readyState===4 && this.status===200){
			window.alert("Successfully logged out: Code: "+this.status);
			window.location.replace("https://brianevans.tech/");
		}
	};
	xhttps.open("GET", "https://brianevans.tech/logout.php?logout=true", true);
	xhttps.send();
}

function postReply(post){
	let author = document.getElementById("author" + post).value;
	let content = document.getElementById("content" + post).value;
	let xhttps = new XMLHttpRequest();
	xhttps.onreadystatechange = function(){
		if(this.readyState===4){
			document.getElementById("content"+post).value = document.getElementById("content"+post).defaultValue;
			document.getElementById("replyboxof"+post).insertAdjacentHTML("beforeend", replyString);
		}
	};
	xhttps.open("POST", "/socialmedia/controller.php", true);
	xhttps.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhttps.send("reply="+post+"&author="+author+"&content="+content);

	var replyString = "<div class='reply'>" +
		"Reply from:<strong> "+author+"</strong>. Posted Today\n<br>" +
		content + "</div>";
}


