<?php
require_once("../functions/DB.php");
require_once("../functions/auth.php");
require_once("../functions/path.php");
require_once("../functions/proverki.php");
require_once("../functions/saveImg.php");

$Admin = is_admin();
if(!$Admin){ exit("Нет прав доступа"); }

$referer        = ($_POST["referer"])? $_POST["referer"]: $_SERVER["HTTP_REFERER"];
if(!$_GET["stranica"]){ header("Location: ".$referer); exit(); }else{ $stranica = proverka1($_GET["stranica"]);  }

if(is_numeric($_GET["ID"]) || is_numeric($_POST["ID"]))
{

    if($_POST["ID"]){ $ID   = $_POST["ID"]; }
    if($_GET["ID"]) { $ID   = $_GET["ID"]; }

}
else{
    $ID = false;
}


$thisPage       = path_withoutGet();
$table          = "text_slider";


/*------------------------------
Ф-ии
-------------------------------*/
function write_to_db($method, $resItem = null){

    global $table;

    $response = [];

    $arr = [
        "maw"        => 1600
        ,"miw"       => 320
        ,"path"      => "../FILES/forTextSlider/"
        ,"inputName" => "photo"

    ];

    switch ($method):
        case "add":

            if(!$_POST["big_title"])
            {
                $response["error"][] = "ошибка: Не заполненны все данные. строка: ".__LINE__; return $response;
            }


            if(!$_FILES["photo"]["tmp_name"]){ $response["error"][] = "ошибка: не передан файл. строка: ".__LINE__; return $response; }
            $resAdd = photo_add_once($arr);
            if(!$resAdd["filename"])
            {
                $response["error"][] = "ошибка: при загрузке файла. строка: ".__LINE__; return $response;
            }


            $arr = [
                "big_title"     => proverka1($_POST["big_title"])
                ,"stranica"     => $_POST["stranica"]
                ,"photo"        => $resAdd["filename"]
                ,"small_title1" => addslashes(json_encode(proverka_recursive($_POST["small_title1"], 1)))
                ,"small_title2" => addslashes(json_encode(proverka_recursive($_POST["small_title2"], 1)))
            ];

            $redDb = db_insert($table, $arr);
            if($redDb["error"])
            {
                $response["error"][] = "ошибка: ".$redDb["error"]." строка: ".__LINE__; return $response;
            }
            else{
                $response["response"] = $redDb;
            }

            break;
        case "edit":

            global $ID;

            if(!$_POST["big_title"])
            {
                $response["error"][] = "ошибка: Не заполненны все данные. строка: ".__LINE__; return $response;
            }


            $resItem = db_row("SELECT ID, photo FROM ".$table." WHERE ID=".$ID)["item"];
            if($resItem){
                if($resItem["small_title1"]){$resItem["small_title1"] = json_decode($resItem["small_title1"], true);}
                if($resItem["small_title2"]){$resItem["small_title2"] = json_decode($resItem["small_title2"], true);}
            }



            if($_FILES["photo"]["tmp_name"]){
                $resAdd = photo_add_once($arr);
                if(!$resAdd["filename"])
                {
                    $response["error"][] = "ошибка: при загрузке файла. строка: ".__LINE__; return $response;
                }
                else{
                    $path = "../FILES/forTextSlider/";
                    if(file_exists($path."small/".$resItem["photo"])){ unlink($path."small/".$resItem["photo"]); }
                    if(file_exists($path."big/".$resItem["photo"])){ unlink($path."big/".$resItem["photo"]); }

                    $resItem["photo"] = $resAdd["filename"];
                }


            }


            $arr = [
                "big_title"     => proverka1($_POST["big_title"])
                ,"stranica"     => $_POST["stranica"]
                ,"photo"        => $resItem["photo"]
                ,"small_title1" => addslashes(json_encode(proverka_recursive($_POST["small_title1"], 1)))
                ,"small_title2" => addslashes(json_encode(proverka_recursive($_POST["small_title2"], 1)))
            ];

            $redDb = db_update($table, $arr, "ID = ".$ID);
            if($redDb["error"])
            {
                $response["error"][] = "ошибка: ".$redDb["error"]." строка: ".__LINE__; return $response;
            }
            else{
                $response["response"] = $redDb;
            }

            break;

    endswitch;



}


/*------------------------------
если был передан get ID
-------------------------------*/
if(is_numeric($_GET["ID"])):
    $resItem = db_row("SELECT * FROM ".$table." WHERE ID=".$ID)["item"];
    if($resItem){
        if($resItem["small_title1"]){$resItem["small_title1"] = json_decode($resItem["small_title1"], true);}
        if($resItem["small_title2"]){$resItem["small_title2"] = json_decode($resItem["small_title2"], true);}
    }
endif;



/*------------------------------
Если была передана форма
-------------------------------*/
if($_SERVER["REQUEST_METHOD"] == "POST"):
    $resWrite = write_to_db($_POST["method"]);
    if($resWrite["error"]){$errors[] = $resWrite["error"]; }
endif;


