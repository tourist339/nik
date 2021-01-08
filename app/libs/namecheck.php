<?php


$query_name = filter_input(FILTER_GET,"q",FILTER_SANITIZE_SPECIAL_CHARS);

$my_name="Saksham";

$arr_to_return=[];

if ($query_name==null){
    array_push($arr_to_return,null);
    array_push($arr_to_return,0);

    return json_encode($arr_to_return);
}else{
    array_push($arr_to_return,$query_name);
}

if (strcmp($query_name,$my_name)==0){
    array_push($arr_to_return,1);

}else{
    array_push($arr_to_return,0);

}

return json_encode($arr_to_return);

?>