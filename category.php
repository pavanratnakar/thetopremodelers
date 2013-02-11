<?php
    if(!$_GET['category']){
        header( 'Location: index.php');
        exit;
    }
    include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
    include_once(Config::$site_path.'controller/pageController.php');
    $pageController=new PageController(2);
    $placeName = $pageController->getUtils()->checkValues($_GET['place']);
    $place = $pageController->getPlace();
    $placeDetails = $place->getPlaceDetails($placeName);
    $categoryName = $pageController->getUtils()->checkValues($_GET['category']);
    $category = $pageController->getCategory();
    $categoryDetails = $category->getCategory($placeName);
    $formatedSection = $pageController->getFormatedSections($categoryName,$placeName);
    if (!$formatedSection) {
        header( 'Location: '.Config::$site_url.'404.php');
        exit;
    }
    echo $pageController->printHeader($category->getMeta($categoryName,$placeDetails['place_title'])); 
?>
            <?php echo $pageController->printNavigation(); ?>
            <div class="main-content-container clearfix">
                <div class="header">
                    <?php echo $pageController->printUserStepsText(1); ?>
                    <div class="back-button clearfix">
                        <a class="button orange small rounded" title="Back" href="<?php echo Config::$site_url.'place/'.$placeName ?>">
                            Back
                        </a>
                    </div>
                </div>
                <div class="content clearfix">
                    <div class="main left">
                        <h2>Get Matched to Top-Rated <?php echo $categoryDetails[0]['category_title']?> for <?php echo $placeDetails['place_title']; ?></h2>
                        <ul>
                            <?php echo $formatedSection; ?>
                        </ul>
                    </div>
                    <div class="sidebar right">
                        <div class="sidebar-container">
                            <div class="sidebar-header">
                                <h3>Roofing Library</h3>
                            </div>
                            <div class="sidebar-content">
                                <ul>
                                    <li>
                                        <h4>A roofing contractor</h4>
                                        <ul class="clearfix">
                                            <li><a href="<?php echo Config::$site_url.'article/having_right_contract' ?>" title="Have the right contract">Have the right contract</a></li>
                                            <li><a href="<?php echo Config::$site_url.'article/roofings_warranty' ?>" title="Choosing your roofing warranty">Choosing your roofing warranty</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <h4>Your choices of flat roofs</h4>
                                        <ul class="clearfix">
                                            <li><a href="<?php echo Config::$site_url.'article/pvc_roofs' ?>" title="Pvc roofs">Pvc roofs</a></li>
                                            <li><a href="<?php echo Config::$site_url.'article/rubber_membrane_usage' ?>" title="Rubber membrane">Rubber membrane</a></li>
                                            <li><a href="<?php echo Config::$site_url.'article/built_up_flat_roofs' ?>" title="Rubber membrane">Built up Flat Roofs</a></li>
                                            <li><a href="<?php echo Config::$site_url.'article/tpo_roofing' ?>" title="Tpo roofing">Tpo roofing</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <h4>Different types of roofs materials</h4>
                                        <ul class="clearfix">
                                            <li><a href="<?php echo Config::$site_url.'article/metal_roof' ?>" title="Metal roof">Metal roof</a></li>
                                            <li><a href="<?php echo Config::$site_url.'article/benefits_of_metal_roofing' ?>" title="Asphalt singles">Asphalt singles</a></li>
                                            <li><a href="<?php echo Config::$site_url.'article/green_roofing' ?>" title="Green roofing">Green roofing</a></li>
                                            <li><a href="<?php echo Config::$site_url.'article/standing_seam_roof' ?>" title="Standing Seam roof">Standing Seam roof</a></li>
                                            <li><a href="<?php echo Config::$site_url.'article/steel_roofing' ?>" title="Steel roofing">Steel roofing</a></li>
                                            <li><a href="<?php echo Config::$site_url.'article/natural_slate_roofing' ?>" title="Natural slate roofing">Natural slate roofing</a></li>
                                            <li><a href="<?php echo Config::$site_url.'article/cooper_roofing' ?>" title="Cooper roofing">Cooper roofing</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <h4>Your Insurance</h4>
                                        <ul class="clearfix">
                                            <li><a href="<?php echo Config::$site_url.'article/dallas_under_dangerous_threat_hailstorm' ?>" title="Dallas under the dangerous threat of Hailstorm in June 2012">Dallas under the dangerous threat of Hailstorm in June 2012</a></li>
                                            <li><a href="<?php echo Config::$site_url.'article/3_ways_your_roof_can_save_you' ?>" title="3 ways your roof can save you">3 ways your roof can save you</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $pageController->printFooterLinks(); ?>
            </div>
            <div class="clear"></div>
        </div>
<?php echo $pageController->printFooter(); ?>