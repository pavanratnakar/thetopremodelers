<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
    include_once(Config::$site_path.'controller/pageController.php');
    $pageController=new PageController(11);
    $promotionType = $pageController->getUtils()->checkValues($_GET['type']);
    echo $pageController->printHeader();
    if ($promotionType) {
        $promotions = array(
            "roof-repairs-dallas" => "Get top rated roofers for roof repairs in dallas",
            "dallas-roof-repair" => "Best quotes for roof repair in Dallas",
            "garland-roofing" => "GARLAND ROOFING TOP RATED CONTRACTORS",
            "garland-roofing-contractors" => "GET TOP RATED GARLAND ROOFING CONTRACTORS"
        );
        if ($promotions[$promotionType]) {
            $promotion = $promotions[$promotionType];
        } else {
            $promotion = 'Get top rated roofers';
        }
    }
?>
        <?php echo $pageController->printHeaderMenu(); ?>
        <div class="container-fluid">
            <div class="row main-container">
                <div class="col-md-3 col-xs-3 col-sm-3 sidebar">
                    <?php echo $pageController->printLogoContainer(); ?>
                </div>
                <div class="container main">
                    <h1><?php echo $promotion; ?></h1>
                    <h2>Immediate service 24/7<br/>call 1(214)303 9771</h2>
                    <a class="btn btn-primary" href="<?php echo Config::$site_url ?>contact-us">Get Quotes</a>
                </div>
                <div class="curl">
                    <div class="curl-content">
                        <h3>Unlimited time offer</h3>
                        <p>Get estimate from our contractors<br/> And enter to win our <b>10k</b> solar systems</p>
                    </div>
                </div>
            </div>
            <!-- FOOTER -->
            <?php echo $pageController->printFooterLinks(); ?>
        </div>
        <?php echo $pageController->printFooter(); ?>