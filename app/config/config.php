<?php
define("ERROR_DEBUG_MODE",true); // true -> will show database errors to user , false->otherwise (set it while deployment)
define("SETUP_DEBUG_MODE",true); // true -> will force user to enter required data (set it while deployment) , false->otherwise

define("MAIN_SCRIPTS",array("jquery-3.5.1.js","login_sys.js"));
define("MAIN_CSS","main.css");
define("MAIN_NAVBAR","navbar.html");
define("DB_NAME","system_d");
define("GOOGLE_CLIENT_ID","316686701656-1cs0k0pc8kpfihuirpetvltlrlp0nb9j.apps.googleusercontent.com");
require CONFIG.'stringhelper.php';
require CORE.'Application.php';

?>