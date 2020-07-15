function openLogin(){
   $("#login-modal").modal({
      fadeDuration:120
   });
}

$("#signin-button").on("click",function () {
    auth2.signIn({
        prompt:"consent",
    }).then(function() {
        var googleUser=auth2.currentUser.get();
        var idToken=googleUser.getAuthResponse().id_token;
        $.post("/login/getSigned",{"id_token":idToken},function(data){
            console.log(data);
            data=JSON.parse(data);
            // alert(data);
            if(data["statusCode"]=="ASK_INFO"){
                $("#login-info-modal").modal({
                    fadeDuration: 120,
                    escapeClose : false,
                    clickClose: false,
                    showClose : false
                });
            }else{
               window.location.reload(true);
            }
        });
    });
});
$("#login-info-submit").submit(function(e){
    e.preventDefault();
    $.post($(this).attr("action"),$(this).serialize(),function(data){
       window.location.reload(true);
    });
});

var signed=false;
var login_type=null;
$.get("/login/getSigned",function (data) {
    console.log(data);
    if(data.localeCompare("notset")!=0) { //data!= "notset"( meaning data is set) , so signed should be true
        signed=true;
        login_type=data;
    }
    if(signed==true){
        $("#nav-signout").show();
    }else{

        $("#nav-signin").show();
    }
});



$("#nav-signout").on("click",function () {
    if(signed) {
        if (login_type == "google") {
            var auth2 = gapi.auth2.getAuthInstance();
            auth2.signOut().then(function () {
            });
        }
        $.post("/login/logout", function (data) {
            window.location.reload(true);
        });
    }
});