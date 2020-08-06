$(document).ready(function() {
    function nextImg(e) {
        $(this).prop("disabled", true);
        var btn = this;
        let totalImgs = $(this).siblings(".numOfImgs").val();
        var active = $(this).siblings(".active");
        let num = parseInt(active.attr("num"));
        let initial = $(this).parent(".img-overlay").hasClass("initial");
        if (initial) {
            $(this).parent(".img-overlay").removeClass("initial");
        }
        let nextNum = num + 1;
        var nextImg;
        if (nextNum > totalImgs) {
            nextNum = 1;
            $(nextImg).removeClass("back");
        }
        nextImg = $(this).siblings("img[num='" + nextNum + "']");
        $(nextImg).addClass("nextImg");
        $(nextImg).addClass("active");
        $(active).addClass("back");

        $(nextImg).addClass("back");

        var waitForBack = function () {
            var a = $.Deferred();
            setTimeout(function () {
                // and call `resolve` on the deferred object, once you're done
                a.resolve();
            }, 600);

            // return the deferred object
            return a;
        }
        waitForBack().done(function () {
            $(active).removeClass("back");
            $(active).removeClass("active");
            $(active).removeClass("stay");

            $(nextImg).removeClass("nextImg");
            $(nextImg).removeClass("back");
            $(nextImg).addClass("stay");
            $(btn).prop("disabled", false);

        });


        e.stopPropagation();

    }

    function prevImg(e) {
        console.log("prev clicked");
        $(this).prop("disabled", true);
        var btn = this;
        let totalImgs = $(this).siblings(".numOfImgs").val();
        var active = $(this).siblings(".active");
        let num = parseInt(active.attr("num"));
        console.log(num);
        let initial = $(this).parent(".img-overlay").hasClass("initial");
        if (initial) {
            $(this).parent(".img-overlay").removeClass("initial");
        }
        let prevNum = num - 1;
        var prevImg;
        if (prevNum == 0) {
            prevNum = totalImgs;
            $(prevImg).removeClass("front");
        }
        prevImg = $(this).siblings("img[num='" + prevNum + "']");
        $(prevImg).addClass("prevImg");
        $(prevImg).addClass("active");
        $(active).addClass("front");

        $(prevImg).addClass("front");

        var waitForFront = function () {
            var a = $.Deferred();
            setTimeout(function () {
                a.resolve();
            }, 600);

            return a;
        }
        waitForFront().done(function () {
            $(active).removeClass("front");
            $(active).removeClass("active");
            $(active).removeClass("stay");

            $(prevImg).removeClass("prevImg");
            $(prevImg).removeClass("front");
            $(prevImg).addClass("stay");
            $(btn).prop("disabled", false);

        });
        e.stopPropagation();

    }


    $(".img-overlay .img-next").on("click", nextImg);
    $(".img-overlay .img-prev").on("click", prevImg);
});