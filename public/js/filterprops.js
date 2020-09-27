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
        loadDefaultPrices:()=>{
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

            if(filters.length==0|| (!("minPrice" in filters) ||!("maxPrice" in filters) )){
                self.loadDefaultPrices();
            }

            //if  filters are not set , load the default values
            if(filters.length!=0){

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
