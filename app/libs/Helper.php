<?php
 class Helper{
     public static function removeValueFromArray($array,$value){
         if (!empty($array)) {
             if (($key = array_search($value, $array)) !== false) {
                 unset($array[$key]);
             }
             $current_list=implode(",",$array);
         }else{
             $current_list="";
         }
         return $current_list;
     }


}
?>