<?
require_once("functions/DB.php");
require_once("functions/auth.php");
require_once("functions/path.php");
require_once("functions/product.php");

/*------------------------------
Общие настройки
-------------------------------*/
$stranica = "contact";
$this_page = path_withoutGet();
$me = is_auth();
$Admin = is_admin();


/*------------------------------
Если была отправленна форма
-------------------------------*/
if($_POST["method_name"] == "send-email" && !$_POST["email"] && proverka_email($_POST["ghtt"])){

    require_once("functions/Mail.php");
    $M = new email_Mail();

    //Формируем запрос
    $M->From($_POST["nickname"].";".$_POST["ghtt"]);
    $M->To("blabla@mail.com");
    $M->Subject($_POST["subject"]);
    $M->Body($_POST["text"]);
    $M->log_on(true);
    $M->Send();

    if(!$M->status_mail['status']){
        echo "<script>alert('".$M->status_mail['message']."')</script>";
    }else{
        echo "<script>alert('Спасибо, ваше сообщение отправленно')</script>";
    }

}




/*------------------------------
Достенем инфо про эту страницу
-------------------------------*/
$pageInfo = db_row("SELECT * FROM page_settings WHERE stranica='".$stranica."'")["item"];
if($pageInfo){
    $pageInfo["meta"] = json_decode($pageInfo["meta"], true);
    $pageInfo["dop_settings"] = json_decode(stripslashes($pageInfo["dop_settings"]), true);
}



?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopin A Ecommerce Category Flat Bootstrap Responsive Website Template | Contact :: w3layouts</title>
    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
    <!-- Custom Theme files -->
    <!--theme-style-->
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
    <!--//theme-style-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="<? echo $pageInfo["meta"]["keywords"]; ?>" />
    <meta name="description" content="<? echo $pageInfo["meta"]["desc"]; ?>" />

    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <!--theme-style-->
    <link href="css/style4.css" rel="stylesheet" type="text/css" media="all" />
    <!--//theme-style-->

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="css/admElements.css" />
    <link rel="stylesheet" href="css/dopElems.css" />





    <script src="js/jquery.min.js"></script>
    <!--- start-rate---->
    <script src="js/jstarbox.js"></script>
    <link rel="stylesheet" href="css/jstarbox.css" type="text/css" media="screen" charset="utf-8" />
    <script type="text/javascript">
        jQuery(function() {
            jQuery('.starbox').each(function() {
                var starbox = jQuery(this);
                starbox.starbox({
                    average: starbox.attr('data-start-value'),
                    changeable: starbox.hasClass('unchangeable') ? false : starbox.hasClass('clickonce') ? 'once' : true,
                    ghosting: starbox.hasClass('ghosting'),
                    autoUpdateAverage: starbox.hasClass('autoupdate'),
                    buttons: starbox.hasClass('smooth') ? false : starbox.attr('data-button-count') || 5,
                    stars: starbox.attr('data-star-count') || 5
                }).bind('starbox-value-changed', function(event, value) {
                    if(starbox.hasClass('random')) {
                        var val = Math.random();
                        starbox.next().text(' '+val);
                        return val;
                    }
                })
            });
        });
    </script>
    <!---//End-rate---->
</head>
<body>
<!--header-->
<? include_once("blocks/face/header.php"); ?>

<!--banner-->
<div class="banner-top">
    <div class="container">
        <h1>Contact</h1>
        <em></em>
        <h2><a href="index.php">Home</a><label>/</label>Contact</a></h2>
    </div>
</div>

<div class="contact">

    <div class="contact-form">
        <div class="container">
            <div class="col-md-6 contact-left">
                <h3><? echo $pageInfo["title"]; ?></h3>
                <? echo $pageInfo["text"]; ?>


                <? if($pageInfo["dop_settings"]): ?>
                <div class="address">

                    <? if($pageInfo["dop_settings"]["address"]){ ?>
                    <div class=" address-grid">
                        <i class="glyphicon glyphicon-map-marker"></i>
                        <div class="address1">
                            <h3>Address</h3>
                            <p><? echo $pageInfo["dop_settings"]["address"] ?></p>
                        </div>
                        <div class="clearfix"> </div>
                    </div>
                    <? } ?>

                    <? if($pageInfo["dop_settings"]["phone"]){ ?>
                    <div class=" address-grid ">
                        <i class="glyphicon glyphicon-phone"></i>
                        <div class="address1">
                            <h3>Our Phone:<h3>
                            <p><? echo $pageInfo["dop_settings"]["address"] ?></p>
                        </div>
                        <div class="clearfix"> </div>
                    </div>
                    <? } ?>


                    <? if($pageInfo["dop_settings"]["email"]){ ?>
                    <div class=" address-grid ">
                        <i class="glyphicon glyphicon-envelope"></i>
                        <div class="address1">
                            <h3>Email:</h3>
                            <p><a href="mailto:<? echo $pageInfo["dop_settings"]["email"] ?>"><? echo $pageInfo["dop_settings"]["email"] ?></a></p>
                        </div>
                        <div class="clearfix"> </div>
                    </div>
                    <? } ?>

                    <? if($pageInfo["dop_settings"]["hours"]){ ?>
                    <div class=" address-grid ">
                        <i class="glyphicon glyphicon-bell"></i>
                        <div class="address1">
                            <h3>Open Hours:</h3>
                            <p><? echo $pageInfo["dop_settings"]["hours"] ?></p>
                        </div>
                        <div class="clearfix"> </div>
                    </div>
                    <? } ?>

                </div>
                <? endif; ?>
            </div>
            <div class="col-md-6 contact-top">
                <h3>Want to work with me?</h3>
                <form action="<? echo $this_page ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="method_name" value="send-email">
                    <div>
                        <span>Your Name </span>
                        <input type="text" name="nickname" value="" >
                    </div>
                    <div>
                        <span>Your Email </span>
                        <input type="text" name="ghtt" value="" >
                    </div>
                    <div>
                        <span>Subject</span>
                        <input type="text" name="subject" value="" >
                    </div>
                    <div>
                        <span>Your Message</span>
                        <textarea name="text"> </textarea>
                    </div>
                    <input type="text" name="email" class="i-h">
                    <label class="hvr-skew-backward">
                        <input type="submit" value="Send" >
                    </label>
                </form>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>

    <? if($pageInfo["dop_settings"]["map"]){ ?>
    <div class="map">
        <? echo $pageInfo["dop_settings"]["map"] ?>
    </div>
    <? } ?>
