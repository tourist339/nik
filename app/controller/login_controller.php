<?php


class login_controller extends Controller
{
    public function google_post(){
        require_once VENDOR.'autoload.php';

        $id_token=$_POST["id_token"];
        $client = new Google_Client(['client_id' => "316686701656-1cs0k0pc8kpfihuirpetvltlrlp0nb9j.apps.googleusercontent.com"]);  // Specify the CLIENT_ID of the app that accesses the backend

        $payload = $client->verifyIdToken($id_token);
        if ($payload) {
            $userid = $payload['sub'];
            echo $payload["email"];
            session_start();
            $_SESSION["email"]=$payload["email"];
            $_SESSION["login_type"]="google";
            print_r($payload);
            // If request specified a G Suite domain:
            //$domain = $payload['hd'];
        } else {
            // Invalid ID token
        }
    }

    public function custom_post(){

    }
    public function loginmodal(){
        $cView=$this->createView('login/loginmodal',[],"template");
        $cView->render();
    }

    public function logout(){
        session_start();
        session_destroy();
        header("Location: /");
    }
}