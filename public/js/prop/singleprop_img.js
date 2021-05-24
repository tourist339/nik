

function syncImagesSplide() {

    let parent =$("#primary-slider").find(".splide__list")



    $("#primary-slider").find(".splide__slide").each(function (){
        let child=$(this).children("img")[0]
        child.style.width="100px";
        child.style.height="100px";

        let dimensions=getAdjustedDimensions(child,parent[0])

        child.style.width=dimensions.width+"px";
        child.style.height=dimensions.height+"px";
    })
}

function initSlideshow(resolve, reject) {

    let primarySlider = new Splide( '#primary-slider', {
        type:'fade',
        pagination:false,
        arrows: false,
    } );

    let secondarySlider=new Splide( '#secondary-slider', {
        fixedWidth: 200,
        height: 150,
        cover: true,
        breakpoints : {
            '600': {
                fixedWidth: 66,
                height    : 40,
            }
        },
        rewind    : true,
        gap       : 10,
        focus      : 'center',
        isNavigation: true,
        pagination: false,
    } ).mount();

    resolve(primarySlider.sync( secondarySlider ).mount());


}

$(document).ready(function (){

    let modal= document.getElementsByClassName("custom-modal")[0];
    $("#imgs-showall").on("click",function (){
        modal.style.display = "block";
        let initS=new Promise(initSlideshow).then(function (response) {
            console.log(response)
            syncImagesSplide()
            console.log($("#primary-slider").find(".splide__list").height())

        })
    })
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    displayMap();

})
function initMap(latitude,longitude) {

    var uluru = {lat: latitude, lng: longitude};

    var map = new google.maps.Map(
        document.getElementById('map'), {zoom: 18,
            center: uluru});
    // The marker, positioned at Uluru
    var marker = new google.maps.Marker({position: uluru,
        draggable:true,
        animation:google.maps.Animation.DROP,
        map: map});
}

function displayMap(){
    var geocoder = new google.maps.Geocoder();
//        address to be changed later
//     var address = "<?php
//     echo getProperAddress($propdata["address"],$propdata["city"],$propdata["state"]);
//         ?>";
    geocoder.geocode( { 'address': address}, function(results, status) {

        if (status == google.maps.GeocoderStatus.OK) {
            var latitude = results[0].geometry.location.lat();
            var longitude = results[0].geometry.location.lng();


            initMap(latitude,longitude);

        }
    });
}
