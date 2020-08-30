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
        }
    }
    return self;
};


function loadFilters(filters_,data){
    var self={
        min_rent:10000000,
        max_rent:0,
    }
    return self;
}
// $(window).scroll(function () {
//     let headingTop=$("#listprops-heading-bar").offset().top;
//     console.log($(window).scrollTop());
//
//     if($(window).scrollTop()>=headingTop){
//        // $("#listprops-heading-bar").css("position","fixed");
//         $("#listprops-main-box").css("position","fixed");
//     }
// });

$(document).ready(function () {

    // $("#filter-props-form").on("submit",function (e) {
    //     let url=$(this).attr("action");
    //     e.preventDefault();
    //     $.ajax({
    //         url:url,
    //         data:$(this).serialize(),
    //         dataType:'json',
    //         success:function(data){
    //             if (data=="" || typeof data == undefined){
    //                 $("#listprops-grid").html("<h2>No property matches your criteria</h2>");
    //             }else {
    //                 propsConfig("#listprops-grid", "#single-listing", ".listprops-item", data).loadProps();
    //             }
    //         }
    //     });
    // });


    //price filter slider jquery ui initialisation
    $("#price-filter-slider").slider({
        range:true,
        min:0,
        max:500,
        values:[],
        slide:(event,ui)=>{
            $( "#minPrice" ).val( ui.values[ 0 ] );
            $( "#maxPrice" ).val( ui.values[ 1 ] );
        }
    });


    /**
     * Set initial min max input values by getting data from price slider
     * @param minPrice
     * @param maxPrice
     */
    function setMinMaxFilterPrice(minPrice,maxPrice){
        $("#minPrice").val(minPrice);
        $("#maxPrice").val(maxPrice);
    }
    //calling above function with values taken from slider
    setMinMaxFilterPrice($("#price-filter-slider").slider("values",0),$("#price-filter-slider").slider("values",1));


    // function changeSliderPrice(priceToChange){
    //     if (priceToChange=="min"){
    //         alert("fds");
    //     }
    // }
    //
    // $("#minPrice").on("change",function(){
    //     changeSliderPrice("min");
    // });


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
    var min_price=propsConfig("#listprops-grid","#single-listing",".listprops-item", props).loadProps();

});