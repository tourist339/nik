$(document).ready(function(){


    //navbar color change on scroll

    $(window).scroll(function () {

        let baseTextVal=100;
        let incTextVal=255-baseTextVal;
        let windowScrolled =$(window).scrollTop()/$(window).height();
        let alpha=3.5*windowScrolled;
        let textRGB=baseTextVal+incTextVal*alpha;
        $("nav").css("background-color","rgba(1,1,1,"+alpha+")");
        $("nav a").css("color","rgb("+textRGB+","+textRGB+","+textRGB+")");
        let url=window.location.pathname;
        let searchScroll;
        if(url=="/" || url=="/home" || url =="/home/index"){
            searchScroll=windowScrolled;
            if(searchScroll>=1){
                $("nav .search-prop-input").css("opacity",1);

            }else{

                $("nav .search-prop-input").css("opacity",0);

            }
        }else{
            $("nav .search-prop-input").css("opacity",1);

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

    lyfly.loadCities(".locationInput");
});