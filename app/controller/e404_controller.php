<?php


class e404_controller extends Controller
{
    public const NORMAL_ERR=1;


    public function __construct($errormsg="File Not Found",$type=self::NORMAL_ERR)
    {
        switch ($type) {
            case self::NORMAL_ERR:
                $this->createView('e404/index', ["title" => "LyfLy",
                        "scripts" => [MAIN_SCRIPTS],
                        "stylesheets" => [MAIN_CSS,"e404.css"],
                        "errormsg"=>$errormsg,
                        "navbar" => MAIN_NAVBAR,
                    ]
                )->render();
                break;

        }

        exit();
    }
}