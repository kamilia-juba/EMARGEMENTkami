let title, errTitle, errWeights;

function checkTitle(){
    let ok = true;
    errTitle.html("");

    if(title.val().trim().length === 0){
        errTitle.append("<p>Title cannot be empty.</p>");
        ok = false;
    }else{
        if(!(/^.{3,255}$/).test(title.val())){
            errTitle.append("<p>Title must have at least 3 characters.</p>");
            ok = false;
        }
    }
    changeTitleView();
    return ok;
}

async function checkTitleExists(){
    const data = await $.post("template/template_exists_service", {newTitle : title.val()},null, "json");
    //const data = await $.getJSON("template/template_exists_service/" + title.val());
    if(data){
        errTitle.html("<p>There's already an existing template with this title. Choose another title</p>");
    }
    changeTitleView();
}

function changeTitleView(){
    if(errTitle.text() == ""){
        $("#okTitle").html("Looks good");
        $("#title").attr("class","form-control mb-2 is-valid");
    }else{
        $("#okTitle").html("");
        $("#title").attr("class", "form-control mb-2 is-invalid");
    }
}

function checkWeight(){
    let ok = true;
    $("input[type='number']").on("input", function(){
        var checkboxes = $("input[type='checkbox']").map(function(){
            return this.id;
        }).get();
        errWeights.html("");
        okWeights.html("Looks good");
        for(var i=0; i<checkboxes.length; ++i){
            var checkbox = $("#" + checkboxes[i]);
            var weight = $("#" + checkboxes[i] + "_weight");
            if(weight.val() <= "0"){
                checkbox.prop("checked", false);
            }else{
                checkbox.prop("checked", true);
            }
            if(weight.val() === ""){
                errWeights.html("<p>Weights cannot be empty</p>");
                ok = false;
                okWeights.html("");
            }
        }
    })
    return ok;
}



function checkAll(){
    let ok = checkTitle();
    ok = checkWeight() && ok;
    return ok;
}

$(function(){
    title = $("#title");
    errTitle = $("#errTitle");
    errWeights = $("#errWeights");
    okWeights = $("#okWeights");

    title.bind("input", checkTitle);
    title.bind("input", checkTitleExists);

    checkWeight();

    $("input:text:first").focus();
});