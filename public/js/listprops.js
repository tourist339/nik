





$(document).ready(function () {



    $("#more-filters-form").attr("action",window.location.href);

    $(".filter-btn").on("click",function () {
        let filterbox=$(this).siblings(".filter-box");
        var show=false;
        if(filterbox.attr("visible")=="false"){
            show=true;
        }
        $(".filter-box").each(function () {
                if ($(this).attr("visible") == "true")
                    $(this).attr("visible", "false");

            }
        );
        if (show)
            filterbox.attr("visible","true");
    });

    $("#filter-more-btn").on("click",function () {
        console.log("fds");
        $("#more-filters-modal").modal({
            fadeDuration: 200,
            fadeDelay: 0.80
        });
    });

    $("#clear-filter-btn").on("click",function () {
        window.location.assign(url_without_filters);
    });

    $("#main-filters-form").on("submit",function () {
        if(parseInt($("#minPrice").val())==php_vars.minrent){
            $("#minPrice").removeAttr("name");
        }
        if(parseInt($("#maxPrice").val())==php_vars.maxrent){
            $("#maxPrice").removeAttr("name");
        }
    });


    //event attached to plus and minus buttons inside more filters box or any
    // inputbox element with type small number
    $("inputbox[type='smallnumber']").on("click","button",function () {
        let sibling_input=$(this).siblings("input");
        var current_val=sibling_input.val();
        if($(this).hasClass("minus")){
            if(current_val>0)
                sibling_input.val(--current_val);

        }else if($(this).hasClass("plus")){
            if(current_val<16)
                sibling_input.val(++current_val);

        }

        if (current_val<=0)
            sibling_input.removeAttr("name");
        else
            sibling_input.attr("name",sibling_input.attr("for"));
    });

    //loadProps fills all the properties and then returns the self object from
    // where we can get the updated min and max rents
    propsConfig("#listprops-grid","#single-listing",".listprops-item", php_vars.props).loadProps();
    console.log(php_vars.filters);
    loadFilters(php_vars.filters, null).loadData();



});