<?php
require_once("../functions/DB.php");
require_once("../functions/auth.php");
require_once("../functions/path.php");
require_once("../functions/proverki.php");

$Admin = is_admin();
if(!$Admin){ exit("Нет прав доступа"); }

if(!$_GET["stranica"]){exit("Ошибка: неверные параметры 'stranica'");}else{ $stranica = proverka1($_GET["stranica"]); }
$referer    = ($_POST["referer"])? $_POST["referer"]: $_SERVER["HTTP_REFERER"];
$thisPage   = path_withoutGet();



/*------------------------------
Ф-ии
-------------------------------*/
function write_to_db($method){

    $table = "page_settings";
    $response = [];

    $arr = [
        "stranica"      => proverka1($_POST["stranica"])
        ,"title"         => proverka1($_POST["title"])
        ,"btn_title"     => proverka1($_POST["btn_title"])
        ,"text"          => proverka1($_POST["text"])
        ,"meta"          => [
            "title"             => proverka1($_POST["meta"]["title"])
            ,"desc"             => proverka1($_POST["meta"]["desc"])
            ,"keywords"         => proverka1($_POST["meta"]["keywords"])
        ]

    ];

    $arr["meta"] = addslashes(json_encode($arr["meta"]));
    $response    = db_duplicate_update($table, [0 => $arr]);

    return $response;
}


/*------------------------------
Если была передана форма
-------------------------------*/
if(isset($_POST["method_name"])):
    $resWrite = write_to_db($_POST["method_name"]);
    if($resWrite["error"]){$errors[] = $resWrite["error"]; }
endif;


/*------------------------------
Вывод записи
-------------------------------*/
$Item = db_row("SELECT * FROM page_settings WHERE stranica = '".proverka1($_GET["stranica"])."'", true)["item"];
if($Item["meta"]){$Item["meta"] = json_decode($Item["meta"], true);}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <title>Редактировать страницу</title>
    <link rel="shortcut icon" href=""/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="all" href="../css/adm/page_settings.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>


</head>

<body>

<div class="forError"><? if($errors){var_dump($errors);} ?></div>

<a href="<? echo $referer; ?>" class="return" title="Вернуться"><i class="material-icons">&#xE31B;</i></a>
<a href="#" class="formTymbler mt25 active"><span>Редактировать инфо</span></a>


<section class="addForm" >
    <div class="wr">
        <form action="<? echo $thisPage."?stranica=".$stranica ?>" method="post" enctype="multipart/form-data" name="myForm" target="_self">
            <? $method = ($Item) ? "edit" : "add"; ?>
            <input type="hidden" name="method_name" value="edit"/>
            <input type="hidden" name="referer" value="<? echo $referer; ?>"/>
            <input type="hidden" name="stranica" value="<? echo $stranica; ?>" />

            <input type="text" name="title" value="<? echo $Item["title"]; ?>" placeholder="title"/><br><br>
            <input type="text" name="btn_title" value="<? echo $Item["btn_title"]; ?>" placeholder="btn_title" required/><br><br>
            <input type="text" name="meta[title]" value="<? echo @$Item["meta"]["title"]; ?>" placeholder="meta[title]"/><br><br>
            <input type="text" name="meta[desc]" value="<? echo @$Item["meta"]["desc"]; ?>" placeholder="meta[desc]"/><br><br>
            <input type="text" name="meta[keywords]" value="<? echo @$Item["meta"]["keywords"]; ?>" placeholder="meta[keywords]"/><br><br>
            <textarea name="text" class="js-ckeditor"><? echo $Item["text"]; ?></textarea><br><br>

            <input name="submit" type="submit" value="готово"/>
        </form>
    </div></section>





<script type="text/javascript" src="../js/jquery.min.js"></script>

<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../ckeditor/adapters/jquery.min.js"></script>
<script type="text/javascript" src="../js/adm/forEditor.js"></script>

<script type="text/javascript" src="../js/adm/page_settings.js"></script>
</body>
</html>

