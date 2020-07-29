$(document).ready(function() {
    $(".add-to-wishlist-btn").on("click", function () {
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
    });
});