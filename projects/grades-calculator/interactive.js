function cancelNewCourse(courseId) {
    deleteCourse(courseId);
    document.getElementById("newCourseForm").innerHTML = "";
    let showFormButton = document.getElementById("newCourseOpenFormButton");
    showFormButton.innerText = "New course";
    showFormButton.onclick = newCourse;
}
function deleteCourse(courseId){
    let courseDeleteRequest = new XMLHttpRequest();
    let url = "interface.php?delete=true&courseId="+courseId;
    courseDeleteRequest.open("GET", url, true);
    courseDeleteRequest.send();
}
function removeCourse(courseId){
    deleteCourse(courseId);
    let courseBox = document.getElementById("courseBox"+courseId);
    courseBox.parentNode.removeChild(courseBox);
}
function pressEnterMessage(){
    let pressEnterMessage = document.getElementById("pressEnterMessage");
    if(document.getElementById("newCourseName").value.length > 1)
        pressEnterMessage.innerText = "(press enter to submit)";
    else
        pressEnterMessage.innerText = "";
}
function deleteEnterMessage(){
    let pressEnterMessage = document.getElementById("pressEnterMessage");
    pressEnterMessage.parentNode.removeChild(pressEnterMessage);
}
function newCourse(){
    // TODO: Add a finish course button to save and refresh
    let showFormButton = document.getElementById("newCourseOpenFormButton");
    showFormButton.innerText = "Cancel";
    showFormButton.onclick = cancelNewCourse;

    let formBox = document.getElementById("newCourseForm");

    let courseTypeBox = document.createElement("input");
    courseTypeBox.autocomplete = "off";
    courseTypeBox.placeholder = "University, GCSEs, A levels";
    courseTypeBox.id = "newCourseType";

    let courseTypeBoxLabel = document.createElement("label");
    courseTypeBoxLabel.innerText = "Course Type: ";
    courseTypeBoxLabel.appendChild(courseTypeBox);
    formBox.appendChild(courseTypeBoxLabel);
    courseTypeBox.focus();

    let courseNameBox = document.createElement("input");
    courseNameBox.autocomplete = "off";
    courseNameBox.placeholder = "Computer Science Degree";
    courseNameBox.id = "newCourseName";
    courseNameBox.addEventListener("change", insertNewCourse);
    courseNameBox.addEventListener("keypress", pressEnterMessage);

    let courseNameBoxLabel = document.createElement("label");
    courseNameBoxLabel.innerText = "Course Name: ";
    courseNameBoxLabel.appendChild(courseNameBox);
    formBox.appendChild(courseNameBoxLabel);

    let enterMessage = document.createElement("span");
    enterMessage.id = "pressEnterMessage";
    formBox.appendChild(enterMessage);
}

function insertNewCourse(){
    deleteEnterMessage();
    let courseName = document.getElementById("newCourseName").value;
    let courseType = document.getElementById("newCourseType").value;
    let courseCreationRequest = new XMLHttpRequest();
    courseCreationRequest.onreadystatechange = () => {
        if(courseCreationRequest.readyState===4 && courseCreationRequest.status===200){
            let cancelFormButton = document.getElementById("newCourseOpenFormButton");
            cancelFormButton.innerText = "Delete course";
            cancelFormButton.onclick = () => {cancelNewCourse(courseCreationRequest.response)};

            let formBox = document.getElementById("newCourseForm");
            formBox.innerHTML = "";
            let newCourseTitle = document.createElement("h2");
            newCourseTitle.innerText = courseName+" "+courseType;
            // newCourseTitle.id = "newCourseTitle"+courseCreationRequest.response;
            formBox.appendChild(newCourseTitle);

            let subjectsBox = document.createElement("div");
            subjectsBox.id = "subjectsContainer";
            formBox.appendChild(subjectsBox);

            let addSubjectButton = document.createElement("button");
            addSubjectButton.innerText = "Add subject";
            addSubjectButton.id = "addSubjectButton";
            addSubjectButton.onclick = () => addSubject(courseCreationRequest.response);
            formBox.appendChild(addSubjectButton);
            addSubjectButton.focus();
        }else if(courseCreationRequest.readyState===4 && courseCreationRequest.status!==200){
            alert(courseCreationRequest.response+courseCreationRequest.statusText);
            alert("Fatal error occurred. Please contact Brian");
        }
    };
    let url = "interface.php?courseType="+courseType+"&courseName="+courseName+"&username="+username;
    courseCreationRequest.open("GET", url, true);
    courseCreationRequest.send();

}

