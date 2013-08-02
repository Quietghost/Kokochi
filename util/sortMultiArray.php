<?php

function array_sort($array, $on, $order=SORT_ASC)
{
    $new_array = array();
    $sortable_array = array();

    if (count($array) > 0) {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $k2 => $v2) {
                    if ($k2 == $on) {
                        $sortable_array[$k] = $v2;
                    }
                }
            } else {
                $sortable_array[$k] = $v;
            }
        }

        switch ($order) {
            case SORT_ASC:
                asort($sortable_array);
            break;
            case SORT_DESC:
                arsort($sortable_array);
            break;
        }

        foreach ($sortable_array as $k => $v) {
            $new_array[$k] = $array[$k];
        }
    }

    return $new_array;
}


function in_array_field($array, $value, $key){
    //loop through the array
    foreach ($array as $val) {
      //if $val is an array cal myInArray again with $val as array input
      if(is_array($val)){
        if(in_array_field($val,$value,$key))
          return true;
      }
      //else check if the given key has $value as value
      else{
        if($array[$key]==$value)
          return true;
      }
    }
    return false;
}



?>