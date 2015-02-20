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
    echo $pageController->minifyHTML($pageController->printHeader(null, false, $theme).$pageController->printHeaderMenu().
        '<div class="container-fluid">
            <div class="row main-container">
                <div class="col-md-3 col-xs-12 col-sm-3 sidebar">
                    '.$pageController->printLogoContainer().'
                </div>
                <div class="container main">
                    <div class="curl hidden-xs">
                        <div class="curl-content">
                            <h3>Unlimited time offer</h3>
                            <p>Get estimate from our contractors<br/> And enter to win our <b>10k</b> solar systems</p>
                        </div>
                    </div>');
                    if ($callout == 2) {
                    echo $pageController->minifyHTML('
                    <div class="header-content">
                        <h1>'.$promotion.'</h1>
                        <h2>Immediate service 24/7 call 1(214)303 9771</h2>
                        <a class="btn btn-orange" href="'.Config::$site_url.'contact-us">Get Quotes</a>
                    </div>');
                    } else {
                    echo $pageController->minifyHTML('
                    <h1>'.$promotion.'</h1>
                    <a class="btn btn-primary" href="'.Config::$site_url.'contact-us">Get Quotes</a>
                    <h2>Immediate service 24/7<br/>call 1(214)303 9771</h2>');
                    }
                    echo $pageController->minifyHTML('
                </div>
            </div>');
            if ($contractors == 1) {
            echo $pageController->minifyHTML('
            <div class="contractor-details">
                <div class="container main">
                    <div class="row">
                        <div class="col-md-8 col-xs-12 col-sm-8">
                            <h4>Get estimates today</h4>
                            <div class="row contractor-detail">
                                <div class="col-md-4 col-xs-6 col-sm-4">
                                    <img alt="" class="border" src="'.Config::$site_url.'images/promotion/person_1.png" />
                                </div>
                                <div class="col-md-6 col-xs-6 col-sm-6">
                                    <h5>Joe</h5>
                                    <h6>45$ to 50$ per 100 square feet install</h6>
                                    <div class="reviews">
                                        <p><i class="rating-static rating-50"></i></p>
                                        <p>4 certified reviews</p>
                                    </div>
                                    <a class="btn btn-orange" href="'.Config::$site_url.'contact-us">Get Quotes</a>
                                </div>
                            </div>
                            <div class="row contractor-detail">
                                <div class="col-md-4 col-xs-6 col-sm-4">
                                    <img alt="" class="border" src="'.Config::$site_url.'images/promotion/person_2.png" />
                                </div>
                                <div class="col-md-6 col-xs-6 col-sm-6">
                                    <h5>Oscar</h5>
                                    <h6>42$ to 60$ per 100 square feet install</h6>
                                    <div class="reviews">
                                        <p><i class="rating-static rating-50"></i></p>
                                        <p>2 certified reviews</p>
                                    </div>
                                    <a class="btn btn-orange" href="'.Config::$site_url.'contact-us">Get Quotes</a>
                                </div>
                            </div>
                            <div class="row contractor-detail">
                                <div class="col-md-4 col-xs-6 col-sm-4">
                                    <img alt="" class="border" src="'.Config::$site_url.'images/promotion/person_3.png" />
                                </div>
                                <div class="col-md-6 col-xs-6 col-sm-6">
                                    <h5>Raphale</h5>
                                    <h6>45$ to 70$ per 100 square feet install</h6>
                                    <div class="reviews">
                                        <p><i class="rating-static rating-50"></i></p>
                                        <p>2 certified reviews</p>
                                    </div>
                                    <a class="btn btn-orange" href="'.Config::$site_url.'contact-us">Get Quotes</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-xs-12 col-sm-4 certified-reviews-container">
                            <h4>Certified Reviews</h4>
                            <p>With so many companies providing online reviews, it becoming difficult to trust any of them, your friend can post a review or you enemy can a post review.</p>
                            <p>We are going on step further, in order to get is review certified the contractor must provide a proof of the job completion (building permit, proof of payment, etc)</p>
                            <img alt="" src="'.Config::$site_url.'images/home/stamp_final.png" />
                        </div>
                    </div>
                    <div class="row contractor-detail-footer">
                        <div class="col-md-12 col-xs-12 col-sm-12">
                            <h4>Our promotion include</h4>
                            <div class="contractor-callout-content">
                                <div class="holder">
                                    <img src="'.Config::$site_url.'images/promotion/holder.png" alt="" width="310"/>
                                    <div class="leaf hidden-xs">
                                        <img src="'.Config::$site_url.'images/promotion/leaf.png" alt="" />
                                    </div>
                                </div>
                                <ul>
                                    <li>10 Astronergy 255-watt Solar Panels</li>
                                    <li>10 Solar Edge Power Boxes</li>
                                    <li>1 SolarEdge 5KW Gridtied Inverter</li>
                                    <li>UL Listed Disconnects and Safety Fuses</li>
                                    <li>UL Listed Cables and Connectors</li>
                                    <li>Building permits</li>
                                    <li>Full installation by professional company</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>');
            }
            echo $pageController->minifyHTML(
            $pageController->printFooterLinks().'
        </div>'.$pageController->printFooter());