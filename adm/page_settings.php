<?php
require_once("../functions/DB.php");
require_once("../functions/auth.php");
require_once("../functions/proverki.php");
require_once("../functions/path.php");

/*------------------------------
Основные настройки
-------------------------------*/
$Admin = is_admin();
if(!$Admin){ exit("Нет прав доступа"); }
if(!$_GET["stranica"]){ header("Location: ../index.php"); }

$referer    = ($_POST["referer"])? $_POST["referer"]: $_SERVER["HTTP_REFERER"];
$stranica   = $_GET["stranica"];
$table      = "page_settings";


/*------------------------------
Ф-ии
-------------------------------*/
function write_to_db(){

    global $table;

    $arr = [
        "stranica"      => proverka1($_POST["stranica"])
        ,"title"         => proverka1($_POST["title"])
        ,"btn_title"     => proverka1($_POST["btn_title"])
        ,"text"          => proverka2($_POST["text"])
        ,"meta"          => [
            "title"             => proverka1($_POST["meta"]["title"])
            ,"desc"             => proverka1($_POST["meta"]["desc"])
            ,"keywords"         => proverka1($_POST["meta"]["keywords"])
        ]

    ];

    $arr["meta"] = addslashes(json_encode($arr["meta"]));
    $response  = db_duplicate_update($table, [0 => $arr]);

    return $response;
}


/*------------------------------
Если была передана форма
-------------------------------*/
if(isset($_POST["method_name"])):
    $resWrite = write_to_db();
    if($resWrite["error"]){$errors[] = $resWrite["error"]; }
endif;





/*------------------------------
Вывод записи
-------------------------------*/
$resItem = db_row("SELECT * FROM ".$table." WHERE stranica='".$stranica."'")["item"];
if($resItem){$resItem["meta"] = json_decode($resItem["meta"], true);}


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
<a href="#" class="addPage active"><span>Редактировать инфо</span></a>

<section class="addForm">
    <form action="<? echo path_withoutGet()."?stranica=".$stranica ?>" method="post" enctype="multipart/form-data" name="myForm" target="_self">
        <input type="hidden" name="method_name" value="edit" />
        <input type="hidden" name="ID" value="<? echo @$resItem["ID"]; ?>" />
        <input type="hidden" name="referer" value="<? echo $referer; ?>" />
        <input type="hidden" name="stranica" value="<? echo $stranica; ?>" >

        <input type="text" name="title" value="<? echo @$resItem["title"]; ?>" placeholder="title"/><br><br>
        <input type="text" name="btn_title" value="<? echo @$resItem["btn_title"]; ?>" placeholder="btn_title"/><br><br>
        <input type="text" name="meta[title]" value="<? echo @$resItem["meta"]["title"]; ?>" placeholder="meta[title]"/><br><br>
        <input type="text" name="meta[desc]" value="<? echo @$resItem["meta"]["desc"]; ?>" placeholder="meta[desc]"/><br><br>
        <input type="text" name="meta[keywords]" value="<? echo @$resItem["meta"]["keywords"]; ?>" placeholder="meta[keywords]"/><br><br>
        <textarea name="text" class="js-ckeditor"><? echo @$resItem["text"]; ?></textarea><br><br>

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

