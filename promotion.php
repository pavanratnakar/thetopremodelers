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
            "demo" => "DEMO"
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
    echo $pageController->printHeader(null, false, $theme);
?>
        <?php echo $pageController->printHeaderMenu(); ?>
        <div class="container-fluid">
            <div class="row main-container">
                <div class="col-md-3 col-xs-3 col-sm-3 sidebar">
                    <?php echo $pageController->printLogoContainer(); ?>
                </div>
                <div class="container main">
                    <div class="curl">
                        <div class="curl-content">
                            <h3>Unlimited time offer</h3>
                            <p>Get estimate from our contractors<br/> And enter to win our <b>10k</b> solar systems</p>
                        </div>
                    </div>
                    <h1><?php echo $promotion; ?></h1>
                    <a class="btn btn-primary" href="<?php echo Config::$site_url ?>contact-us">Get Quotes</a>
                    <h2>Immediate service 24/7<br/>call 1(214)303 9771</h2>
                </div>
            </div>
            <!-- FOOTER -->
            <?php echo $pageController->printFooterLinks(); ?>
        </div>
        <?php echo $pageController->printFooter(); ?>