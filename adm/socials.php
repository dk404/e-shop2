<?php
require_once "../functions/DB.php";
require_once "../functions/proverki.php";
require_once "../functions/auth.php";
require_once "../functions/path.php";

$admin = (is_admin()['status'] == 3)? true : false;
if($admin !== true){header("Location: /e-shop2/");}

//Общие данные для скрипта
$this_page = path_withoutGet();
$referer = ($_POST["referer"])? $_POST["referer"] : $_SERVER["HTTP_REFERER"];


//Функции
function combine($array){
    $keys = array_column($array, "class");
    $values = array_column($array, "link");
    return array_combine($keys, $values);
}


/*------------------------------
Вывод записи
-------------------------------*/
$socials = db_select("SELECT * FROM socials")['items'];
if($socials){ $socials = combine($socials); }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <title></title>
    <link rel="shortcut icon" href=""/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="all" href="../css/adm_elements.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>


</head>

<body>

<div class="forError"><? if($errors){var_dump($errors);} ?></div>

<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../ckeditor/adapters/jquery.min.js"></script>
<script type="text/javascript" src="../js/adm/forEditor.js"></script>
<script type="text/javascript" src="../js/adm/page_settings.js"></script>
</body>
</html>
