<?php
require_once("../functions/DB.php");
require_once("../functions/auth.php");
require_once("../functions/path.php");
require_once("../functions/proverki.php");
require_once("../functions/saveImg.php");

$Admin = is_admin();
if(!$Admin){ exit("Нет прав доступа"); }

$referer = ($_POST["referer"])? $_POST["referer"]: $_SERVER["HTTP_REFERER"];
if(!$_GET["stranica"]){ header("Location: ".$referer); exit(); }else{ $stranica = proverka1($_GET["stranica"]);  }
$ID = (is_numeric($_GET["ID"]))? $_GET["ID"] : false;
$thisPage = path_withoutGet();

/*------------------------------
Ф-ии
-------------------------------*/
function write_to_db($method){

    $table = "text_slider";
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
                ,"small_title1" => json_encode(proverka_recursive($_POST["small_title1"], 1))
                ,"small_title2" => json_encode(proverka_recursive($_POST["small_title2"], 1))
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

    endswitch;



}



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

<? if ($ID) {
    $method = "edit";
    $tmp = ["active", null];
}else{
    $method = "add";
    $tmp = [null, "hidden"];
} ?>
<a href="#" class="formTymbler <? echo $tmp[0] ?>"><span>Редактировать информацию</span></a>

<section class="infoForm textSlider" <? echo $tmp[1] ?>>
    <?


    ?>



    <form action="<? echo $thisPage."?stranica=".$stranica; ?>" method="post" enctype="multipart/form-data" name="myForm" target="_self">
        <input type="hidden" name="referer" value="<? echo $referer; ?>" />
        <input type="hidden" name="stranica" value="<? echo $stranica; ?>" />
        <input type="hidden" name="method" value="<? echo $method ?>" />
        <input type="hidden" name="ID" value="" />

        <img src="" align="right">

        <input type="file" name="photo" ><br><br>
        <input type="text" name="big_title" value="<? echo @$Items["big_title"]; ?>" placeholder="title"/><br><br>

        <div class="forInput">smallTitle1</div>
        <ul class="smallTitle1">
            <li><input type="text" name="small_title1[]" >
                <a href="#" class="plus"><i class="material-icons">&#xE148;</i></a>
                <a href="#" class="minus"><i class="material-icons">&#xE15D;</i></a>
            </li>
            <li><input type="text" name="small_title1[]" >
                <a href="#" class="plus"><i class="material-icons">&#xE148;</i></a>
                <a href="#" class="minus"><i class="material-icons">&#xE15D;</i></a>
            </li>
        </ul>

        <div class="forInput">smallTitle2</div>
        <ul class="smallTitle2">
            <li><input type="text" name="small_title2[]" >
                <a href="#" class="plus"><i class="material-icons">&#xE148;</i></a>
                <a href="#" class="minus"><i class="material-icons">&#xE15D;</i></a>
            </li>
            <li><input type="text" name="small_title2[]" >
                <a href="#" class="plus"><i class="material-icons">&#xE148;</i></a>
                <a href="#" class="minus"><i class="material-icons">&#xE15D;</i></a>
            </li>
            <li><input type="text" name="small_title2[]" >
                <a href="#" class="plus"><i class="material-icons">&#xE148;</i></a>
                <a href="#" class="minus"><i class="material-icons">&#xE15D;</i></a>
            </li>
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
                    <a class="title" href="#"><? echo $item["big_title"] ?></a>
                </div>
                <div class="col2">
                    <a href="#" class="edit">edit</a>
                    <a href="#" class="delete">delete</a>
                </div>
            </div>
            <!--инфо про smalltitles-->
            <div class="row2 active" >

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
</body>
</html>

