<?php

//Функции для разного уровня экранизации

/**
 * Максимальный экран
 * @param $str
 * @return string
 */
function proverka1($str){
    $str = htmlspecialchars($str);
    $str = addslashes($str);

    return $str;
}

/**
 * Малое экранирование, для админ раздела
 * @param $str
 * @return string
 */
function proverka2($str){
//    $str = htmlspecialchars($str);
    $str = addslashes($str);

    return $str;
}

/**
 * рекурсивная проверка массива
 * @param $arr
 * @param int $n - номер нужной ф-ии проверки
 * @return array
 */
function proverka_recursive($arr, $n = 1){

    $proverka = "proverka".$n;
    $newArr   = [];
    foreach ($arr as $item => $value) {
        if(empty($value)){ continue; }
        $newArr[] = $proverka($value);
    }

    return $newArr;
}



