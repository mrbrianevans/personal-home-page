function search(term){
    let apiKey = "bq9ZoOJjz0UguRPmbKr3qnoyEAh-9mpEDi3tmm8S";
    let url = "https://api.companieshouse.gov.uk/search/companies?q=";
    let query = term;
    let apiCall = new XMLHttpRequest();
    apiCall.onreadystatechange = () => {
        if(apiCall.readyState===4&&apiCall.status===200){
            // alert(apiCall.response);
        }
    };

    apiCall.open("GET", url+query, true);
    apiCall.setRequestHeader("Authorization", "Basic "+btoa(apiKey+":"));

    apiCall.send();
}