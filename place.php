<?php
    if(!$_GET['name']){
        header( 'Location: index.php');
        exit;
    }
    include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
    include_once(Config::$site_path.'controller/pageController.php');
    $pageController=new PageController(7);
    $placeName = $pageController->getUtils()->checkValues($_GET['name']);
    $place = $pageController->getPlace();
    $placeDetails = $place->getPlaceDetails($placeName);
    if (sizeof($placeDetails) == 0){
        header( 'Location: '.Config::$site_url.'404.php');
        exit;
    }
    echo $pageController->printHeader($pageController->getMeta('place',$placeName)); 
?>
            <?php echo $pageController->printNavigation(); ?>
            <div class="main-content-container clearfix">
                <div class="header">
                    <?php echo $pageController->printUserStepsText(1); ?>
                    <div class="back-button clearfix">
                        <a class="button orange small rounded" title="Back" href="<?php echo Config::$site_url ?>places">
                            Back
                        </a>
                    </div>
                </div>
                <div class="content clearfix">
                    <div class="main left">
                        <h2>Matching you with our prescreened Contractors for <?php echo $placeDetails['place_title'] ?></h2>
                        <div class="left clearfix first">
                            <ul class="top">
                                <li><h3>Additions &amp; Remodels</h3></li>
                            </ul>
                            <ul class="list clearfix">
                                <?php echo $pageController->getFormatedCategories(1,$placeName); ?>
                            </ul>
                        </div>
                        <div class="left clearfix">
                            <ul class="top">
                                <li><h3>Handyman Services</h3></li>
                            </ul>
                            <ul class="list clearfix">
                                <?php echo $pageController->getFormatedCategories(2,$placeName); ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php echo $pageController->printFooterLinks(); ?>
            </div>
            <div class="clear"></div>
        </div>
<?php echo $pageController->printFooter(); ?>