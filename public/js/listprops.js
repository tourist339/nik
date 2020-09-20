/**
 * Module to load the props in the parent div
 * @param parent: id of the div where all the properties are to be loaded
 * @param template:id of the template which contains html structure of a single property ,
 * @param selector: id of the div of the single property template
 * @param props: json data containing information about all the propertues
 * @returns the  object containing all the module functions
 * */
function propsConfig(parent,template,selector,props){
    self={
        num:1,

        parent:$(parent),
        template:$(template),
        newElement:()=>{
            var newEl=self.template.children().clone(false,false);
            newEl[0].setAttribute("num",self.num);
            return newEl;
        },
        currentElement:()=>{return $(selector+"[num='"+self.num+"']");},
        increaseNum:()=>{self.num+=1;},
        imageOverlay:()=>{return self.currentElement().children(".img-overlay");},
        propTitleBox:()=>{return self.currentElement().children(".prop-title-box");},
        setSrc:(prop_title)=>{self.currentElement().attr("src",prop_title)},

        setImages:(images)=>{
            var num=1;
            for(image of images){
                var img=$('<img>').attr("num",num).attr("src","/uploads/"+image);
                self.imageOverlay().append(img);
                num++;
            }
            num--;
            self.imageOverlay().children(".numOfImgs").val(num);
        },
        setDescription:(description)=>{
            self.imageOverlay().children(".show-desc-area").html("<p>"+description+"</p>");
        },
        setTitle:(title)=>{
            self.propTitleBox().children(".prop-title").html(title);
        },
        setAddress:(address)=>{
        self.propTitleBox().children(".prop-address").html(address);

        },
        setWishlist:(wishlisted,id=0)=>{
            var wishlistBtn=self.imageOverlay().children(".add-to-wishlist-btn");
                wishlistBtn.attr("wishlist",wishlisted);

                wishlistBtn.attr("for", id);

        },

        loadProps:function () {
            console.log(props);

            if (props==""){
                this.parent.append("<h2 class='text-body'>Sorry no matched properties in the city.</h2>")
            }else{
            var i=0;
            this.parent.html("");
            for (prop of props){

                this.parent.append(this.newElement());

                let prop_title = "/prop/v/" + prop["title"].replace(/\s+/g, "-") + "/" + prop["id"];
                let address = prop["address"] + " , " + prop["city"];
                var wishlisted = false;
                if (!(typeof prop["wishlisted"] == undefined)) {
                    wishlisted = prop["wishlisted"];
                }
                this.setWishlist(wishlisted,prop["id"]);
                let images = prop["images"].split(",");
                this.setSrc(prop_title);
                this.setImages(images);
                this.setDescription(prop["description"]);
                this.setTitle(prop["title"]);
                this.setAddress(address);
                this.increaseNum();
                i++;

            }
                }
            return self;
        }
    }
    return self;
}


/**
 *
 * @param filters_
 * @param default_data
 * @return self
 * */
