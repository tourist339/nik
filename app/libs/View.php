<?php


class View
{
    protected $view_path;
    protected $view_data;

    public function __construct($view_path,$view_data=[])
    {
        $this->view_path=$view_path;
        $this->view_data=$view_data;

    }
    public function render($inc_header=true,$inc_footer=true){
        if($inc_header)
        require VIEW.'header.phtml';

        $full_path=VIEW.$this->view_path.".phtml";
        if(file_exists($full_path)){
            require $full_path;
        }else{
            echo "NOt exists".$full_path;
        }

        if($inc_footer)
        require VIEW.'footer.phtml';

    }
}