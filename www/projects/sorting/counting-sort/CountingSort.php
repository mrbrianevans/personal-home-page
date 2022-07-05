<?php
class CountingSort{
    public static function counting_sort($array){
        $element_count = array_count_values($array);
        $sorted_array = [];
        for ($i = 0; $i<count($element_count); $i++){
            for ($j=0; $j<$element_count[$i]; $j++){
                array_push($sorted_array, $i);
            }
        }
        return $sorted_array;
    }
}