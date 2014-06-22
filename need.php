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
$categoryDetails = $category->getCategory($placeName);
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
    $submit = Config::$site_url.'placeRequest?place='.$placeName.'&amp;category='.$categoryName.'&amp;section='.$sectionName.'&amp;contractor='.$contractorName;
} else {
    $back = Config::$site_url.$placeName.'/category/'.$categoryName;
    $submit = Config::$site_url.'placeRequest?place='.$placeName.'&amp;category='.$categoryName.'&amp;section='.$sectionName;
}
$formatQuestions = $pageController->formatQuestions($placeName,$categoryName,$sectionName);
if (!$formatQuestions) {
    header( 'Location: '.$submit);
    exit;
}
$metaDetails = $section->getMeta($contractorTitle);
$metaDetails['geo'] = $placeDetails['place_geo'];
$metaDetails['geo_placename'] = $placeDetails['place_geo_placename'];
echo $pageController->printHeader($metaDetails,true);
?>
<?php echo $pageController->printNavigation(); ?>
<div class="main-content-container clearfix">
    <div class="header">
        <?php echo $pageController->printUserStepsText(2); ?>
        <div class="back-button clearfix">
            <a class="button orange small rounded" title="Back" href="<?php echo $back ?>">
                Back
            </a>
        </div>
    </div>
    <div class="content">
        <?php
        if ($contractorDetails) { ?>
        <div class="contractor clearfix">
            <?php echo $pageController->getContractorDetails($contractorDetails); ?>   
        </div>
        <h2>Request a Quote: <?php echo $sectionDetails['section_title']?> for <?php echo $placeDetails['place_title']; ?></h2>

        <?php } else { ?>
        <h2>Submit and Get Matched to Prescreened <?php echo $sectionDetails['section_title']?> for <?php echo $placeDetails['place_title']; ?></h2>
        <?php } ?>
        <form id="questionForm" action="<?php echo $submit ?>" method="post">
            <fieldset>
                <ol>
                    <?php echo $formatQuestions; ?>
                </ol>
                <div class="clear"></div>
                <input class="submit button blue small" type="submit" value="Continue"/>
            </fieldset>
        </form>
    </div>
    <?php echo $pageController->printFooterLinks(); ?>
</div>
<div class="clear"></div>
</div>
<?php echo $pageController->printFooter(); ?>