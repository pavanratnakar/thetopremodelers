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
<?php echo $pageController->printHeaderMenu(); ?>
    <div class="container-fluid">
        <div class="row main-container">
            <div class="col-md-3 col-xs-12 col-sm-3 sidebar">
                <?php echo $pageController->printLogoContainer(); ?>
            </div>
            <div class="container full-main">
                <div class="row options-container">
                    <div class="col-md-9 col-sm-9 hidden-xs">
                        <?php echo $pageController->printUserStepsText(1); ?>
                    </div>
                    <div class="col-md-3 col-xs-12 col-sm-3 secondary-options-container">
                        <a class="btn btn-warning" title="Back" href="<?php echo Config::$site_url ?>places">
                            Back
                        </a>
                    </div>
                </div>
                <h1>Matching you with our prescreened Contractors for <?php echo $placeDetails['place_title'] ?></h1>
                <div class="row">
                    <div class="col-md-6 col-xs-12 col-sm-6">
                        <ul class="nobullet">
                            <li><h2>Additions &amp; Remodels</h2></li>
                        </ul>
                        <ul class="list">
                            <?php echo $pageController->getFormatedCategories(1,$placeName); ?>
                        </ul>
                    </div>
                    <div class="col-md-6 col-xs-12 col-sm-6">
                        <ul class="nobullet">
                            <li><h2>Handyman Services</h2></li>
                        </ul>
                        <ul class="list">
                            <?php echo $pageController->getFormatedCategories(2,$placeName); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- FOOTER -->
    <?php echo $pageController->printFooterLinks(); ?>
    </div>
<?php echo $pageController->printFooter(); ?>