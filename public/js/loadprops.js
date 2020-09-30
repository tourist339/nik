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
                this.parent.append("<h2 class='text-body'>Sorry no matched properties in "+php_vars.city+".</h2>")
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


$(document).ready(function() {

    $("#listprops-grid").on("click",".listprops-item",function(){
        var check1=$(this).find(".img-next").prop("disabled");
        var check2=$(this).find(".img-prev").prop("disabled");

        if(check1==false && check2==false)
            window.location.href = $(this).attr("src");
    });
    $("#listprops-grid").on("click",".add-to-wishlist-btn",function (e) {
        lyfly.setWishlist(e,$(this));
    });


    $("#listprops-grid").on("click",".show-desc-btn",function (e) {
        var descArea=$(this).siblings(".show-desc-area");
        if(descArea.css("display") == "none") {
            descArea.fadeIn(300);
        }else{
            descArea.fadeOut(300);

        }
        e.stopPropagation();
    });
    $(".img-overlay img[num='1']").addClass("active");

});
