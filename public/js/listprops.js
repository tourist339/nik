
$(document).ready(function () {

    $("#show-prop-filters").on("click",function () {
        let status= $("#filter-searched-props").attr("status");
        if(status=="close") {
            $("#filter-searched-props").attr("status", "open");
        }else{
            $("#filter-searched-props").attr("status", "close");

        }
    });
});