</div>

<!--//contact-->
<!--brand-->
<div class="container">
    <div class="brand">
        <div class="col-md-3 brand-grid">
            <img src="images/ic.png" class="img-responsive" alt="">
        </div>
        <div class="col-md-3 brand-grid">
            <img src="images/ic1.png" class="img-responsive" alt="">
        </div>
        <div class="col-md-3 brand-grid">
            <img src="images/ic2.png" class="img-responsive" alt="">
        </div>
        <div class="col-md-3 brand-grid">
            <img src="images/ic3.png" class="img-responsive" alt="">
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!--//brand-->
</div>

</div>
<!--//content-->
<!--//footer-->
<div class="footer">
    <div class="footer-middle">
        <div class="container">
            <div class="col-md-3 footer-middle-in">
                <a href="index.php"><img src="images/log.png" alt=""></a>
                <p>Suspendisse sed accumsan risus. Curabitur rhoncus, elit vel tincidunt elementum, nunc urna tristique nisi, in interdum libero magna tristique ante. adipiscing varius. Vestibulum dolor lorem.</p>
            </div>

            <div class="col-md-3 footer-middle-in">
                <h6>Information</h6>
                <ul class=" in">
                    <li><a href="404.html">About</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                    <li><a href="#">Returns</a></li>
                    <li><a href="contact.php">Site Map</a></li>
                </ul>
                <ul class="in in1">
                    <li><a href="#">Order History</a></li>
                    <li><a href="wishlist.html">Wish List</a></li>
                    <li><a href="login.php">Login</a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="col-md-3 footer-middle-in">
                <h6>Tags</h6>
                <ul class="tag-in">
                    <li><a href="#">Lorem</a></li>
                    <li><a href="#">Sed</a></li>
                    <li><a href="#">Ipsum</a></li>
                    <li><a href="#">Contrary</a></li>
                    <li><a href="#">Chunk</a></li>
                    <li><a href="#">Amet</a></li>
                    <li><a href="#">Omnis</a></li>
                </ul>
            </div>
            <div class="col-md-3 footer-middle-in">
                <h6>Newsletter</h6>
                <span>Sign up for News Letter</span>
                <form>
                    <input type="text" value="Enter your E-mail" onfocus="this.value='';" onblur="if (this.value == '') {this.value ='Enter your E-mail';}">
                    <input type="submit" value="Subscribe">
                </form>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <ul class="footer-bottom-top">
                <li><a href="#"><img src="images/f1.png" class="img-responsive" alt=""></a></li>
                <li><a href="#"><img src="images/f2.png" class="img-responsive" alt=""></a></li>
                <li><a href="#"><img src="images/f3.png" class="img-responsive" alt=""></a></li>
            </ul>
            <p class="footer-class">&copy; 2016 Shopin. All Rights Reserved | Design by  <a href="http://w3layouts.com/" target="_blank">W3layouts</a> </p>
            <div class="clearfix"> </div>
        </div>
    </div>
</div>
<!--//footer-->
<? if(is_admin()): ?>
    <!--Админ панель-->
    <section id="admBar">
        <a href="#" class="tymbler"><i class="material-icons">&#xE23E;</i></a>
        <ul class="listBtns">

            <? /*Общие кнопки*/ include_once("blocks/face/forAdmBar.php"); ?>

            <!--            <li>
                <a href="adm/categories.php">Категории</a>
            </li>
            <li>
                <a href="adm/forSlider.php?stranica=<?/* echo $stranica */?>">Большой слайдер</a>
            </li>
            <li>
                <a href="adm/products.php">Товары</a>
            </li>-->
        </ul>


    </section>

<? endif; ?>




<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

<script src="js/simpleCart.min.js"> </script>
<!-- slide -->
<script src="js/bootstrap.min.js"></script>
<script src="js/face/admBar.js" type="text/javascript"></script>


</body>
</html>