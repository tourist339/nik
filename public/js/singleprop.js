//Sticky Contact Info
$(document).ready(function(){
    if($("#propDesc")[0].clientHeight < $("#propDesc")[0].scrollHeight){

    }else{
        $("#showDesc").css("display","none");

    }
    var contactTop=$("#contact-box").offset().top;
    var bottomImagePos=$("#images-box").offset().top+$("#images-box").outerHeight();
    let padding =10;
    var navbarHeight=0;
    $(window).scroll(function () {
        var windowTop =$(window).scrollTop();



        //For showing property navbar
        if ($(window).scrollTop()>=bottomImagePos){
            $("#prop-navbar").removeClass("hidden");
            $("#prop-navbar").addClass("rowflex");
            if(navbarHeight==0)
                navbarHeight=$("#prop-navbar").outerHeight();

        }else{
            if(!$("#prop-navbar").hasClass("hidden")) {
                $("#prop-navbar").addClass("hidden");
                $("#prop-navbar").removeClass("rowflex");

            }

        }

        //for fixing contact box on the right side
        var toSitck=$("#contact-box").position().top;
        if(windowTop+navbarHeight+padding>=contactTop){
            $("#contact-box").addClass("sticky");
            $("#contact-box").css("top",navbarHeight+padding);

            $("#contact-box").width($("#right-prop-content").width());
        }else{
            $("#contact-box").removeClass("sticky");
            $("#contact-box").width("100%");

        }
    });

    $("#book-now-btn").on("click",function () {
        if(lyfly.checkLoggedIN())
            $("#booking-modal").modal();

    });
    $(".visit-time").on("click",function () {
        var attr = $(this).attr("name");
        if (typeof attr !== typeof undefined && attr !== false) {
            //if element's already been selected
            $(this).removeAttr("name");
            $(this).css("background-color","white");
        }else{
            $(this).attr("name","visit-time[]");
            $(this).css("background-color","lightgray");

        }
    });


    $("#prop-navbar").on("click","a",function () {
        var toScrollElement=$(this).attr("for");
        var padding=10;
        var finalToScroll=$(toScrollElement).offset().top-padding-navbarHeight;
        $('html,body').animate({scrollTop: finalToScroll},'slow');
    });

});

function showFull(){
    $("#propDesc").css("height","auto");
    $("#showDesc").css("display","none");
}

