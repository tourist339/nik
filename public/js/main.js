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

     // Review box script
    var stars=    parseFloat($(".front-stars").attr("stars"));
    var total=    parseFloat($(".front-stars").attr("total"));
    var percentage=stars/total *100;
    console.log(percentage);
    $(".front-stars").css("width",percentage+"%");


    //Image overlay sliding script

    function nextImg(e){
        let totalImgs=$(this).siblings(".numOfImgs").val();
        var active=$(this).siblings(".active");
        let num=parseInt(active.attr("num"));
        console.log(num);
        let initial=$(this).parent(".img-overlay").hasClass("initial");
        if(initial){
            $(this).parent(".img-overlay").removeClass("initial");
        }
        let nextNum=num+1;
        let prevNum=num-1;
        var nextImg;
        var secphase=false;
        if(nextNum>totalImgs){
            nextNum=1;
        }
        nextImg=$(this).siblings("img[num='"+nextNum+"']");
        nextImg.addClass("nextImg");
        console.log(nextNum);
        if(prevNum<1){
            prevNum=totalImgs;
        }

        //debugger;
        if (initial) {
            $(active).css("transform", "translateX(-100%)");
        } else {
            $(active).css("transform", "translateX(-200%)");

        }
        let transormActive = -num * 100;
            $(nextImg).css("transition","none");
            $(nextImg).css("transform","");
            $(nextImg).css("position","relative");
            $(nextImg).css("left","");
            $(nextImg).css("transition","transform 0.4s linear");

            $(nextImg).css({"position":"absolute","top":"0","left":"100%"});




        $(nextImg).css("transform", "translateX(-200%)");

        $(active).removeClass("active");
        $(nextImg).removeClass("nextImg");
        $(nextImg).addClass("active");
        // $(active).css("transform","");
        // $(nextImg).css("transform","");



        e.stopPropagation();

    }
    function prevImg(e){
        let totalImgs=$(this).siblings(".numOfImgs").val();
        var active=$(this).siblings(".active");
        let num=parseInt(active.attr("num"));
        let prevNum=num-1;
        if(prevNum<1){
            prevNum=totalImgs;
        }
        var prevImg=$(this).siblings("img[num='"+prevNum+"']");
        $(prevImg).css({"position":"absolute","top":"0","left":"100%"});

        let transormActive=num*100;

        $(active).css("transform","translateX("+transormActive+"%)");
        $(prevImg).css("transform","translateX(100%)");
        $(active).removeClass("active");
        $(prevImg).addClass("active");

        e.stopPropagation();

    }



    function positionNextElements(nextImg,prevImg){
    }
    function translateBack(){

    }

    $(".img-overlay .img-next").on("click",nextImg);
    $(".img-overlay .img-prev").on("click",prevImg);

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
