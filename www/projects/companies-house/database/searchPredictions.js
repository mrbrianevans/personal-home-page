function predictCompanyNumber(){
    let typed = document.getElementById("companyNumberInput").value;
    if(typed.length===7){
        let request = new XMLHttpRequest();
        request.onreadystatechange = () => {
            if(request.readyState===4 && request.status===200){
                let predictions = JSON.parse(request.response);
                let datalist = document.getElementById("predictionsDatalist");
                datalist.innerHTML = "";
                for (let i = 0; i < predictions.length; i++) {
                    let newOption = document.createElement("option");
                    newOption.value = predictions[i].number;
                    newOption.innerText = predictions[i].name;
                    datalist.appendChild(newOption);
                }
            }
        };
        request.open("GET", "controller.php?action=predict&typed="+typed, true);
        request.send();
    }else if(typed.length===8){
        window.location.href = "?action=search&number="+typed;
    }
}