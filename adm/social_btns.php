<?php
require_once("../functions/DB.php");
require_once("../functions/auth.php");
require_once("../functions/path.php");
require_once("../functions/proverki.php");

$Admin = is_admin();
if (!$Admin) {
    exit("Нет прав доступа");
}

$table      = "socials";
$referer    = ($_POST["referer"]) ? $_POST["referer"] : $_SERVER["HTTP_REFERER"];
$thisPage   = path_withoutGet();

/*------------------------------
Ф-ии
-------------------------------*/
function goodKeys($arr, $colName){
    $cols = array_column($arr, $colName);
    $res  = array_combine($cols, $arr);
    return $res;
}

/*------------------------------
Если была передана форма
-------------------------------*/
if (isset($_POST["method_name"])):

    switch ($_POST["method_name"]):
        case "add":

            $arr = [];

            foreach ($_POST["item_type"] as $item => $val) {
                $arr[] = [
                    "item_type" => proverka1($item)
                    ,"item_val" => (!empty($val))? urlencode(proverka1($val)) : null
                ];
            }

            $resDb = db_duplicate_update($table, $arr);
            $forError = ($resDb["error"])? $resDb["error"] : null;

        break;

    endswitch;


endif;


/*------------------------------
Если был передан GET
-------------------------------*/
//if($_GET["method_name"] == "delete" && is_numeric($_GET["ID"])):
//    $resDb = db_delete($table, "ID =".$_GET["ID"]);
//    if($resDb["error"]){$errors[] = $resDb["error"]; }
//endif;
//
//if($_GET["method_name"] == "edit" && is_numeric($_GET["ID"])):
//    $resItem = db_row("SELECT * FROM ".$table." WHERE ID=".$_GET["ID"])["item"];
//    if($resItem){$resItem["meta"] = json_decode($resItem["meta"], true);}
//endif;


/*------------------------------
Вывод записей
-------------------------------*/
$Items = db_select("SELECT * FROM ".$table." ORDER BY nomer", true)["items"];
if($Items){$Items = goodKeys($Items, "item_type");}


/*------------------------------
Дополнительные данные
-------------------------------*/
$socialTypes = ["tw" => "Twitter", "fb" => "Facebook", "be" => "Behance", "in" => "Linkedin"];

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

<div class="forError"><? if ($forError) {
        var_dump($forError);
    } ?></div>

<a href="<? echo $referer; ?>" class="return" title="Вернуться"><i class="material-icons">&#xE31B;</i></a>
<a href="#" class="formTymbler mt25"><span>Добавить элемент</span></a>

<? $tmp = (!$resItem) ? "hidden" : null; ?>
<section class="addForm mt15" <? echo $tmp; ?>>
    <div class="wr">
        <form action="<? echo $thisPage ?>" method="post" enctype="multipart/form-data" name="myForm" target="_self">
            <? $method = (@$resItem) ? "edit" : "add"; ?>
            <input type="hidden" name="method_name" value="<? echo $method; ?>"/>
            <input type="hidden" name="ID" value="<? echo @$resItem["ID"]; ?>"/>

            <? foreach ($socialTypes as $item => $val) {
               $tmp = ($Items[$item]["item_val"])? urldecode($Items[$item]["item_val"]) : null;
            ?>
                <div class="row mb10">
                    <div class="title"><? echo $val ?></div>
                    <input type="text" name="item_type[<? echo $item ?>]" value="<? echo $tmp; ?>">
                </div>

            <? } ?>

            <input type="submit" value="готово"/>
        </form>
    </div>
</section>


<? if ($Items): ?>
    <section class="list mt50">
        <div class="wr">
            <ul class="pageItems sort_cont" data-js-sort="<? echo $table; ?>">
                <? foreach ($Items as $item) {
                    if(empty($item["item_val"])){continue;}
                    $href = urldecode($item["item_val"]);
                    $tmp  = substr($href, 0, 30);
                    ?>
                    <li id="<? echo "nomer_".$item["ID"]; ?>">
                        <a href="<? echo $href ?>" class="pageItem" target="_blank"><? echo "(". $item["item_type"] .") ".$tmp; ?></a>
                    </li>
                <? } ?>
            </ul>
        </div>
    </section>
<? endif; ?>


<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/sort/jquery-ui.min.js"></script>
<script type="text/javascript" src="../js/sort/for_sort.js"></script>
<script type="text/javascript" src="../js/adm/page_settings.js"></script>
</body>
</html>

