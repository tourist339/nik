<?php


class login_controller extends Controller
{
    public function __construct()
    {

    }
    public function google_post(){
        $login_type="google";
        $usermodel = new user_model();
        require_once VENDOR.'autoload.php';

        $id_token=$_POST["id_token"];

        $client = new Google_Client(['client_id' => GOOGLE_CLIENT_ID]);  // Specify the CLIENT_ID of the app that accesses the backend

        $payload = $client->verifyIdToken($id_token);
        if ($payload) {
            $email=$payload["email"];
            if(!$usermodel->userExists($email,$login_type)) {
                $info=array("statusCode"=>"ASK_INFO","email"=>$email,"login_type"=>$login_type);
               session_start();
               session_regenerate_id(true);
               $_SESSION["payload"]=$payload;
               $_SESSION["login_type"]=$login_type;
            }else{
                $info=array("statusCode"=>"ALL_OK");
                $id=$usermodel->getUserId($payload["email"],$login_type);
               $this->login($id,$payload,$login_type);
            }
            echo json_encode($info);

        }
        $usermodel->closeDb();
    }

    private function login($id,$payload,$login_type){
        session_start();
        session_regenerate_id(true);
        $_SESSION["auth"]=true;
        $_SESSION[Session::ID]=$id;
        if(isset($_SESSION[Session::ACTIVE_PROP])){
            unset($_SESSION[Session::ACTIVE_PROP]);
        }

        $_SESSION[Session::EMAIL]=$payload["email"];
        $_SESSION[Session::LOGIN_TYPE]=$login_type;
    }


        public function update_user(){
        session_start();
        if(isset($_SESSION["payload"]) and isset($_SESSION["login_type"])){
            $payload=$_SESSION["payload"];
            $login_type=$_SESSION["login_type"];
            session_unset();
            session_destroy();
            if(isset($_POST["pNum"]) && isset($_POST["address"]) && isset($_POST["city"]) && isset($_POST["state"]) ){
               $payload["pNum"]=$_POST["pNum"];
                $payload["address"]=$_POST["address"];
                $payload["city"]=$_POST["city"];
                $payload["state"]=$_POST["state"];

                $model=new user_model();
                $model->createUser($payload,$login_type);
                $id=$model->getUserId($payload["email"],$login_type);
                $this->login($id,$payload,$login_type);
            }
        }
         }
    public function custom_post(){

    }

    public function logout(){
        Session::logout();
    }

    public function getSigned(){
        session_start();
        if(isset($_SESSION["id"])) {
            $login_type=$_SESSION["login_type"];
            echo $login_type;
        }else{
            echo "notset";
        }
    }
}