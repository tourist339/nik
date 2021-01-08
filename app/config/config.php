<?php
define("ERROR_DEBUG_MODE",true); // true -> will show database errors to user , false->otherwise (set it false while deployment)
define("SETUP_DEBUG_MODE",true); // true -> will force user to enter required data (set it true while deployment) , false->otherwise
define("ADMIN_URL","admin");

//main styling constants
define("MAIN_SCRIPTS",array("jquery-3.5.1.js","login_sys.js","main.js",
    "load_custom_elements.js",
    "jquery-ui.min.js",
    "https://unpkg.com/aos@2.3.1/dist/aos.js"));
define("MAIN_CSS",array(
    "main.css",
    "jquery-ui.min.css",
    "https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css",
    "https://unpkg.com/aos@2.3.1/dist/aos.css"));
define("MAIN_NAVBAR","navbar.html");

//header footer constants
define("HEADER_NONE",0);
define("HEADER_NORMAL",1);
define("HEADER_SCRIPTS_AND_CSS",2);
define("FOOTER_NORMAL",1);
define("FOOTER_NONE",0);


//database name
define("DB_NAME","system_d");

//google api id
define("GOOGLE_CLIENT_ID","316686701656-1cs0k0pc8kpfihuirpetvltlrlp0nb9j.apps.googleusercontent.com");


//ERROR CODES
define("DB_ERROR_CODE","1X1XY");
require CONFIG.'stringhelper.php';
require CORE.'Application.php';

?>