function loadFilters(filters,default_data){
    var self={
        //helper dict to get ids and types of filter inputs with the keys as names of data submitted
        filters_dict:{
            ":minPrice":{id:"#minPrice",type:"text",place:"main"},
            ":maxPrice":{id:"#maxPrice",type:"text",place:"main"},
            ":gender":{id:"#gender",type:"text",place:"main"},
            ":pType":{id: "#prop-type-checkboxes",type:"checkbox",place:"main"},
            ":bedrooms":{id:"#nBedrooms",type:"num",place:"more",},
            ":bathrooms":{id:"#nBathrooms",type:"num",place:"more"},
            ":kitchen":{id:"#kitchen-toggle",type:"toggle",place:"more"},
            ":lyfly":{id:"#lyfly-toggle",type:"toggle",place:"more"},
            ":amenities":{id: "#amenities-filter-grid",type:"checkbox",place:"more"},
            ":rules":{id: "#rules-filter-grid",type:"checkbox",place:"more"}
        },

        //adds the name to the element , used in input type with nums
        addName:(element,name)=>{      element.attr("name",name);},

        //get the main filter form element where price and prop type are currently
        main_filters_form:()=> {return $("#main-filters-form");},
        //get the more filter form element where all the extra filters like no of bathrooms , bedrooms are
        more_filters_form:()=> {return $("#more-filters-form");},
        loadDefaultData:()=>{
            self.setMinMaxPrice(php_vars.minrent,php_vars.maxrent);
        },
        setMinMaxPrice:(minPrice,maxPrice)=>{
            $("#minPrice").val(minPrice);
            $("#maxPrice").val(maxPrice);
        },

        initialisePriceSlider:()=>{
            //price filter slider jquery ui initialisation
            $("#price-filter-slider").slider({
                range:true,
                min:php_vars.minrent,
                max:php_vars.maxrent,
                values:[$("#minPrice").val(),$("#maxPrice").val()],

                slide:(event,ui)=>{
                    $( "#minPrice" ).val( ui.values[ 0 ] );
                    $( "#maxPrice" ).val( ui.values[ 1 ] );
        }

        });

        },
        bindPrices:()=>{
            $("#minPrice").bind("keyup", function () {
                var newMinPrice=parseInt($(this).val());
                var currMaxPrice= parseInt($("#maxPrice").val());
                if(newMinPrice<=currMaxPrice) {
                    $("#price-filter-slider").slider("values", 0, $(this).val());
                }else{
                    $("#price-filter-slider").slider("values", 0, currMaxPrice);
                }
            });
            $("#minPrice").bind("change", function () {
                var newMinPrice=parseInt($(this).val());
                var currMaxPrice= parseInt($("#maxPrice").val());
                if (newMinPrice < php_vars.minrent) {
                    $(this).val(php_vars.minrent);
                }
                if(newMinPrice>=currMaxPrice ){
                    $(this).val(currMaxPrice.toString());
                }
            });

            $("#maxPrice").bind("keyup", function () {
                var newMaxPrice=parseInt($(this).val());
                var currMinPrice= parseInt($("#minPrice").val());
                if(newMaxPrice>=currMinPrice) {
                    $("#price-filter-slider").slider("values", 1, $(this).val());
                }else{
                    $("#price-filter-slider").slider("values", 1, currMinPrice);
                }
            });
            $("#maxPrice").bind("change", function () {
                var newMaxPrice=parseInt($(this).val());
                var currMinPrice= parseInt($("#minPrice").val());
                if (newMaxPrice > php_vars.maxrent) {
                    $(this).val(php_vars.maxrent);
                }
                if(newMaxPrice<currMinPrice ){
                    $(this).val(currMinPrice.toString());
                }
            });
        }

        ,
        loadData:()=>{
            self.bindPrices();


            //if  filters are not set , load the default values
            if(filters.length==0){
                self.loadDefaultData();
            }else{
                for (var filter_key in filters){

                    if(filters.hasOwnProperty(filter_key)) {
                        var attributes=self.filters_dict[filter_key];
                        var element=$(attributes.id);

                        //to add the hidden inputs from main form to more filter form and vice versa
                        switch (attributes.place) {
                            case "main":
                                if(attributes.type=="checkbox"){
                                    //in case of amenities , house rules or property type with multiples values under same name
                                    var arr_values=filters[filter_key].trim("%").split("%");
                                    attributes.arr_values=arr_values;
                                    for (val of arr_values){
                                        self.more_filters_form().append("<input type='hidden' name='"+filter_key.substring(1)+"[]' value='"+val+"'>")

                                    }

                                }else {
                                    self.more_filters_form().append("<input type='hidden' name='" + filter_key.substring(1) + "' value='" + filters[filter_key] + "'>")
                                }
                                break;
                            case "more":

                                if(attributes.type=="checkbox"){
                                    //in case of amenities , house rules or property type with multiples values under same name
                                    var arr_values=filters[filter_key].trim("%").split("%");
                                    attributes.arr_values=arr_values;
                                    for (val of arr_values){
                                        self.main_filters_form().append("<input type='hidden' name='"+filter_key.substring(1)+"[]' value='"+val+"'>")

                                    }

                                }else {
                                    self.main_filters_form().append("<input type='hidden' name='" + filter_key.substring(1) + "' value='" + filters[filter_key] + "'>")
                                }
                        }

                        //to preload the previously set filters using it's appropriate type
                        switch(attributes.type){
                            case "text":
                                element.val(filters[filter_key]);
                                break;
                            case "num":
                                element.val(filters[filter_key]);
                                self.addName(element,filter_key.substring(1));
                                break;
                            case "toggle":
                                element.attr("checked","");
                                break;
                            case "checkbox":
                                for(val of attributes.arr_values) {
                                    element.find("input[value='" + val + "']").prop("checked",true);
                                }
                        }

                    }
                }
            }
            self.initialisePriceSlider();
        }
    }
    return self;
}






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
    //only load filters when props array is not empty
    if(php_vars.props!="") {
        loadFilters(php_vars.filters, null).loadData();
    }


});