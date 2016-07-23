<?php
require_once("../functions/DB.php");
require_once("../functions/auth.php");
require_once("../functions/path.php");
require_once("../functions/proverki.php");

$Admin = is_admin();
if(!$Admin){ exit("Нет прав доступа"); }

$referer        = ($_POST["referer"])? $_POST["referer"]: $_SERVER["HTTP_REFERER"];
if(is_numeric($_GET["ID"]) || is_numeric($_POST["ID"]))
{

    if($_POST["ID"]){ $ID   = $_POST["ID"]; }
    if($_GET["ID"]) { $ID   = $_GET["ID"]; }

}
else{
    $ID = false;
}


$thisPage       = path_withoutGet();
$table          = "groups";


/*------------------------------
Ф-ии
-------------------------------*/
function write_to_db($method, $resItem = null){

    global $table;

    $response = [];
    $arr = [ "title"  => proverka1($_POST["title"])];

    switch ($method):
        case "add":


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


            $resItem = db_row("SELECT * FROM ".$table." WHERE ID=".$ID);
            if($resItem["error"]){
                $response["error"][] = "ошибка: ".$resItem["error"]." строка: ".__LINE__; return $response;
            }

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


    return $response;
}


/*------------------------------
если был передан get ID
-------------------------------*/
if(is_numeric($_GET["ID"])):
    $resItem = db_row("SELECT * FROM ".$table." WHERE ID=".$ID)["item"];
    if($resItem){
        if($resItem["href"]){$resItem["href"] = urldecode($resItem["href"]);}
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
$Items = db_select("SELECT * FROM ".$table." ORDER BY nomer ASC", true)["items"];

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <title></title>
    <link rel="shortcut icon" href=""/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" type="text/css" media="all" href="../js/sort/jquery-ui.min.css"/>
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

    <form action="<? echo $thisPage ?>" method="post" enctype="multipart/form-data" name="myForm" target="_self">

        <input type="hidden" name="referer" value="<? echo $referer; ?>" />
        <input type="hidden" name="method" value="<? echo $method ?>" />
        <input type="hidden" name="ID" value="<? echo @$ID; ?>" />

        <input type="text" name="title" value="<? echo @$resItem["title"] ?>" placeholder="title"/><br><br>
        <input name="submit" type="submit" value="готово"/>
    </form>
</section>

<? if($Items): ?>
<section class="list">
    <ul class="sliderItems sort_cont" data-js-sort="<? echo $table ?>">
        <? foreach ($Items as $item) {
            if($item["href"]){$item["href"] = urldecode($item["href"]);}
            ?>
        <li id="<? echo "nomer_".$item["ID"]; ?>">
            <!--основная инфа-->
            <div class="row1">
                <div class="col1">
                    <a class="title" target="_blank" href="category.php?group_id=<? echo $item["ID"] ?>"><? echo $item["title"] ?></a>
                </div>
                <div class="col2">
                    <a href="<? echo $thisPage."?ID=".$item["ID"]; ?>" class="edit">edit</a>
                    <a href="options.php?method_name=deleteItem&table=<? echo $table ?>&ID=<? echo $item["ID"] ?>" class="delete">delete</a>
                </div>
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

<script type="text/javascript" src="../js/sort/jquery-ui.min.js"></script>
<script type="text/javascript" src="../js/sort/for_sort.js"></script>


<script type="text/javascript" src="../js/adm/page_settings.js"></script>
<script type="text/javascript" src="../js/adm/text_slider.js"></script>
</body>
</html>

