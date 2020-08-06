$(document).ready(function() {
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
    return publicFuncs;
})();
