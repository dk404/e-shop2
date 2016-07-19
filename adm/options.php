<?php
require_once("../functions/DB.php");
require_once("../functions/auth.php");
require_once("../functions/path.php");

if(!is_admin()){exit("пока пока");}


//Удалить элемент из слайдера
if($_GET["method_name"] == "deleteBigSlider" && is_numeric($_GET["ID"])){
    $resDb = db_delete("bigSlider", "ID=".$_GET["ID"]);
    if(!$resDb["error"]){ echo 1; }
}

//Удалить элемент из брендов
if($_GET["method_name"] == "deleteBrand" && is_numeric($_GET["ID"])){

    $arr = [
        "ID" => $_GET["ID"]
        ,"table" => "brands"
        ,"imgDir" => "forBrands"
    ];

    $resDel = delItem($arr);


    //response
    if($_SERVER['HTTP_X_REQUESTED_WITH']!='XMLHttpRequest') {

        header("Location: ".$_SERVER["HTTP_REFERER"]);
    }
    else
    {
        print_r(json_encode($resDel)); exit();
    }


    if(!$resDel["error"]){ echo 1; }
}
//Сортировка
if($_POST["method_name"] == "sort" && $_POST["table"]){

    $response = [];
    parse_str($_POST["data"], $arr);

    if(!$arr["nomer"]){ $response["error"] = "Ошибка: отсутствуют данные"; }
    else{
        $tmp = [];
        foreach ($arr["nomer"] as $nomer => $ID) {
            $tmp[$nomer]["ID"]      = $ID;
            $tmp[$nomer]["nomer"]   = $nomer;
        }

        $resDb = db_duplicate_update($_POST["table"], $tmp, true);
        if($resDb["error"]){ $response["error"] = $resDb["error"]; }
        $response["response"] = $resDb;

    }

    //перевести json
        echo json_encode($response);
}

//Удалить товар
if($_GET["method_name"] == "deleteProduct" && is_numeric($_GET["ID"])){

    $response = [
        "error" => null
    ];

    //узнаем есть ли такая запись
    $resItem = db_row("SELECT * FROM products WHERE ID=".$_GET["ID"])["item"];
    if(!$resItem){ $response["error"] = "Ошибка такого элемента не найдено";

        if($_SERVER['HTTP_X_REQUESTED_WITH']!='XMLHttpRequest') {

            header("Location: ".$_SERVER["HTTP_REFERER"]);
        }
        else
        {
            print_r(json_encode($response)); exit();
        }

    }

    //удалим запись
    $resDb = db_delete("products", "ID=".$_GET["ID"], true);
    if($resDb["error"]){
        $response["error"] = $resDb["error"];

        if($_SERVER['HTTP_X_REQUESTED_WITH']!='XMLHttpRequest') {

            header("Location: ".$_SERVER["HTTP_REFERER"]);
        }
        else
        {
            print_r(json_encode($response)); exit();
        }
    }

    //удалим картинки
    if($resItem["photo"])
    {
        $tmp["big"]     = path_clear_path()."/FILES/products/big/".$resItem["photo"];
        $tmp["small"]   = path_clear_path()."/FILES/products/small/".$resItem["photo"];

        if(file_exists($tmp["small"])){ unlink($tmp["small"]);  }
        if(file_exists($tmp["big"])){ unlink($tmp["big"]);  }

    }

    //response
    if($_SERVER['HTTP_X_REQUESTED_WITH']!='XMLHttpRequest') {

        header("Location: ".$_SERVER["HTTP_REFERER"]);
    }
    else
    {
        print_r(json_encode($response)); exit();
    }


}


//Универсальная ф-ия del
function delItem($array){


    $ID         = $array["ID"];
    $table      = $array["table"];
    $imgDir     = $array["imgDir"]; //название папки где лежит фото
    $imgCol     = ($array["imgCol"])? $array["imgCol"] : "img"; //название поля в бд где храниться название фотки

    $response = ["error" => false, "response" => null];

    //сделаем выборку этой записи
    $resItem = db_row("SELECT * FROM ".$table." WHERE ID='".$ID."'")["item"];
    if(!$resItem){ $response["error"] = "Ошибка: такой записи не обнаружено"; return  $response;   }

    //Удаляем саму запись
    $resDel1 = db_delete($table, "ID='".$ID."'");
    if($resDel1["error"]){ $response["error"] = $resDel1["error"]; return  $response;   }

    //Удалим фото
    if($imgDir && $resItem[$imgCol]){
        $path = "../FILES/".$imgDir."/";
        $tmp = ["big/", "small/"];

        foreach ($tmp as $item) {
            $yy = $path.$item.$resItem[$imgCol];
            if(file_exists($yy)){ unlink($path.$item.$resItem[$imgCol]); }
        }

    }

    //response
    $response["response"] = $resDel1;
    return  $response;


}