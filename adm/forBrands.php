<?php
require_once("../functions/DB.php");
require_once("../functions/auth.php");
require_once("../functions/proverki.php");
require_once("../functions/saveImg.php");
require_once("../functions/path.php");

$Admin = is_admin();
if(!$Admin){ exit("Нет прав доступа"); }

$referer     = ($_POST["referer"])? $_POST["referer"]: $_SERVER["HTTP_REFERER"];
$forStranica = ($_POST["stranica"])? $_POST["stranica"] : $_GET["stranica"];
$thisPage    = path_withoutGet();

/*------------------------------
Ф-ии
-------------------------------*/

/*------------------------------
Если была передана форма
-------------------------------*/
if(isset($_POST["submit"])):

    $arr = [
        "maw"       => 1000
       ,"miw"       => 320
       ,"path"      => "../FILES/forBrands/"
       ,"inputName" => "img"

    ];

    $resAdd = photo_add_once($arr);

    if($resAdd["filename"])
    {
        $arr = [
             "img" => $resAdd["filename"]
            ,"href" => $_POST["href"]
        ];


        //пишем в базу
        $resDb = db_insert("brands", $arr);

    }





endif;



/*------------------------------
Если был передан GET
-------------------------------*/
if($_GET["method_name"] == "delete" && is_numeric($_GET["ID"])):
    $resDb = db_delete("page_settings", "ID =".$_GET["ID"]);
    if($resDb["error"]){$errors[] = $resDb["error"]; }
endif;

if($_GET["method_name"] == "edit" && is_numeric($_GET["ID"])):
    $resItem = db_row("SELECT * FROM page_settings WHERE ID=".$_GET["ID"])["item"];
    if($resItem){$resItem["meta"] = json_decode($resItem["meta"], true);}
endif;


/*------------------------------
Вывод записей
-------------------------------*/
$Items = db_select("SELECT * FROM brands ORDER BY nomer", true)["items"];


?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <title>Работа с брендами</title>
    <link rel="shortcut icon" href=""/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../js/sort/jquery-ui.min.css" rel="stylesheet" >
    <link href="../css/adm/page_settings.css" rel="stylesheet" >
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>


</head>

<body>

<div class="forError"><? if($errors){var_dump($errors);} ?></div>

<a href="<? echo $referer; ?>" class="return" title="Вернуться"><i class="material-icons">&#xE31B;</i></a>
<!--<a href="#" class="addPage">Добавить слайдер</a>-->


<section id="bigSlider">
    <section class="addItems">
        <h3>Добавить бренды</h3>
        <form action="" method="post" enctype="multipart/form-data" name="myForm" target="_self">
            <input type="hidden" name="referer" value="<? echo $referer; ?>"/>
            <input type="hidden" name="stranica" value="<? echo $forStranica; ?>"/>
            <input type="file" name="img" />
            <input name="submit" type="submit" value="Загрузить"/><br>
            <p>Href</p>
            <input type="text" name="href">
        </form>
    </section>

    <? if($Items): ?>
    <ul class="listItems sort_cont" data-js-sort="brands">
        <? foreach ($Items as $item) {  ?>
        <li id="<? echo "nomer_".$item["ID"]; ?>">
            <a class="js-delItem" href="options.php?method_name=deleteBrand&ID=<? echo $item["ID"]; ?>" style="background-image:url('../FILES/forBrands/small/<? echo $item["img"] ?>');"><span class="bg"></span><i class="material-icons">&#xE14C;</i></a>
        </li>
        <? } ?>
    </ul>
    <? endif; ?>
</section>





<script src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/sort/jquery-ui.min.js"></script>
<script type="text/javascript" src="../js/sort/for_sort.js"></script>
<script type="text/javascript" src="../js/adm/page_settings.js"></script>
</body>
</html>

