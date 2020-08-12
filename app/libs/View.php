<?php

class View
{
    protected $view_path;
    protected $view_data;
    protected $view_type;

    public function __construct($view_path,$view_data=[],$view_type)
    {
        $this->view_path=$view_path;
        $this->view_data=$view_data;
        $this->view_type=$view_type;
    }
    public function render($inc_header=HEADER_NORMAL,$inc_footer=FOOTER_NORMAL){


        if($this->view_type=="file") {
            switch ($inc_header){
                case HEADER_NORMAL:
                    require VIEW.'header.phtml';
                    break;
                case HEADER_SCRIPTS_AND_CSS:
                    require VIEW.'headeronlyscripts.phtml';
                    break;
                case HEADER_NONE:
                    break;
            }
            $full_path = VIEW . $this->view_path . ".phtml";
            $this->require_path($full_path);
            if($inc_footer)
                require VIEW.'footer.phtml';
        }
        elseif($this->view_type=="template"){
            $full_path = TEMPLATE .$this->view_path .".html";
            $this->require_path($full_path);
        }




    }
    private function require_path($full_path){
        if(file_exists($full_path)){
            require $full_path;

        }else{
            echo "NOt exists".$full_path;
        }
    }
}