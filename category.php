<?php

require_once "./php/configuration.php";

require 'vendor/autoload.php';
\Firebase\JWT\JWT::$leeway = 60;

use Auth0\SDK\Auth0;

$auth0 = new Auth0([
    'domain' => 'dev-44t0mog0.eu.auth0.com',
    'client_id' => 'hzLwly8pSwfEEJPBcJXtd8HLLS6eO0ZC',
    'client_secret' => 'oUbeVZiuepsh92ldnjHHPAuEaI2WDEjDUM7aXAN-vcONJlRZ9T5SrB-SQUwiA8Rr',
    'redirect_uri' => $siteName . '/category.php',
    'persist_id_token' => true,
    'persist_access_token' => true,
    'persist_refresh_token' => true,
        ]);
$userInfo = $auth0->getUser();
echo json_encode($userInfo);
?>
<!DOCTYPE html>
<html lang="zxx" class="no-js">

    <head>
        <!-- Mobile Specific Meta -->
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Favicon-->
        <link rel="shortcut icon" href="img/fav.png">
        <!-- Author Meta -->
        <meta name="author" content="CodePixar">
        <!-- Meta Description -->
        <meta name="description" content="">
        <!-- Meta Keyword -->
        <meta name="keywords" content="">
        <!-- meta character set -->
        <meta charset="UTF-8">
        <!-- Site Title -->
        <title>Just Another Minecraft Store Shop</title>

        <!--
            CSS
            ============================================= -->
        <link rel="stylesheet" href="css/linearicons.css">
        <link rel="stylesheet" href="css/owl.carousel.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/themify-icons.css">
        <link rel="stylesheet" href="css/nice-select.css">
        <link rel="stylesheet" href="css/nouislider.min.css">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/main.css">
    </head>

    <body id="category">

        <!-- Start Header Area -->
        <header class="header_area sticky-header">
            <div class="main_menu">
                <nav class="navbar navbar-expand-lg navbar-light main_box">
                    <div class="container">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <a class="navbar-brand logo_h" href="index.html"><img src="img/logo.png" alt=""></a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
                            <ul class="nav navbar-nav menu_nav ml-auto">
                                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                                <li class="nav-item submenu dropdown active">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                                       aria-expanded="false">Shop</a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item active"><a class="nav-link" href="category.php">Shop Category</a></li>
                                        <li class="nav-item"><a class="nav-link" href="single-product.php">Product Details</a></li>
                                        <li class="nav-item"><a class="nav-link" href="checkout.html">Product Checkout</a></li>
                                        <li class="nav-item"><a class="nav-link" href="cart.html">Shopping Cart</a></li>
                                        <li class="nav-item"><a class="nav-link" href="confirmation.html">Confirmation</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item submenu dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                                       aria-expanded="false">Blog</a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item"><a class="nav-link" href="blog.html">Blog</a></li>
                                        <li class="nav-item"><a class="nav-link" href="single-blog.html">Blog Details</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item submenu dropdown">
                                    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                                       aria-expanded="false">Pages</a>
                                    <ul class="dropdown-menu">
                                        <li class="nav-item"><a class="nav-link" href="login.html">Login</a></li>
                                        <li class="nav-item"><a class="nav-link" href="tracking.html">Tracking</a></li>
                                        <li class="nav-item"><a class="nav-link" href="elements.html">Elements</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
                                <?php if (!$userInfo): ?>
                                    <li class="nav-item"><a class="nav-link" href="api/users/login.php">Log In</a></li>
                                <?php else: ?>
                                    <li class="nav-item submenu dropdown">
                                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                                           aria-expanded="false"><?php echo $userInfo["nickname"] ?></a>
                                        <ul class="dropdown-menu">
                                            <li class="nav-item"><a class="nav-link" href="profile_settings.php">Settings</a></li>
                                            <li class="nav-item"><a class="nav-link" href="api/users/logout.php">Log out</a></li>
                                        </ul>
                                    </li>
                                <?php endif ?>
                            </ul>
                            <ul class="nav navbar-nav navbar-right">
                                <li class="nav-item"><a href="#" class="cart"><span class="ti-bag"></span></a></li>
                                <li class="nav-item">
                                    <button class="search"><span class="lnr lnr-magnifier" id="search"></span></button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="search_input" id="search_input_box">
                <div class="container">
                    <form class="d-flex justify-content-between">
                        <input type="text" class="form-control" id="search_input" placeholder="Search Here">
                        <button type="submit" class="btn"></button>
                        <span class="lnr lnr-cross" id="close_search" title="Close Search"></span>
                    </form>
                </div>
            </div>
        </header>
        <!-- End Header Area -->

        <!-- Start Banner Area -->
        <section class="banner-area organic-breadcrumb">
            <div class="container">
                <div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
                    <div class="col-first">
                        <h1>Shop Category page</h1>
                        <nav class="d-flex align-items-center">
                            <a href="index.html">Home<span class="lnr lnr-arrow-right"></span></a>
                            <a href="#">Shop<span class="lnr lnr-arrow-right"></span></a>
                            <a href="category.html">Fashon Category</a>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Banner Area -->
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-5">
                    <div class="sidebar-categories">
                        <div class="head">Browse Categories</div>
                        <ul id='ag_categories' class="main-categories"></ul>
                    </div>
                    <div class="sidebar-filter mt-50">
                        <div class="top-filter-head">Product Filters</div>
                        <div class="common-filter">
                            <div class="head">Price</div>
                            <div class="price-range-area">
                                <div id="price-range"></div>
                                <div class="value-wrapper d-flex">
                                    <div class="price">Price:</div>
                                    <span>$</span>
                                    <div id="lower-value"></div>
                                    <div class="to">to</div>
                                    <span>$</span>
                                    <div id="upper-value"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 col-lg-8 col-md-7">
                    <!-- Start Filter Bar -->
                    <div class="filter-bar d-flex flex-wrap align-items-center">
                        <div class="sorting">
                            <select onchange="setParam('sorting', this.value)">
                                <option value="default">Default sorting</option>
                                <option value="priceasc">Price Ascending</option>
                                <option value="pricedesc">Price Descending</option>
                            </select>
                        </div>
                        <div class="sorting mr-auto">
                            <select id="ag_products_per_page" onchange="setParam('pagelimit', this.value)">
                                <option value="12">Show 12</option>
                                <option value="27">Show 27</option>
                                <option value="57">Show 57</option>
                            </select>
                        </div>
                        <div class="pagination"></div>
                    </div>
                    <!-- End Filter Bar -->
                    <!-- Start Best Seller -->
                    <section class="lattest-product-area pb-40 category-list">
                        <div id="product_list" class="row"></div>
                    </section>
                    <!-- End Best Seller -->
                    <!-- Start Filter Bar -->
                    <div class="filter-bar d-flex flex-wrap align-items-center">
                        <div class="sorting mr-auto">
                            <!--
                            <select>
                                <option value="10">Show 10</option>
                                <option value="25">Show 25</option>
                                <option value="50">Show 50</option>
                            </select>
                            debug and probably useless-->
                        </div>
                        <div class="pagination"></div>
                    </div>
                    <!-- End Filter Bar -->
                </div>
            </div>
        </div>

        <!-- Start related-product Area -->
        <section class="related-product-area section_gap">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6 text-center">
                        <div class="section-title">
                            <h1>Deals of the Week</h1>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore
                                magna aliqua.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-9">
                        <div class="row">
                            <div class="col-lg-4 col-md-4 col-sm-6 mb-20">
                                <div class="single-related-product d-flex">
                                    <a href="#"><img src="img/r1.jpg" alt=""></a>
                                    <div class="desc">
                                        <a href="#" class="title">Black lace Heels</a>
                                        <div class="price">
                                            <h6>$189.00</h6>
                                            <h6 class="l-through">$210.00</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 mb-20">
                                <div class="single-related-product d-flex">
                                    <a href="#"><img src="img/r2.jpg" alt=""></a>
                                    <div class="desc">
                                        <a href="#" class="title">Black lace Heels</a>
                                        <div class="price">
                                            <h6>$189.00</h6>
                                            <h6 class="l-through">$210.00</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 mb-20">
                                <div class="single-related-product d-flex">
                                    <a href="#"><img src="img/r3.jpg" alt=""></a>
                                    <div class="desc">
                                        <a href="#" class="title">Black lace Heels</a>
                                        <div class="price">
                                            <h6>$189.00</h6>
                                            <h6 class="l-through">$210.00</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 mb-20">
                                <div class="single-related-product d-flex">
                                    <a href="#"><img src="img/r5.jpg" alt=""></a>
                                    <div class="desc">
                                        <a href="#" class="title">Black lace Heels</a>
                                        <div class="price">
                                            <h6>$189.00</h6>
                                            <h6 class="l-through">$210.00</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 mb-20">
                                <div class="single-related-product d-flex">
                                    <a href="#"><img src="img/r6.jpg" alt=""></a>
                                    <div class="desc">
                                        <a href="#" class="title">Black lace Heels</a>
                                        <div class="price">
                                            <h6>$189.00</h6>
                                            <h6 class="l-through">$210.00</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6 mb-20">
                                <div class="single-related-product d-flex">
                                    <a href="#"><img src="img/r7.jpg" alt=""></a>
                                    <div class="desc">
                                        <a href="#" class="title">Black lace Heels</a>
                                        <div class="price">
                                            <h6>$189.00</h6>
                                            <h6 class="l-through">$210.00</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="single-related-product d-flex">
                                    <a href="#"><img src="img/r9.jpg" alt=""></a>
                                    <div class="desc">
                                        <a href="#" class="title">Black lace Heels</a>
                                        <div class="price">
                                            <h6>$189.00</h6>
                                            <h6 class="l-through">$210.00</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="single-related-product d-flex">
                                    <a href="#"><img src="img/r10.jpg" alt=""></a>
                                    <div class="desc">
                                        <a href="#" class="title">Black lace Heels</a>
                                        <div class="price">
                                            <h6>$189.00</h6>
                                            <h6 class="l-through">$210.00</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="single-related-product d-flex">
                                    <a href="#"><img src="img/r11.jpg" alt=""></a>
                                    <div class="desc">
                                        <a href="#" class="title">Black lace Heels</a>
                                        <div class="price">
                                            <h6>$189.00</h6>
                                            <h6 class="l-through">$210.00</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="ctg-right">
                            <a href="#" target="_blank">
                                <img class="img-fluid d-block mx-auto" src="img/category/c5.jpg" alt="">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End related-product Area -->

        <!-- start footer Area -->
        <footer class="footer-area section_gap">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3  col-md-6 col-sm-6">
                        <div class="single-footer-widget">
                            <h6>About Us</h6>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore dolore
                                magna aliqua.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4  col-md-6 col-sm-6">
                        <div class="single-footer-widget">
                            <h6>Newsletter</h6>
                            <p>Stay update with our latest</p>
                            <div class="" id="mc_embed_signup">

                                <form target="_blank" novalidate="true" action="https://spondonit.us12.list-manage.com/subscribe/post?u=1462626880ade1ac87bd9c93a&amp;id=92a4423d01"
                                      method="get" class="form-inline">

                                    <div class="d-flex flex-row">

                                        <input class="form-control" name="EMAIL" placeholder="Enter Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Email '"
                                               required="" type="email">


                                        <button class="click-btn btn btn-default"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></button>
                                        <div style="position: absolute; left: -5000px;">
                                            <input name="b_36c4fd991d266f23781ded980_aefe40901a" tabindex="-1" value="" type="text">
                                        </div>

                                        <!-- <div class="col-lg-4 col-md-4">
                                                                        <button class="bb-btn btn"><span class="lnr lnr-arrow-right"></span></button>
                                                                </div>  -->
                                    </div>
                                    <div class="info"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3  col-md-6 col-sm-6">
                        <div class="single-footer-widget mail-chimp">
                            <h6 class="mb-20">Instragram Feed</h6>
                            <ul class="instafeed d-flex flex-wrap">
                                <li><img src="img/i1.jpg" alt=""></li>
                                <li><img src="img/i2.jpg" alt=""></li>
                                <li><img src="img/i3.jpg" alt=""></li>
                                <li><img src="img/i4.jpg" alt=""></li>
                                <li><img src="img/i5.jpg" alt=""></li>
                                <li><img src="img/i6.jpg" alt=""></li>
                                <li><img src="img/i7.jpg" alt=""></li>
                                <li><img src="img/i8.jpg" alt=""></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-6">
                        <div class="single-footer-widget">
                            <h6>Follow Us</h6>
                            <p>Let us be social</p>
                            <div class="footer-social d-flex align-items-center">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-dribbble"></i></a>
                                <a href="#"><i class="fa fa-behance"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
                    <p class="footer-text m-0"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    </p>
                </div>
            </div>
        </footer>
        <!-- End footer Area -->

        <!-- Modal Quick Product View -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="container relative">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="product-quick-view">
                        <div class="row align-items-center">
                            <div class="col-lg-6">
                                <div class="quick-view-carousel">
                                    <div class="item" style="background: url(img/organic-food/q1.jpg);">

                                    </div>
                                    <div class="item" style="background: url(img/organic-food/q1.jpg);">

                                    </div>
                                    <div class="item" style="background: url(img/organic-food/q1.jpg);">

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="quick-view-content">
                                    <div class="top">
                                        <h3 class="head">Mill Oil 1000W Heater, White</h3>
                                        <div class="price d-flex align-items-center"><span class="lnr lnr-tag"></span> <span class="ml-10">$149.99</span></div>
                                        <div class="category">Category: <span>Household</span></div>
                                        <div class="available">Availibility: <span>In Stock</span></div>
                                    </div>
                                    <div class="middle">
                                        <p class="content">Mill Oil is an innovative oil filled radiator with the most modern technology. If you are
                                            looking for something that can make your interior look awesome, and at the same time give you the pleasant
                                            warm feeling during the winter.</p>
                                        <a href="#" class="view-full">View full Details <span class="lnr lnr-arrow-right"></span></a>
                                    </div>
                                    <div class="bottom">
                                        <div class="color-picker d-flex align-items-center">Color:
                                            <span class="single-pick"></span>
                                            <span class="single-pick"></span>
                                            <span class="single-pick"></span>
                                            <span class="single-pick"></span>
                                            <span class="single-pick"></span>
                                        </div>
                                        <div class="quantity-container d-flex align-items-center mt-15">
                                            Quantity:
                                            <input type="text" class="quantity-amount ml-15" value="1" />
                                            <div class="arrow-btn d-inline-flex flex-column">
                                                <button class="increase arrow" type="button" title="Increase Quantity"><span class="lnr lnr-chevron-up"></span></button>
                                                <button class="decrease arrow" type="button" title="Decrease Quantity"><span class="lnr lnr-chevron-down"></span></button>
                                            </div>

                                        </div>
                                        <div class="d-flex mt-20">
                                            <a href="#" class="view-btn color-2"><span>Add to Cart</span></a>
                                            <a href="#" class="like-btn"><span class="lnr lnr-layers"></span></a>
                                            <a href="#" class="like-btn"><span class="lnr lnr-heart"></span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <script src="js/vendor/jquery-2.2.4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
        crossorigin="anonymous"></script>
        <script src="js/vendor/bootstrap.min.js"></script>
        <script src="js/jquery.ajaxchimp.min.js"></script>
        <script src="js/jquery.nice-select.min.js"></script>
        <script src="js/jquery.sticky.js"></script>
        <script src="js/nouislider.min.js"></script>
        <script src="js/jquery.magnific-popup.min.js"></script>
        <script src="js/owl.carousel.min.js"></script>
        <!--php-->
        <script src="js/main_shop.js" type="text/javascript"></script>
        <!---->
        <script src="js/main.js"></script>
    </body>

</html>