$(document).ready(function(){


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

    $("#hp-prop-right").on("click",function () {
        let card_width=$("#hp-cityprops-scroller").children(".card").outerWidth();
        let already_scrolled=$("#hp-cityprops-scroller").scrollLeft();
        $("#hp-cityprops-scroller").animate({scrollLeft:card_width+already_scrolled},"slow");
    });
    $("#hp-prop-left").on("click",function () {
        let card_width=$("#hp-cityprops-scroller").children(".card").outerWidth();
        let already_scrolled=$("#hp-cityprops-scroller").scrollLeft();
        $("#hp-cityprops-scroller").animate({scrollLeft:-card_width+already_scrolled},"slow");
    });
});