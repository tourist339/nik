function propsConfig(parent,template,selector){
    self={
        num:1,
        parent:$(parent),
        template:$(template),
        newElement:()=>{
            var newEl=self.template.children().clone(false,false);
            console.log(newEl);
            newEl[0].setAttribute("num",self.num);
            console.log(newEl[0].outerHTML);
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
        loadProps:function () {
            let totalProps=props.length;
            var i=0;
            for (prop of props){

                this.parent.append(this.newElement());

                    let prop_title = "/prop/v/" + prop["title"].replace(/\s+/g, "-") + "/" + prop["id"];
                    console.log(prop_title);
                    let address = prop["address"] + " , " + prop["city"];
                    var wishlisted = false;
                    if (!typeof prop["wishlisted"] == undefined) {
                        wishlisted = prop["wishlisted"];
                    }
                    let images = prop["images"].split(",");
                    console.log(images);
                    this.setSrc(prop_title);
                    this.setImages(images);
                    this.setDescription(prop["description"]);
                    this.setTitle(prop["title"]);
                    this.setAddress(address);
                    this.increaseNum();
                    // if(i<totalProps-1) {
                    //     this.parent.append(this.newElement);
                    // }
                    i++;

            }
        }
    }
    return self;
};
$(document).ready(function () {

    $("#show-prop-filters").on("click",function () {
        let status= $("#filter-searched-props").attr("status");
        if(status=="close") {
            $("#filter-searched-props").attr("status", "open");
        }else{
            $("#filter-searched-props").attr("status", "close");

        }
    });

    $("#filter-props-form").on("submit",function (e) {
        $url=$(this).attr("action");
        $method=$(this).attr("method");
        e.preventDefault();
        $.get($url,function (data) {
            console.log(data);
        });
    });


    propsConfig("#listprops-grid","#single-listing",".listprops-item").loadProps();
});