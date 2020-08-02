$(document).ready(function(){
    function listProperties(location){
        window.location.href = "/prop/l/"+location;
    }
    $(".search-go-btn").on("click",function () {
        listProperties($(".searchInput").val());
    })
    $(".searchInput").keyup(function (e) {
        if(e.keyCode==13) {
            listProperties($(this).val());
        }
    });

    //navbar color change on scroll

    $(window).scroll(function () {

        var baseTextVal=100;
        var incTextVal=255-baseTextVal;
        var windowScrolled =$(window).scrollTop()/$(window).height();
        var alpha=3.5*windowScrolled;
        var textRGB=baseTextVal+incTextVal*alpha;
        $("nav").css("background-color","rgba(1,1,1,"+alpha+")");
        $("nav a").css("color","rgb("+textRGB+","+textRGB+","+textRGB+")");
        var url=window.location.pathname;
        var searchScroll;
        if(url=="/" || url=="/home" || url =="/home/index"){
            searchScroll=windowScrolled;
        }else{
            searchScroll=alpha;
        }

        if(searchScroll>=1){
            $("nav .search-prop-input").css("opacity",1);

        }else{

            $("nav .search-prop-input").css("opacity",0);

        }
    });
});