function addSubject(courseId){
    document.getElementById("addSubjectButton").disabled = true;
    let newSubjectFormBox = document.createElement("div");
    newSubjectFormBox.id = "newSubjectFormBox";
    let newSubjectNameInput = document.createElement("input");
    newSubjectNameInput.autocomplete = "off";
    newSubjectNameInput.id = "newSubjectNameInput";
    newSubjectNameInput.placeholder = "Calculus";
    newSubjectNameInput.addEventListener("change", ()=>{addNewSubject(courseId)});

    let newSubjectNameLabel = document.createElement("label");
    newSubjectNameLabel.for = "newSubjectNameInput";
    newSubjectNameLabel.innerText = "Subject/module name: ";

    newSubjectFormBox.appendChild(newSubjectNameLabel);
    newSubjectFormBox.appendChild(newSubjectNameInput);

    document.getElementById("addSubjectButton").insertAdjacentElement("beforebegin", newSubjectFormBox);
    newSubjectNameInput.focus();
}

function addNewSubject(courseId){
    let subjectName = document.getElementById("newSubjectNameInput").value;
    document.getElementById("newSubjectNameInput").value = "";
    let newSubjectRequest = new XMLHttpRequest();
    newSubjectRequest.onreadystatechange = () => {
        if(newSubjectRequest.readyState===4&&newSubjectRequest.status===200){
            insertSubject(newSubjectRequest.response, subjectName);
        }
    };
    let url = "interface.php?subjectName="+subjectName+"&courseId="+courseId;
    newSubjectRequest.open("GET", url, true);
    newSubjectRequest.send();
}

