function showContactForm(){
    document.getElementById("addContactForm").hidden = false;
}

function addContact() {
    sessionStorage.user = document.getElementById("usernametop").innerText;
    var contactName = document.getElementById("newcontactname").value;
    var user = sessionStorage.user;
    xhttps = new XMLHttpRequest();
    xhttps.onreadystatechange = function(){
        if(this.readyState===4 && this.status===200){
            window.alert(this.response);
        }
    };
    xhttps.open("GET", "/messaging/request_handler.php?new_contact="+contactName
    +"&user="+user);
    xhttps.send();
    document.getElementById("newcontactname").value = document.getElementById("newcontactname").defaultValue;
}

function hideContactForm() {
    var contactNameBox = document.getElementById("newcontactname");
    if(contactNameBox.value === contactNameBox.defaultValue){
        document.getElementById("addContactForm").hidden = true;
    }
}