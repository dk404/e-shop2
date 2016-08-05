<?php
require_once("../functions/DB.php");
require_once("../functions/auth.php");
require_once("../functions/proverki.php");
require_once("../functions/saveImg.php");
require_once("../functions/path.php");

$Admin = is_admin();
if(!$Admin){ exit("Нет прав доступа"); }

$referer     = ($_POST["referer"])? $_POST["referer"]: $_SERVER["HTTP_REFERER"];
$thisPage    = path_withoutGet();

/*------------------------------
Ф-ии
-------------------------------*/
function write_to_db($method){

    $response   = [];
    $title      = proverka1($_POST["title"]);
    $table      = "brands";

    switch ($_POST["method_name"]):
        case "add":

            if(!$_FILES["photo"]["name"]){
                $response["error"] = "Ошибка: файл изображения не выбран";
                return $response;
            }

                $arr = [
                    "maw"       => 1000
                   ,"miw"       => 320
                   ,"path"      => "../FILES/brands/"
                   ,"inputName" => "photo"

                ];

                $resAdd = photo_add_once($arr);
                if($resAdd["error"]){ return $resAdd; }

                $arr    = ["title" => $title, "photo" => $resAdd["filename"]];
                $resDb  = db_insert($table, $arr);



            break;
        case "edit":

            if(!is_numeric($_POST["ID"])){
                $response["error"] = "Ошибка: не верные параметры ID";
                return $response;
            }

            //сделаем выборку этой записи
                $resItem = db_row("SELECT * FROM ".$table. " WHERE ID =".$_POST["ID"])["item"];
                if(!$resItem){
                    $response["error"] = "Ошибка: Записи с данным id не существует";
                    return $response;
                }


            if($_FILES["photo"]["name"]){

                $arr = [
                    "maw"       => 1000
                   ,"miw"       => 320
                   ,"path"      => "../FILES/brands/"
                   ,"inputName" => "photo"

                ];

                $resAdd = photo_add_once($arr);
                if($resAdd["error"]){ return $resAdd; }

                $photoName = $resAdd["filename"];

                //Удалим старую фотку
                if($resItem["photo"])
                {
                    $path = path_clear_path()."FILES/brands/";
                    if(file_exists($path."big/".$resItem["photo"])){ unlink($path."big/".$resItem["photo"]); }
                    if(file_exists($path."small/".$resItem["photo"])){ unlink($path."small/".$resItem["photo"]); }
                }


            }else{
                $photoName = $resItem["photo"];

            }


            //пишем в базу
                $arr    = ["title" => $title, "photo" => $photoName];
                $resDb  = db_update($table, $arr, "ID = ".$_POST["ID"]);

            break;

    endswitch;

    //response
        return $resDb;

}

/*------------------------------
Если была передана форма
-------------------------------*/
if(isset($_POST["method_name"]) && $_POST["title"]):
    $arr = ["title" => proverka1($_POST["title"])];
    $resWrite = write_to_db($_POST["method_name"]);

    if($resWrite["error"]){
        $errors[] = $resWrite["error"];
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
$Items = db_select("SELECT * FROM bigSlider WHERE stranica='".$forStranica."' ORDER BY nomer", true)["items"];


?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8"/>
    <title>Работа со брэндами</title>
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
    <form action="<? echo $thisPage ?>" method="post" enctype="multipart/form-data" name="myForm" target="_self">
        <input type="hidden" name="method_name" value="add" />
        <input type="hidden" name="ID" value="<? echo @$resItem["ID"]; ?>" />
        <input type="hidden" name="referer" value="<? echo $referer; ?>" />

        <input type="text" name="title" value="<? echo @$resItem["title"]; ?>" placeholder="title"/><br><br>
        <input type="file" name="photo"><br><br>
        <input name="submit" type="submit" value="готово"/>
    </form>
</section>


<? if($Items): ?>
    <ul class="listItems sort_cont" data-js-sort="bigSlider">
        <? foreach ($Items as $item) {  ?>
            <li id="<? echo "nomer_".$item["ID"]; ?>">
                <a class="js-delItem" href="options.php?method_name=deleteBigSlider&ID=<? echo $item["ID"]; ?>" style="background-image:url('../FILES/forSlider/small/<? echo $item["photo"] ?>');"><span class="bg"></span><i class="material-icons">&#xE14C;</i></a>
            </li>
        <? } ?>
    </ul>
<? endif; ?>



<script type="text/javascript" src="../js/jquery-2.2.4.min.js"></script>
<script type="text/javascript" src="../js/sort/jquery-ui.min.js"></script>
<script type="text/javascript" src="../js/sort/for_sort.js"></script>
<script type="text/javascript" src="../js/adm/page_settings.min.js"></script>
</body>
</html>

