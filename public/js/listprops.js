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
        min_rent:10000000,
        max_rent:0,
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
            if(wishlisted==true) {
                wishlistBtn.attr("for", id);
            }

        },
        updateRent:(rent)=>{
        if(rent<self.min_rent){
            self.min_rent=rent;
        }
        if(rent>self.max_rent){
            self.max_rent=rent;
        }
        },
        loadProps:function () {
            let totalProps=props.length;
            var i=0;
            this.parent.html("");
            for (prop of props){

                this.parent.append(this.newElement());

                let prop_title = "/prop/v/" + prop["title"].replace(/\s+/g, "-") + "/" + prop["id"];
                let address = prop["address"] + " , " + prop["city"];
                var wishlisted = false;
                if (!typeof prop["wishlisted"] == undefined) {
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
                this.updateRent(prop["rent"]);
                i++;

            }
            return self;
        }
    }
    return self;
}


function loadFilters(filters_,default_data){
    var self={
        filters_dict_type:{
            ":minPrice":"text",
            ":maxPrice":"text"
        },
        filters_dict_place:{
            //give main to the filters that are not in the more filters sectioin
            ":minPrice":"main",
            ":maxPrice":"main"
        },
        more_filters_form:()=> {return $("#more-filters-form");},
        initialisePriceSlider:()=>{
            //price filter slider jquery ui initialisation
            $("#price-filter-slider").slider({
            range:true,
            min:0,
            max:parseInt($("#maxPrice").val())+1000,
            values:[$("#minPrice").val(),$("#maxPrice").val()],
            slide:(event,ui)=>{
                $( "#minPrice" ).val( ui.values[ 0 ] );
                $( "#maxPrice" ).val( ui.values[ 1 ] );
        }

        });

        },
        setMinMaxPrice:(minPrice,maxPrice)=>{
            $("#minPrice").val(minPrice);
            $("#maxPrice").val(maxPrice);
        },
        loadDefaultData:()=>{
            self.setMinMaxPrice(0,10000);
        },
        loadData:()=>{
            //if  no filters are set , load the default values
            if(filters.length==0){
                self.loadDefaultData();
            }else{
                console.log("not null");
                for (var filter_key in filters){

                    if(filters.hasOwnProperty(filter_key)) {

                        //to add the hidden inputs from main form to more filter form and vice versa
                        switch (self.filters_dict_place[filter_key]) {
                            case "main":
                                self.more_filters_form().append("<input type='hidden' name='"+filter_key.substring(1)+"' value='"+filters[filter_key]+"'>")

                        }

                        //to preload the previously set filters using it's appropriate type
                        if (self.filters_dict_type[filter_key] == "text") {
                            $("#"+filter_key.substring(1)).val(filters[filter_key]);
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
    });

    //loadProps fills all the properties and then returns the self object from
    // where we can get the updated min and max rents
    var rents=propsConfig("#listprops-grid","#single-listing",".listprops-item", props).loadProps();

    console.log(filters);
    loadFilters(filters,null).loadData();


});