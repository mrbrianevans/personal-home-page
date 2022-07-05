function sendFeedback(){
    const feedback = document.getElementById("feedbackTextArea").value;

    let feedbackRequest = new XMLHttpRequest();
    feedbackRequest.onreadystatechange = () => {
        if(feedbackRequest.readyState===4){
            switch (feedbackRequest.status) {
                case 200:
                    console.log(feedbackRequest.response);
                    alert("Feedback sent");
                    break;
                default:
                    alert("Failed to send feedback");
                    break;
            }
        }
    };

    feedbackRequest.open("POST", "/projects/cbt/app/feedbackHandler.php", true);
    feedbackRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    feedbackRequest.send("feedback="+feedback);
}

function countDownload(data) {
    let countDownloadRequest = new XMLHttpRequest();

    countDownloadRequest.onreadystatechange = () => {
        if(countDownloadRequest.readyState===4){
            switch (countDownloadRequest.status) {
                case 200:
                    console.log(countDownloadRequest.response);
                    break;
                default:
                    console.log("Failed to download");
                    break;
            }
        }
    };

    countDownloadRequest.open("POST", "/projects/cbt/counter.php", true);
    countDownloadRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    countDownloadRequest.send("download="+data);
}