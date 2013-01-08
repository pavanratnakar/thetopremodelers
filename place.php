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
            <div class="main-content-container clearfix">
                <div class="header">
                    <?php echo $pageController->printUserStepsText(1); ?>
                    <div class="back-button clearfix">
                        <a class="clearfix" title="Back" href="<?php echo Config::$site_url ?>">
                            <img width="104" height="34" src="<?php echo Config::$site_url.'/images/global/buttons/back-button.jpg' ?>" title="Back" alt="Back"/>
                        </a>
                    </div>
                </div>
                <div class="content clearfix">
                    <div class="main left">
                        <h2>Matching you with our prescreened Contractors for <?php echo $placeDetails['place_title'] ?></h2>
                        <div class="left clearfix first">
                            <ul class="top">
                                <li>Additions &amp; Remodels</li>
                            </ul>
                            <ul class="list clearfix">
                                <?php echo $pageController->getFormatedCategories(1,$placeName); ?>
                            </ul>
                        </div>
                        <div class="left clearfix">
                            <ul class="top">
                                <li>Handyman Services</li>
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