/*------------------------------
Вывод записи
-------------------------------*/
$Items = db_select("SELECT * FROM text_slider WHERE stranica ='".$stranica."'", true)["items"];

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <title></title>
    <link rel="shortcut icon" href=""/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="all" href="../css/adm/page_settings.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>


</head>

<body>

<div class="forError"><? if($errors){var_dump($errors);} ?></div>

<a href="<? echo $referer; ?>" class="return" title="Вернуться"><i class="material-icons">&#xE31B;</i></a>

<? if ($resItem) {
    $method = "edit";
    $tmp = ["active", null, null];
}else{
    $method = "add";
    $tmp = [null, "hidden" ,"required"];
} ?>
<a href="#" class="formTymbler <? echo $tmp[0] ?>"><span>Редактировать информацию</span></a>

<section class="infoForm textSlider" <? echo $tmp[1] ?>>
    <?


    ?>



    <form action="<? echo $thisPage."?stranica=".$stranica; ?>" method="post" enctype="multipart/form-data" name="myForm" target="_self">

        <input type="hidden" name="referer" value="<? echo $referer; ?>" />
        <input type="hidden" name="stranica" value="<? echo $stranica; ?>" />
        <input type="hidden" name="method" value="<? echo $method ?>" />
        <input type="hidden" name="ID" value="<? echo @$ID; ?>" />


        <? if($resItem["photo"]){ ?>
            <img src="<? echo "../FILES/forTextSlider/small/".$resItem["photo"]; ?>" align="right">
        <? } ?>

        <input type="file" name="photo" <? echo $tmp[2]; ?>><br><br>


        <input type="text" name="big_title" value="<? echo @$resItem["big_title"] ?>" placeholder="title"/><br><br>


        <div class="forInput">smallTitle1</div>
        <ul class="smallTitle1">
            <? if($resItem["small_title1"]){
            foreach ($resItem["small_title1"] as $item) {  ?>
            <li><input type="text" name="small_title1[]" value="<? echo $item ?>" >
                <a href="#" class="plus"><i class="material-icons">&#xE148;</i></a>
                <a href="#" class="minus"><i class="material-icons">&#xE15D;</i></a>
            </li>
            <? }}else{  ?>
            <li><input type="text" name="small_title1[]" >
                <a href="#" class="plus"><i class="material-icons">&#xE148;</i></a>
                <a href="#" class="minus"><i class="material-icons">&#xE15D;</i></a>
            </li>
            <? } ?>
        </ul>

        <div class="forInput">smallTitle2</div>
        <ul class="smallTitle2">
            <? if($resItem["small_title2"]){
                foreach ($resItem["small_title2"] as $item) {  ?>
                    <li><input type="text" name="small_title2[]" value="<? echo $item ?>" >
                        <a href="#" class="plus"><i class="material-icons">&#xE148;</i></a>
                        <a href="#" class="minus"><i class="material-icons">&#xE15D;</i></a>
                    </li>
                <? }}else{  ?>
                <li><input type="text" name="small_title2[]" >
                    <a href="#" class="plus"><i class="material-icons">&#xE148;</i></a>
                    <a href="#" class="minus"><i class="material-icons">&#xE15D;</i></a>
                </li>
            <? } ?>
        </ul>

        <input name="submit" type="submit" value="готово"/>
    </form>
</section>

<? if($Items): ?>
<section class="list">
    <ul class="sliderItems">
        <? foreach ($Items as $item) {
            if($item["small_title1"]){$item["small_title1"] = json_decode($item["small_title1"], true);}
            if($item["small_title2"]){$item["small_title2"] = json_decode($item["small_title2"], true);}
            ?>
        <li>
            <!--основная инфа-->
            <div class="row1">
                <div class="col1">
                    <img width="50" src="<? echo "../FILES/forTextSlider/small/".$item["photo"] ?>" >
                    <a class="title js-tymbler" href="#"><? echo $item["big_title"] ?></a>
                </div>
                <div class="col2">
                    <a href="<? echo $thisPage."?stranica=".$stranica."&ID=".$item["ID"]; ?>" class="edit">edit</a>
                    <a href="options.php?method_name=deleteTextslider&ID=<? echo $item["ID"] ?>" class="delete">delete</a>
                </div>
            </div>
            <!--инфо про smalltitles-->
            <div class="row2" hidden>

                <? if($item["small_title1"]){?>
                <ul class="col1">
                    <? foreach ($item["small_title1"] as $small_title) { ?>
                        <li><? echo $small_title ?></li>
                    <? } ?>
                </ul>
                <? } ?>


                <? if($item["small_title2"]){?>
                <ul class="col2">
                    <? foreach ($item["small_title2"] as $small_title) { ?>
                        <li><? echo $small_title ?></li>
                    <? } ?>
                </ul>
                <? } ?>

            </div>
        </li>
        <? } ?>
    </ul>
</section>
<? endif; ?>




<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../ckeditor/adapters/jquery.min.js"></script>
<script type="text/javascript" src="../js/adm/forEditor.js"></script>
<script type="text/javascript" src="../js/adm/page_settings.js"></script>
<script type="text/javascript" src="../js/adm/text_slider.js"></script>
</body>
</html>