function insertSubject(subjectId, subjectName){
    document.getElementById("addSubjectButton").disabled = false;
    document.getElementById("newCourseForm").removeChild(document.getElementById("newSubjectFormBox"));
    let subjectsBox = document.getElementById("subjectsContainer");
    let newSubjectBox = document.createElement("div");
    newSubjectBox.id = "subject"+subjectId;
    newSubjectBox.className = "subjectBox";

    let newSubjectSmallHead = document.createElement("p");
    newSubjectSmallHead.className = "smallhead";
    newSubjectSmallHead.innerHTML = subjectName+" (<span id='average"+subjectId+"'></span>%)";
    let deleteButton = document.createElement("button");
    let deleteTooltip = document.createElement("span");
    deleteTooltip.className = "deleteTooltip";
    deleteTooltip.innerText = "Delete subject";
    deleteButton.addEventListener("click", ()=>{deleteSubject(subjectId)}) ;
    deleteButton.className = "deleteButton";
    let deleteIconRequest = new XMLHttpRequest();
    deleteIconRequest.onreadystatechange = () => {
        if(deleteIconRequest.readyState===4&&deleteIconRequest.status===200){
            deleteButton.innerHTML = deleteIconRequest.response;
            deleteButton.appendChild(deleteTooltip);
            newSubjectSmallHead.appendChild(deleteButton);
        }
    };
    deleteIconRequest.open("GET", "interface.php?icon=delete", true);
    deleteIconRequest.send();
    newSubjectBox.appendChild(newSubjectSmallHead);

    let newAssessmentForm = document.createElement("div");
    newAssessmentForm.id = "newAssessmentForm"+subjectId;

    let newAssessmentName = document.createElement("input");
    newAssessmentName.autocomplete = "off";
    newAssessmentName.type = "text";
    newAssessmentName.placeholder = "Exam";
    newAssessmentName.id = "newAssessmentName";
    newAssessmentName.addEventListener("change", ()=>{displayMaxMarkBox(subjectId)});

    let newAssessmentNameLabel = document.createElement("label");
    newAssessmentNameLabel.for = "newAssessmentName";
    newAssessmentNameLabel.innerText = "Assessment Name: ";
    newAssessmentForm.appendChild(newAssessmentNameLabel);
    newAssessmentForm.appendChild(newAssessmentName);
    newSubjectBox.appendChild(newAssessmentForm);
    subjectsBox.appendChild(newSubjectBox);
    newAssessmentName.focus();
}
function deleteSubject(subjectId){
    let deleteRequest = new XMLHttpRequest();
    deleteRequest.onreadystatechange = () => {
        if(deleteRequest.readyState===4&&deleteRequest.status===200){
            let subjectNode = document.getElementById("subject"+subjectId);
            subjectNode.parentNode.removeChild(subjectNode);
        }
    };
    deleteRequest.open("GET", "interface.php?subjectId="+subjectId+"&delete=true");
    deleteRequest.send();
}
function displayMaxMarkBox(subjectId){
    let newAssessmentName = document.getElementById("newAssessmentName");
    newAssessmentName.removeEventListener("change", ()=>{displayMaxMarkBox(subjectId)});
    let subjectBox = document.getElementById("newAssessmentForm"+subjectId);
    let newAssessmentMaxMarkBox = document.createElement("input");
    newAssessmentMaxMarkBox.autocomplete = "off";
    newAssessmentMaxMarkBox.type = "text";
    newAssessmentMaxMarkBox.placeholder = "50";
    newAssessmentMaxMarkBox.id = "newAssessmentMaxMarkBox";
    newAssessmentMaxMarkBox.addEventListener("change", ()=>{displaySubjectContributionBox(subjectId)});

    let newAssessmentMaxMarkLabel = document.createElement("label");
    newAssessmentMaxMarkLabel.for = "newAssessmentMaxMarkBox";
    newAssessmentMaxMarkLabel.innerText = "Max mark: ";

    subjectBox.appendChild(newAssessmentMaxMarkLabel);
    subjectBox.appendChild(newAssessmentMaxMarkBox);
    newAssessmentMaxMarkBox.focus();

}
function displaySubjectContributionBox(subjectId){
    let newAssessmentMaxMarkBox = document.getElementById("newAssessmentMaxMarkBox");
    newAssessmentMaxMarkBox.removeEventListener("change", ()=>{displaySubjectContributionBox(subjectId)});
    let subjectBox = document.getElementById("newAssessmentForm"+subjectId);
    let newAssessmentContributionBox = document.createElement("input");
    newAssessmentContributionBox.type = "text";
    newAssessmentContributionBox.autocomplete = "off";
    newAssessmentContributionBox.placeholder = "40%";
    newAssessmentContributionBox.id = "newAssessmentContributionBox";
    newAssessmentContributionBox.addEventListener("change", ()=>{sendNewAssessmentRequest(subjectId)});

    let newAssessmentContributionLabel = document.createElement("label");
    newAssessmentContributionLabel.for = "newAssessmentContributionBox";
    newAssessmentContributionLabel.innerText = "Contribution to subject: ";

    subjectBox.appendChild(newAssessmentContributionLabel);
    subjectBox.appendChild(newAssessmentContributionBox);
    newAssessmentContributionBox.focus();
}
function sendNewAssessmentRequest(subjectId){
    let newAssessmentNameBox = document.getElementById("newAssessmentName");
    let name = newAssessmentNameBox.value;
    newAssessmentNameBox.value = "";
    let newAssessmentMaxMarkBox = document.getElementById("newAssessmentMaxMarkBox");
    let maxMark = newAssessmentMaxMarkBox.value.match(/[0-9]+/g);
    newAssessmentMaxMarkBox.value = "";
    let newAssessmentContributionBox = document.getElementById("newAssessmentContributionBox");
    let subjContribution = newAssessmentContributionBox.value.match(/[0-9]+/g);
    newAssessmentContributionBox.value = "";
    let url = "interface.php?subjectId="+subjectId+"&assessmentName="+name+"&maxMark="+maxMark+"&subjectContribution="+subjContribution;
    let assessmentRequest = new XMLHttpRequest();
    assessmentRequest.onreadystatechange = () => {
      if(assessmentRequest.readyState===4 && assessmentRequest.status===200){
          drawAssessmentBox(name, assessmentRequest.response, maxMark, subjContribution, subjectId)
      }
    };
    assessmentRequest.open("GET", url, true);
    assessmentRequest.send();


}

