/**
 * Function to add amenity on setup page
 * @param amenity : amenity to add ( String )
 */
function addAmenity(amenity){
    var checked="";

    $("#amenity-box").append(
        "<div class='amenity'>"+
        "<input type='checkbox'  id='"+amenity+"' name='amenities[]' value='"+amenity+"' "+checked+">"+
        "<label for='"+amenity+"' class='phase-text'>"+amenity+"</label><br>"+
        "</div>"
    );



    if (lyfly.isAmenityChecked(amenity)){
        $("input[id='"+amenity+"']").prop("checked",true)
    }

}

/**
 * Function to add house rule on setup page using given data
 * @param houserule the rule name to add
 * @param newCount count of this new houserule
 */
function  addHouseRules(houserule,newCount) {
    $("#hrules-box").append("<div class=\"rowflex\">\n" +
        "                        <p class=\"phase-text\">"+houserule+"</p>\n" +
        "                        <input type=\"button\" class=\"rules-btns rb"+newCount+"\" rep='yes' value=\"&#10003;\" style=\"margin-left:auto;margin-right:1vw;\">\n" +
        "                        <input type=\"button\" class=\"rules-btns rb"+newCount+"\" rep='no' value=\"X\">\n" +
        "                        <input type=\"hidden\" class=\"rules-yes\"  value=\""+houserule+":Yes\">\n" +
        "                        <input type=\"hidden\" class=\"rules-no\"  value=\""+houserule+":Noo\">\n" +
        "                    </div>");

    var status;

    if ((status=lyfly.getHouseRuleStatus(houserule))!=null){
        if (status){
            onHouseRuleClick($(".rb"+newCount+"[rep='yes']"))
        }else{
            onHouseRuleClick($(".rb"+newCount+"[rep='no']"))

        }
    }
}

function onHouseRuleClick(elem){
    let value=elem.val()
    if(elem.hasClass("activeBtn")){
        elem.removeClass("activeBtn");
        if(value=="X"){
            elem.siblings(".rules-no").removeAttr("name");
        }else{
            elem.siblings(".rules-yes").removeAttr("name");
        }
    }else{
        var sClass=elem.attr("class").split(' ')[1];
        $("."+sClass).removeClass("activeBtn");
        elem.addClass("activeBtn");
        if(value=="X"){
            elem.siblings(".rules-no").attr("name","houseRules[]");
            elem.siblings(".rules-yes").removeAttr("name");

        }else{
            elem.siblings(".rules-yes").attr("name","houseRules[]");
            elem.siblings(".rules-no").removeAttr("name");

        }
    }
}


$(document).ready(function(){

    //get data from current opened prop obtained from php
    let cprop=php_vars.cprop
    let arrayInputs=["amenities","houseRules"]

    //autofill the data from opened prop , if it exists
    if (cprop.length!=0){
        cprop=cprop[0];
        for (let key in cprop){
            if (cprop.hasOwnProperty(key) && cprop[key]!=null){

                if (arrayInputs.includes(key)){





                    let arrayInp=cprop[key].split(",")

                    if (key=="amenities") {
                        lyfly.addAmenities(arrayInp)
                        lyfly.addCheckedAmenities(arrayInp)

                    }

                    if(key=="houseRules")
                        lyfly.addHouseRules(arrayInp)
                }

                if (cprop[key] !== null)
                    $("[name='"+key+"']").val(cprop[key]);
            }
        }
    }


    //add preexisting amenities on lyfly module
    var  amList=lyfly.getAmenities();

    for (let amenity of amList){
        addAmenity(amenity);
    }

    var  houseRulesList=lyfly.getHouseRules()
    var index=0
    for (let rules of houseRulesList){
        addHouseRules(rules,index)
        index++
    }
    // Add Amenity button event
    $("#add-amenity").on("click",function () {
        var amenity=$("#prop-add-amenity").val();
        addAmenity(amenity);
        $("#prop-add-amenity").val("");

    });

    //        House rules button event
    $("#hrules-box").on("click",".rules-btns",function(){
        let elem=$(this)
        onHouseRuleClick(elem)

    });
});