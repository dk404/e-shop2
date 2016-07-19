<?php
require_once("../../functions/DB.php");
require_once("../../functions/auth.php");
require_once("../../functions/proverki.php");
require_once("../../functions/path.php");

$Admin = is_admin();
if(!$Admin){ exit("Нет прав доступа"); }


/*-------------------------------
Общие настройки
-------------------------------*/
if(!$_GET["stranica"]){
    header("Location: ../../index.php"); exit();
}
else{
    $stranica = proverka1($_GET["stranica"]);
}

$referer = ($_POST["referer"])? $_POST["referer"]: $_SERVER["HTTP_REFERER"];
$thisPage = path_withoutGet();
$table = "page_settings";


/*------------------------------
Ф-ии
-------------------------------*/
function write_to_db(){

    global $table;
    $response = [];

    $arr = [
        "stranica"      => proverka1($_POST["stranica"])
        ,"dopRows"          => [
            "address"             => proverka1($_POST["dopRows"]["address"])
            ,"phone"             => proverka1($_POST["dopRows"]["phone"])
            ,"email"             => proverka1($_POST["dopRows"]["email"])
            ,"hours"             => proverka1($_POST["dopRows"]["hours"])
            ,"map"             => ($_POST["dopRows"]["map"])
        ]

    ];

    $arr["dopRows"] = addslashes(json_encode($arr["dopRows"]));

    $response  = db_duplicate_update($table, [0 => $arr]);
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
$resItem = db_row("SELECT * FROM ".$table." WHERE stranica='".$stranica."'", true)["item"];
if($resItem["dopRows"]){$resItem["dopRows"] = json_decode($resItem["dopRows"], true);}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <title></title>
    <link rel="shortcut icon" href=""/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="all" href="../../css/adm/page_settings.css"/>

    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>


</head>

<body>

<div class="forError"><? if($errors){var_dump($errors);} ?></div>

<a href="<? echo $referer; ?>" class="return" title="Вернуться"><i class="material-icons">&#xE31B;</i></a>
<a href="#" class="addPage active">Редактировать информацию</a>


<section class="addForm" >
    <form action="<? echo $thisPage."?stranica=".$stranica ?>" method="post" enctype="multipart/form-data" name="myForm" target="_self">
        <input type="hidden" name="ID" value="<? echo @$resItem["ID"]; ?>" />
        <input type="hidden" name="referer" value="<? echo $referer; ?>" />
        <input type="hidden" name="stranica" value="<? echo $stranica; ?>"/>

        <input type="text" name="dopRows[address]" value="<? echo @$resItem["dopRows"]["address"]; ?>" placeholder="[dopRows][address]"/><br><br>
        <input type="text" name="dopRows[phone]" value="<? echo @$resItem["dopRows"]["phone"]; ?>" placeholder="[dopRows][phone]"/><br><br>
        <input type="text" name="dopRows[email]" value="<? echo @$resItem["dopRows"]["email"]; ?>" placeholder="[dopRows][email]"/><br><br>
        <input type="text" name="dopRows[hours]" value="<? echo @$resItem["dopRows"]["hours"]; ?>" placeholder="meta[[dopRows][hours]]"/><br><br>
        <textarea name="dopRows[map]" placeholder="карта (html)"><? echo @$resItem["dopRows"]["map"]; ?></textarea><br><br>

        <input name="submit" type="submit" value="готово"/>
    </form>
</section>




<script type="text/javascript" src="../../js/jquery.min.js"></script>

<script type="text/javascript" src="../../ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../../ckeditor/adapters/jquery.min.js"></script>


<script type="text/javascript" src="../../js/adm/page_settings.js"></script>
<script type="text/javascript" src="../../js/adm/forEditor.js"></script>
</body>
</html>

