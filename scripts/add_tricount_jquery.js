
let title ;
let errorTitle;
let description;
let errorDescription;

function checkTitle(){
    let verification= true;
    errorTitle.html("");
    if(title.val().length === 0){
        errorTitle.append("<p>Title cannot be empty.</p>");
        verification=false;
    }
    else {
        if(title.val().length<3 || title.val().length>16){
            errorTitle.append("<p>Title length must be between 3 and 16.</p>");
            verification=false;
        }

    }
  
    console.log(title);
    return verification; 
  
}

function checkDescription(){
    let verification= true;
    errorDescription.html("");
    
    if(description.val().length>0){
        if(description.val().length<3 || description.val().length>16){
            errorDescription.append("<p>Description length must be between 3 and 16.</p>");
            verification=false;
        }

    }
    return verification ;
}

function checkAll(){
    let verification = checkTitle();
    verification = checkDescription() && verification; 
    return verification;
}

$(function(){
    title = $("#title");
    errorTitle = $("#errorTitle");
    description = $("#description");
    errorDescription = $("#errorDescription");

    title.bind("input", checkTitle);
    description.bind("input", checkDescription);

    $("input:text:first").focus();
}

);

