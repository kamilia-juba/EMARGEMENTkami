let title, errTitle;

function checkTitle(){
    let ok = true;
    errTitle.html("");
    if(!(/^.{3,255}$/).test(title.val())){
        errTitle.append("<p>Title must have at least 3 characters.</p>");
        ok = false;
    }
    changeTitleView();
    return ok;
}

async function checkTitleExists(){
    const data = await $.post("template/template_exists_service", {newTitle : title.val()},null, "json");
    //const data = await $.getJSON("template/template_exists_service/" + title.val());
    if(data){
        errTitle.html("<p>There's already an existing template with this title. Choose another title</p>");
    }else{
        errTitle.html("");
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
    $("input[type='number']").on("input", function(){
        var checkboxes = $("input[type='checkbox']").map(function(){
            return this.id;
        }).get();
    
        for(var i=0; i<checkboxes.length; ++i){
            var checkbox = $("#" + checkboxes[i]);
            var weight = $("#" + checkboxes[i] + "_weight");
            if(weight.val() <= "0"){
                checkbox.prop("checked", false);
            }else{
                checkbox.prop("checked", true);
            }
        }
    })
}

$(function(){
    title = $("#title");
    errTitle = $("#errTitle");

    title.bind("input", checkTitle);
    title.bind("blur", checkTitleExists);

    checkWeight();

    $("input:text:first").focus();
});