<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
    include_once(Config::$site_path.'controller/pageController.php');
    $pageController=new PageController(11);
    $promotionType = $pageController->getUtils()->checkValues($_GET['type']);
    $theme = 1;
    $promotion = '';
    if ($promotionType) {
        $promotions = array(
            "roof-repairs-dallas" => "Get top rated roofers for roof repairs in dallas",
            "dallas-roof-repair" => "Best quotes for roof repair in Dallas",
            "garland-roofing" => "GARLAND ROOFING TOP RATED CONTRACTORS",
            "garland-roofing-contractors" => "GET TOP RATED GARLAND ROOFING CONTRACTORS",
            "demo" => "DEMO",
            "video" => "Get multiple free roofing quotes",
            "demo2" => "Get multiple free roofing quotes"
        );
        if ($promotions[$promotionType]) {
            $promotion = $promotions[$promotionType];
        }
    }
    if (!$promotion) {
        $promotion = 'Get top rated roofers';
    }
    if ($promotionType == 'demo') {
        $theme = 0;
    }
    if ($promotionType == 'demo2') {
        $callout = 2;
        $contractors = 1;
    }
    if ($promotionType == 'video') {
        $theme = 99;
        $callout = 2;
        $contractors = 1;
    }
    echo $pageController->printHeader(null, false, $theme);
?>
        <?php echo $pageController->printHeaderMenu(); ?>
        <div class="container-fluid">
            <div class="row main-container">
                <div class="col-md-3 col-xs-12 col-sm-3 sidebar">
                    <?php echo $pageController->printLogoContainer(); ?>
                </div>
                <div class="container main">
                    <div class="curl">
                        <div class="curl-content">
                            <h3>Unlimited time offer</h3>
                            <p>Get estimate from our contractors<br/> And enter to win our <b>10k</b> solar systems</p>
                        </div>
                    </div>
                    <?php if ($callout == 2) { ?>
                    <div class="header-content">
                        <h1><?php echo $promotion; ?></h1>
                        <h2>Immediate service 24/7 call 1(214)303 9771</h2>
                        <a class="btn btn-orange" href="<?php echo Config::$site_url ?>contact-us">Get Quotes</a>
                    </div>
                    <?php } else { ?>
                    <h1><?php echo $promotion; ?></h1>
                    <a class="btn btn-primary" href="<?php echo Config::$site_url ?>contact-us">Get Quotes</a>
                    <h2>Immediate service 24/7<br/>call 1(214)303 9771</h2>
                    <?php } ?>
                </div>
            </div>
            <?php if ($contractors == 1) { ?>
            <div class="contractor-details">
                <div class="container main">
                    <div class="row">
                        <div class="col-md-7 col-xs-12 col-sm-7">
                            <h4>Get estimates today</h4>
                            <div class="row contractor-detail">
                                <div class="col-md-4 col-xs-6 col-sm-4">
                                    <img alt="" class="border" src="<?php echo Config::$site_url.'images/promotion/person_1.png' ?>" />
                                </div>
                                <div class="col-md-6 col-xs-6 col-sm-6">
                                    <h5>Joe</h5>
                                    <h6>45$ to 50$ per 100 square feet install</h6>
                                    <a class="btn btn-orange" href="<?php echo Config::$site_url ?>contact-us">Get Quotes</a>
                                </div>
                            </div>
                            <div class="row contractor-detail">
                                <div class="col-md-4 col-xs-6 col-sm-4">
                                    <img alt="" class="border" src="<?php echo Config::$site_url.'images/promotion/person_2.png' ?>" />
                                </div>
                                <div class="col-md-6 col-xs-6 col-sm-6">
                                    <h5>Oscar</h5>
                                    <h6>42$ to 60$ per 100 square feet install</h6>
                                    <a class="btn btn-orange" href="<?php echo Config::$site_url ?>contact-us">Get Quotes</a>
                                </div>
                            </div>
                            <div class="row contractor-detail">
                                <div class="col-md-4 col-xs-6 col-sm-4">
                                    <img alt="" class="border" src="<?php echo Config::$site_url.'images/promotion/person_3.png' ?>" />
                                </div>
                                <div class="col-md-6 col-xs-6 col-sm-6">
                                    <h5>Raphale</h5>
                                    <h6>45$ to 70$ per 100 square feet install</h6>
                                    <a class="btn btn-orange" href="<?php echo Config::$site_url ?>contact-us">Get Quotes</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 hidden-xs col-sm-5">
                            <h4>Our Green Commitment</h4>
                            <div class="contractor-callout-content">
                                <div class="holder">
                                    <div class="leaf">
                                        <img src="<?php echo Config::$site_url.'images/promotion/leaf.png' ?>" alt="" />
                                    </div>
                                </div>
                                <p>Our green commitment With the Threat of global warming on the rise, one of our goal in the next 12 month is to become a carbon neutral company, and in the next 2 years to become A carbon negative company, meaning we will be removing more carbon co 2 that we are responsible for creating. We believe companies should play a large role in fighting global warming, and consumers should start buying from carbon negative companies. We hope to set a new trend, and that over will follow us.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row contractor-detail-footer">
                        <div class="col-md-6 col-xs-12 col-sm-6 left">
                            <img alt="" src="<?php echo Config::$site_url.'images/home/stamp_final.png' ?>" />
                        </div>
                        <div class="col-md-6 hidden-xs col-sm-6 right">
                            <img alt="" src="<?php echo Config::$site_url.'images/global/logo.png' ?>" />
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
            <!-- FOOTER -->
            <?php echo $pageController->printFooterLinks(); ?>
        </div>
        <?php echo $pageController->printFooter(); ?>