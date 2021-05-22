$(document).ready(function() {

    //Search Input box Script
    function listProperties(location,searchKey=""){
        //replace one or more whitespace characters with dash
        searchKey=searchKey.replace(/\s+/g , "-" );
        if (searchKey!="")
            searchKey="/"+searchKey;

        location=location.toLowerCase().ucfirst();
        window.location.href = "/prop/l/"+location+searchKey;

    }

    function checkSearchInputs(location,searchKey){
        if(location==""&& searchKey==""){

        }else if (searchKey==""&&location!==""){
            listProperties(location);

        }else if (searchKey!="" && location ==""){

        }else{
            listProperties(location,searchKey);
        }
    }
    $(".search-go-btn").on("click",function () {
        let location=$(this).siblings(".locationInput").val();
        let searchKey=$(this).siblings(".searchInput").val();
        checkSearchInputs(location,searchKey);
    });
    $(".searchInput").keyup(function (e) {
        if(e.keyCode==13) {
            let searchKey=$(this).val();
            let location=$(this).siblings(".locationInput").val();
            checkSearchInputs(location,searchKey);
        }
    });
    $(".locationInput").keyup(function (e) {
        if(e.keyCode==13) {
            let location=$(this).val();
            let searchKey=$(this).siblings(".searchInput").val();
            checkSearchInputs(location,searchKey);
        }
    });









    $(".add-to-wishlist-btn").on("click", function (e) {
        if (!login_params.signed) {
            openLogin();
        } else {
            var prop_id = $(this).attr("for");
            var wishlisted=$(this).attr("wishlist");
            if(wishlisted=="true"){
                $.post("/prop/remove_from_wishlist", {"prop_id": prop_id}, (data) =>{
                    $(this).attr("wishlist","false");
                });
            }
            else {
                $.post("/prop/add_to_wishlist", {"prop_id": prop_id},(data)=> {
                    $(this).attr("wishlist","true");
                });
            }
        }
        e.stopPropagation();

    });

     // Review box script
    var stars=    parseFloat($(".front-stars").attr("stars"));
    var total=    parseFloat($(".front-stars").attr("total"));
    var percentage=stars/total *100;
    console.log(percentage);
    $(".front-stars").css("width",percentage+"%");







});


/**
 * Remove specified characters from the start of the string
 * @param charlist
 * @return {string}
 */
String.prototype.trimLeft = function(charlist) {
    if (charlist === undefined)
        charlist = "\s";

    return this.replace(new RegExp("^[" + charlist + "]+"), "");
};

/**
 * Remove specified characters from the end of the string
 * @param charlist
 * @return {string}
 */
String.prototype.trimRight = function(charlist) {
    if (charlist === undefined)
        charlist = "\s";

    return this.replace(new RegExp("[" + charlist + "]+$"), "");
};

/**
 * Remove specified characters from the both the start and the end  of the string
 * @param charlist
 * @return {string}
 */
String.prototype.trim = function(charlist) {
    return this.trimLeft(charlist).trimRight(charlist);
};

String.prototype.ucfirst = function() {
    return this.charAt(0).toUpperCase() + this.slice(1);
}


//helper module for the whole app , contains various functions
var lyfly=(function(){
   var publicFuncs={};

    var  amenities=["Wifi","Laundry","Air Conditioner","TV","Refrigerator","Breakfast","Lunch","Dinner"];
    var  houseRules=["Smoking Allowed","Parties Allowed",""];

    var checkedAmenity={};
    publicFuncs.getAmenities=function () {
        return amenities
    }

    publicFuncs.addAmenities=function(data){
        amenities=amenities.concat(data);
    }

    publicFuncs.addHouseRules(){

    }

    publicFuncs.addCheckedAmenities=function (data){
        for(let amenity of data){
            console.log("ament"+amenity)
            checkedAmenity[amenity]=true
        }
        console.log(checkedAmenity)
    }
    publicFuncs.isAmenityChecked=function(amenity){

        return checkedAmenity.hasOwnProperty(amenity);
    }
   publicFuncs.checkLoggedIN = function(){
        if (!login_params.signed) {
            openLogin();
            return false;
        }else{
            return true;
        }
    }

    publicFuncs.setWishlist=function(e,btn){

        if (publicFuncs.checkLoggedIN()) {
            var prop_id = btn.attr("for");
            var wishlisted=btn.attr("wishlist");
            if(wishlisted=="true"){
                $.post("/prop/remove_from_wishlist", {"prop_id": prop_id}, (data) =>{
                    btn.attr("wishlist","false");
                });
            }
            else {
                $.post("/prop/add_to_wishlist", {"prop_id": prop_id},(data)=> {
                    btn.attr("wishlist","true");
                });
            }
        }
        e.stopPropagation();

    }

    publicFuncs.loadCities=function (element_selector,cities_json="/util/cities/indian_cities.json") {
        $(element_selector).autocomplete({
            source:function (request,response){
                var result=$.ui.autocomplete.filter(cities_json,request.term);

            },
            select:function(event,ui){
                $(element_selector).val(ui.item.name)
            },
            _renderItem:function (ul,item) {
                return $("<li>")
                    .append("<div>"+item.name+"</div>")
                    .appendTo(ul);
            }
        });
    }
    return publicFuncs;
})();
