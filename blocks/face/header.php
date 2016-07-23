<?
/*------------------------------
Соц кнопки
-------------------------------*/
$socBtns = db_select("SELECT * FROM socials")["items"];

/*------------------------------
кнопки основного меню
-------------------------------*/
$mainMenuBtns = ["home", "women", "men", "sale", "about", "short_code", "contact"];
$mainMenuInfo = db_select("SELECT btn_title, stranica FROM page_settings WHERE stranica IN ('".implode("','", $mainMenuBtns)."')")["items"];


?>


<div class="header">
    <div class="container">
        <div class="head">
            <div class=" logo">
                <a href="index.php"><img src="images/logo.png" alt=""></a>
            </div>
        </div>
    </div>
    <div class="header-top">
        <div class="container">
            <div class="col-sm-5 col-md-offset-2  header-login">
                <ul >
                    <? if($me){ ?>
                        <li><a href="login.php?logout=1">Logout</a></li>
                    <? }else{ ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <? } ?>
                    <li><a href="checkout.html">Checkout</a></li>
                </ul>
            </div>

            <div class="col-sm-5 header-social">
                <ul >
                    <? if($socBtns){ foreach ($socBtns as $socBtn) {
                        if(!$socBtn["link"]){continue;}
                        ?>
                        <li><a href="<? echo @urldecode($socBtn["link"]) ?>"><i class="<? echo $socBtn["type"] ?>"></i></a></li>
                    <? }
                    } ?>
                    <li><a href="#"><i class="ic4"></i></a></li>
                </ul>

            </div>
            <div class="clearfix"> </div>
        </div>
    </div>

    <div class="container">

        <div class="head-top">

            <div class="col-sm-8 col-md-offset-2 h_menu4">
                <nav class="navbar nav_bottom" role="navigation">

                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header nav_2">
                        <button type="button" class="navbar-toggle collapsed navbar-toggle1" data-toggle="collapse" data-target="#bs-megadropdown-tabs">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                    </div>
                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-megadropdown-tabs">
                        <ul class="nav navbar-nav nav_1">
                            <li><a class="color" href="index.php"><? echo $mainMenuInfo[0]["btn_title"]; ?></a></li>

                            <li class="dropdown mega-dropdown active">
                                <a class="color1" href="#" class="dropdown-toggle" data-toggle="dropdown">Women<span class="caret"></span></a>
                                <div class="dropdown-menu ">
                                    <div class="menu-top">
                                        <div class="col1">
                                            <div class="h_nav">
                                                <h4>Submenu1</h4>
                                                <ul>
                                                    <li><a href="product.php">Accessories</a></li>
                                                    <li><a href="product.php">Bags</a></li>
                                                    <li><a href="product.php">Caps & Hats</a></li>
                                                    <li><a href="product.php">Hoodies & Sweatshirts</a></li>

                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col1">
                                            <div class="h_nav">
                                                <h4>Submenu2</h4>
                                                <ul>
                                                    <li><a href="product.php">Jackets & Coats</a></li>
                                                    <li><a href="product.php">Jeans</a></li>
                                                    <li><a href="product.php">Jewellery</a></li>
                                                    <li><a href="product.php">Jumpers & Cardigans</a></li>
                                                    <li><a href="product.php">Leather Jackets</a></li>
                                                    <li><a href="product.php">Long Sleeve T-Shirts</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col1">
                                            <div class="h_nav">
                                                <h4>Submenu3</h4>
                                                <ul>
                                                    <li><a href="product.php">Shirts</a></li>
                                                    <li><a href="product.php">Shoes, Boots & Trainers</a></li>
                                                    <li><a href="product.php">Sunglasses</a></li>
                                                    <li><a href="product.php">Sweatpants</a></li>
                                                    <li><a href="product.php">Swimwear</a></li>
                                                    <li><a href="product.php">Trousers & Chinos</a></li>

                                                </ul>

                                            </div>
                                        </div>
                                        <div class="col1">
                                            <div class="h_nav">
                                                <h4>Submenu4</h4>
                                                <ul>
                                                    <li><a href="product.php">T-Shirts</a></li>
                                                    <li><a href="product.php">Underwear & Socks</a></li>
                                                    <li><a href="product.php">Vests</a></li>
                                                    <li><a href="product.php">Jackets & Coats</a></li>
                                                    <li><a href="product.php">Jeans</a></li>
                                                    <li><a href="product.php">Jewellery</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col1 col5">
                                            <img src="images/me.png" class="img-responsive" alt="">
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </li>
                            <li class="dropdown mega-dropdown active">
                                <a class="color2" href="#" class="dropdown-toggle" data-toggle="dropdown">Men<span class="caret"></span></a>
                                <div class="dropdown-menu mega-dropdown-menu">
                                    <div class="menu-top">
                                        <div class="col1">
                                            <div class="h_nav">
                                                <h4>Submenu1</h4>
                                                <ul>
                                                    <li><a href="product.php">Accessories</a></li>
                                                    <li><a href="product.php">Bags</a></li>
                                                    <li><a href="product.php">Caps & Hats</a></li>
                                                    <li><a href="product.php">Hoodies & Sweatshirts</a></li>

                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col1">
                                            <div class="h_nav">
                                                <h4>Submenu2</h4>
                                                <ul>
                                                    <li><a href="product.php">Jackets & Coats</a></li>
                                                    <li><a href="product.php">Jeans</a></li>
                                                    <li><a href="product.php">Jewellery</a></li>
                                                    <li><a href="product.php">Jumpers & Cardigans</a></li>
                                                    <li><a href="product.php">Leather Jackets</a></li>
                                                    <li><a href="product.php">Long Sleeve T-Shirts</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col1">
                                            <div class="h_nav">
                                                <h4>Submenu3</h4>

                                                <ul>
                                                    <li><a href="product.php">Shirts</a></li>
                                                    <li><a href="product.php">Shoes, Boots & Trainers</a></li>
                                                    <li><a href="product.php">Sunglasses</a></li>
                                                    <li><a href="product.php">Sweatpants</a></li>
                                                    <li><a href="product.php">Swimwear</a></li>
                                                    <li><a href="product.php">Trousers & Chinos</a></li>

                                                </ul>

                                            </div>
                                        </div>
                                        <div class="col1">
                                            <div class="h_nav">
                                                <h4>Submenu4</h4>
                                                <ul>
                                                    <li><a href="product.php">T-Shirts</a></li>
                                                    <li><a href="product.php">Underwear & Socks</a></li>
                                                    <li><a href="product.php">Vests</a></li>
                                                    <li><a href="product.php">Jackets & Coats</a></li>
                                                    <li><a href="product.php">Jeans</a></li>
                                                    <li><a href="product.php">Jewellery</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col1 col5">
                                            <img src="images/me1.png" class="img-responsive" alt="">
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </li>
                            <li><a class="color3" href="product.php">Sale</a></li>
                            <li><a class="color4" href="404.html">About</a></li>
                            <li><a class="color5" href="typo.html">Short Codes</a></li>
                            <li ><a class="color6" href="contact.php">Contact</a></li>
                        </ul>
                    </div><!-- /.navbar-collapse -->

                </nav>
            </div>
            <div class="col-sm-2 search-right">
                <ul class="heart">
                    <li>
                        <a href="wishlist.html" >
                            <span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
                        </a></li>
                    <li><a class="play-icon popup-with-zoom-anim" href="#small-dialog"><i class="glyphicon glyphicon-search"> </i></a></li>
                </ul>
                <div class="cart box_1">
                    <a href="checkout.html">
                        <h3> <div class="total">
                                <span class="simpleCart_total"></span></div>
                            <img src="images/cart.png" alt=""/></h3>
                    </a>
                    <p><a href="javascript:;" class="simpleCart_empty">Empty Cart</a></p>

                </div>
                <div class="clearfix"> </div>

                <!----->

                <!---pop-up-box---->
                <link href="css/popuo-box.css" rel="stylesheet" type="text/css" media="all"/>
                <script src="js/jquery.magnific-popup.js" type="text/javascript"></script>
                <!---//pop-up-box---->
                <div id="small-dialog" class="mfp-hide">
                    <div class="search-top">
                        <div class="login-search">
                            <input type="submit" value="">
                            <input type="text" value="Search.." onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Search..';}">
                        </div>
                        <p>Shopin</p>
                    </div>
                </div>
                <script>
                    $(document).ready(function() {
                        $('.popup-with-zoom-anim').magnificPopup({
                            type: 'inline',
                            fixedContentPos: false,
                            fixedBgPos: true,
                            overflowY: 'auto',
                            closeBtnInside: true,
                            preloader: false,
                            midClick: true,
                            removalDelay: 300,
                            mainClass: 'my-mfp-zoom-in'
                        });

                    });
                </script>
                <!----->
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