function drawAssessmentBox(assessmentName, assessmentId, maxMark, subjectContribution, subjectId){

    let newAssessmentButton = document.createElement("button");
    newAssessmentButton.id = "newAssessmentButton"+subjectId;
    newAssessmentButton.innerText = "Add assessment";
    newAssessmentButton.addEventListener("click", ()=>{drawNewAssessmentForm(subjectId)});

    let assessmentBox = document.createElement("div");
    assessmentBox.class = "assessmentBox";
    assessmentBox.id = "assessment"+assessmentId;
    document.getElementById("subject"+subjectId).appendChild(assessmentBox);
    assessmentBox.innerHTML = assessmentName
        + " -> <input id='mark"+assessmentId+"'> / "
        + maxMark + " makes up " + subjectContribution + "%";
    let deleteButton = document.createElement("button");
    deleteButton.addEventListener("click", ()=>{deleteAssessment(assessmentId)}) ;
    deleteButton.innerHTML = "Delete";
    deleteButton.className = "deleteButton";
    let deleteTooltip = document.createElement("span");
    deleteTooltip.className = "deleteTooltip";
    deleteTooltip.innerText = "Delete assessment";
    let deleteIconRequest = new XMLHttpRequest();
    deleteIconRequest.onreadystatechange = () => {
        if(deleteIconRequest.readyState===4&&deleteIconRequest.status===200){
            deleteButton.innerHTML = deleteIconRequest.response;
            deleteButton.appendChild(deleteTooltip);
            assessmentBox.appendChild(deleteButton);
        }
    };
    deleteIconRequest.open("GET", "interface.php?icon=delete", true);
    deleteIconRequest.send();
    let markBox = document.getElementById("mark"+assessmentId);
    markBox.placeholder = Math.round(0.7*maxMark);
    markBox.className = "markEntry";
    markBox.addEventListener("change", ()=>{changeMark(assessmentId)});
    markBox.focus();
    let newAssessmentForm =  document.getElementById("newAssessmentForm"+subjectId);
    newAssessmentForm.parentNode.appendChild(newAssessmentButton);
    newAssessmentForm.parentNode.removeChild(newAssessmentForm);
}
function deleteAssessment(assessmentId){
    let deleteRequest = new XMLHttpRequest();
    deleteRequest.onreadystatechange = () => {
        if(deleteRequest.readyState===4&&deleteRequest.status===200){
            let assessmentNode = document.getElementById("assessment"+assessmentId);
            assessmentNode.parentNode.removeChild(assessmentNode);
        }
    };
    deleteRequest.open("GET", "interface.php?assessmentId="+assessmentId+"&delete=true");
    deleteRequest.send();
}
function drawNewAssessmentForm(subjectId){
    let subjectBox = document.getElementById("subject"+subjectId);
    subjectBox.removeChild(document.getElementById("newAssessmentButton"+subjectId));
    let newAssessmentForm = document.createElement("div");
    newAssessmentForm.id = "newAssessmentForm"+subjectId;

    let newAssessmentName = document.createElement("input");
    newAssessmentName.autocomplete = "off";
    newAssessmentName.type = "text";
    newAssessmentName.placeholder = "Exam";
    newAssessmentName.id = "newAssessmentName";
    newAssessmentName.addEventListener("change", ()=>{displayMaxMarkBox(subjectId)});

    let newAssessmentNameLabel = document.createElement("label");
    newAssessmentNameLabel.for = "newAssessmentName";
    newAssessmentNameLabel.innerText = "Assessment Name: ";
    newAssessmentForm.appendChild(newAssessmentNameLabel);
    newAssessmentForm.appendChild(newAssessmentName);
    subjectBox.appendChild(newAssessmentForm);
    newAssessmentName.focus();
}

function changeMark(assessmentId){
    let markBox = document.getElementById("mark"+assessmentId);
    let newMark = markBox.value;

    let changeMarkRequest = new XMLHttpRequest();
    let url = "interface.php?assessmentId="+assessmentId+"&mark="+newMark;
    changeMarkRequest.onreadystatechange = () => {
        if(changeMarkRequest.readyState===4&&changeMarkRequest.status===200){
            let subjectId = document.getElementById("assessment"+assessmentId).parentElement.id.match(/[0-9]+/);
            document.getElementById("average"+subjectId).innerText = changeMarkRequest.response;
            markBox.style.border = "none";
            markBox.style.color = "black";
            markBox.style.margin = "0";
            markBox.style.padding = "0";
            markBox.blur();
        }

    };

    changeMarkRequest.open("GET", url, true);
    changeMarkRequest.send();
}