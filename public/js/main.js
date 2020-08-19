$(document).ready(function() {

    //Search Input box Script
    function listProperties(location,searchKey=""){
        //replace one or more whitespace characters with dash
        searchKey=searchKey.replace(/\s+/g , "-" );
        window.location.href = "/prop/l/"+location+"/"+searchKey;

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

//helper function for checking logged in
var lyfly=(function(){
   var publicFuncs={};

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
    return publicFuncs;
})();
