<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
if(!$_GET['category'] || !$_GET['section'] || !$_GET['place']){
    header( 'Location: '.Config::$site_url.'index.php');
    exit;
}
include_once(Config::$site_path.'controller/pageController.php');
$pageController=new PageController(3);
$placeName = $pageController->getUtils()->checkValues($_GET['place']);
$place = $pageController->getPlace();
$placeDetails = $place->getPlaceDetails($placeName);
$category = $pageController->getCategory();
$categoryName = $pageController->getUtils()->checkValues($_GET['category']);
$categoryDetails = $category->getCategory($categoryName);
$sectionName = $pageController->getUtils()->checkValues($_GET['section']);
$section = $pageController->getSection($categoryName,$placeName);
$sectionDetails = $section->getSectionDetails($sectionName);
if ($_GET['contractor']) {
    $contractorName = $pageController->getUtils()->checkValues($_GET['contractor']);
    $contractor = $pageController->getContractor();
    $contractorDetails = $contractor->getContractor($contractorName);
    $contractorTitle = $contractorDetails['contractor_title'];
}
if ($contractorDetails) {
    $back = Config::$site_url.$placeName.'/'.$categoryName.'/'.$sectionName.'/contractors/';
    $submit = Config::$site_url.'placeRequest?place='.$placeName.'&category='.$categoryName.'&section='.$sectionName.'&contractor='.$contractorName;
} else {
    $back = Config::$site_url.$placeName.'/category/'.$categoryName;
    $submit = Config::$site_url.'placeRequest?place='.$placeName.'&category='.$categoryName.'&section='.$sectionName;
}
$formatQuestions = $pageController->formatNewQuestions($placeName,$categoryName,$sectionName);
if (!$formatQuestions) {
    header( 'Location: '.$submit);
    exit;
}
$metaDetails = $section->getMeta($contractorTitle);
$metaDetails['geo'] = $placeDetails['place_geo'];
$metaDetails['geo_placename'] = $placeDetails['place_geo_placename'];
echo $pageController->printHeader($metaDetails,true);
?>
<?php echo $pageController->printHeaderMenu(); ?>
    <div class="container-fluid">
        <div class="row main-container">
            <div class="col-md-3 col-xs-12 col-sm-3 sidebar">
                <?php echo $pageController->printLogoContainer(); ?>
            </div>
            <div class="regular-main">
                <div class="row options-container">
                    <div class="col-md-9 col-sm-9 hidden-xs">
                        <?php echo $pageController->printUserStepsText(2); ?>
                    </div>
                    <div class="col-md-3 col-xs-12 col-sm-3 secondary-options-container">
                        <a class="btn btn-warning" title="Back" href="<?php echo Config::$site_url ?>places">
                            Back
                        </a>
                    </div>
                </div>
                <div class="row sub">
                    <div class="col-md-8 col-xs-12 col-sm-8">
                        <?php
                        if ($contractorDetails) { ?>
                        <div id="top" class="top">
                            <?php echo $pageController->getContractorDetails($contractorDetails,false); ?>
                        </div>
                    </div>
                </div>
                <div class="row form-container">
                    <div class="col-md-12 col-xs-12 col-sm-12">
                        <h3>Request a Quote: <?php echo $sectionDetails['section_title']?> for <?php echo $placeDetails['place_title']; ?></h3>
                        <?php } else { ?>
                        <h3>Submit and Get Matched to Prescreened <?php echo $sectionDetails['section_title']?> for <?php echo $placeDetails['place_title']; ?></h3>
                        <?php } ?>
                        <form id="questionForm" action="<?php echo $submit ?>" method="post" role="form">
                            <fieldset>
                                <?php echo $formatQuestions; ?>
                                <input class="submit btn btn-success" type="submit" value="Continue"/>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- FOOTER -->
    <?php echo $pageController->printFooterLinks(); ?>
    </div>
<?php echo $pageController->printFooter(); ?>