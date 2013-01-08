<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
    if(!$_GET['category'] || !$_GET['section']){
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
    $formatQuestions = $pageController->formatQuestions($placeName,$categoryName,$sectionName);
    if (!$formatQuestions) {
        header( 'Location: '.Config::$site_url.'404.php');
        exit;
    }
    echo $pageController->printHeader($section->getMeta($sectionName));
?>
            <div class="main-content-container clearfix">
                <div class="header">
                    <?php echo $pageController->printUserStepsText(2); ?>
                    <div class="back-button clearfix">
                        <a class="clearfix" title="Back" href="<?php echo Config::$site_url.$placeName ?>/category/<?php echo $categoryName ?>">
                            <img width="104" height="34" src="<?php echo Config::$site_url.'/images/global/buttons/back-button.jpg' ?>" title="Back" alt="Back"/>
                        </a>
                    </div>
                </div>
                <div class="content">
                    <h2>Submit and Get Matched to Prescreened <?php echo $sectionDetails['section_title']?> under <?php echo $categoryDetails[0]['category_title'];?> for <?php echo $placeDetails['place_title']; ?></h2>
                    <form id="questionForm" action="<?php echo Config::$site_url ?><?php echo $placeName ?>/<?php echo $categoryName ?>/<?php echo $sectionName ?>/placeRequest" method="post">
                        <fieldset>
                            <ol>
                                <?php echo $formatQuestions; ?>
                            </ol>
                        </fieldset>
                        <input class="submit" type="submit" value="Continue"/>
                     </form>
                 </div>
                <?php echo $pageController->printFooterLinks(); ?>
            </div>
            <div class="clear"></div>
        </div>
<?php echo $pageController->printFooter(); ?>