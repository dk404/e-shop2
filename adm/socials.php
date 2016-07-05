<?php
require_once("../functions/DB.php");
require_once("../functions/auth.php");
require_once("../functions/path.php");
require_once("../functions/proverki.php");

$Admin = is_admin();
if(!$Admin){ exit("Нет прав доступа"); }

$referer = ($_POST["referer"])? $_POST["referer"]: $_SERVER["HTTP_REFERER"];
$thisPage = path_withoutGet();
$socArr = ["tw" => "twitter", "fb" => "facebook", "be" => "behance", "in" => "linkedin"];



/*------------------------------
Ф-ии
-------------------------------*/
function combine($arr){
    $keys = array_column($arr, "type");
    $val  = array_column($arr, "link");
    return array_combine($keys, $val);
}


function write_to_db(){

    $table      = "socials";
    $arr        = [];
    $response   = [];


    foreach ($_POST["social"] as $item => $value) {
        $arr[] = [
            "type"  => $item
            ,"link" => urlencode($value)
        ];
    }


    $response  = db_duplicate_update($table, $arr);
    return $response;
}


/*------------------------------
Если была передана форма
-------------------------------*/
if($_SERVER["REQUEST_METHOD"] == "POST"):
    $resWrite = write_to_db();
    if($resWrite["error"]){$errors[] = $resWrite["error"]; }
endif;



/*------------------------------
Вывод записи
-------------------------------*/
$Items = db_select("SELECT * FROM socials", true)["items"];
if($Items){ $Items = combine($Items); }

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
<a href="#" class="formTymbler"><span>Редактировать информацию</span></a>

<section class="infoForm" >
    <form action="<? echo $thisPage; ?>" method="post" enctype="multipart/form-data" name="myForm" target="_self">
        <input type="hidden" name="referer" value="<? echo $referer; ?>" />
        <? foreach ($socArr as $item => $value) { ?>
            <div class="forInput"><? echo $value; ?></div>
            <input type="text" name="social[<? echo $item ?>]" value="<? echo @urldecode($Items[$item]); ?>" ><br><br>
        <? } ?>
        <input name="submit" type="submit" value="готово"/>
    </form>
</section>




<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../ckeditor/adapters/jquery.min.js"></script>
<script type="text/javascript" src="../js/adm/forEditor.js"></script>
<script type="text/javascript" src="../js/adm/page_settings.js"></script>
</body>
</html>

