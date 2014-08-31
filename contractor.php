<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
if (!$_GET['name']) {
    header( 'Location: '.Config::$site_url.'index.php');
    exit;
}
include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
include_once(Config::$site_path.'controller/pageController.php');
$pageController=new PageController(10);
$contractorName = $pageController->getUtils()->checkValues($_GET['name']);
$contractor = $pageController->getContractor();
$contractorDetails = $contractor->getContractor($contractorName);
if (!$contractorDetails){
    header( 'Location: '.Config::$site_url.'404.php');
    exit;
}
$contractorRatingDetails = $contractor->getRatingForContractor(array('contractor_id'=>$contractorDetails['contractor_id']));
$jumpListData['profile'] = array('title'=>'Profile');
if ($contractorRatingDetails) {
    $jumpListData['ratings-reviews'] = array('title'=>'Ratings & Reviews');
}
echo $pageController->printHeader($contractor->getMeta($contractorDetails));
?>
<?php echo $pageController->printHeaderMenu(); ?>
    <div class="container-fluid">
        <div class="row main-container">
            <div class="col-md-3 col-xs-3 col-sm-3 sidebar">
                <?php echo $pageController->printLogoContainer(); ?>
            </div>
            <div class="regular-main">
                <div class="row">
                    <div class="col-md-8 col-xs-8 col-sm-8">
                        <div id="top" class="sub top">
                            <?php echo $pageController->getContractorDetails($contractorDetails,true); ?>
                        </div>
                        <div id="profile" class="sub">
                            <h3>Profile</h3>
                            <?php if ($contractorDetails['contractor_description']) { ?>
                            <p ><?php echo $contractorDetails['contractor_description'] ?></p>
                            <?php } ?>
                            <div id="service-container">
                                <h4>Services Offered</h4>
                                <?php
                                $contractorCategoryDetails = $contractor->getSectionsForContractor(array('contractor_id'=>$contractorDetails['contractor_id']));
                                $category = '';
                                foreach($contractorCategoryDetails as $key=>$value) {
                                    if ($category != $value['category_title']) {
                                        if ($category) echo '</ul>';
                                        echo '<h5>'.$value['category_title'].'</h5>';
                                        echo '<ul>';
                                        $category = $value['category_title'];
                                    }
                                    echo '<li>'.$value['section_title'].'</li>';
                                }
                                echo '</ul>';
                                ?>
                                <h4>Service Area</h4>
                                <ul>
                                    <?php
                                    $contractorPlaceDetails = $contractor->getPlacesForContractor(array('contractor_id'=>$contractorDetails['contractor_id']));
                                    foreach($contractorPlaceDetails as $key=>$value) {
                                        echo '<li>'.$value['place_title'].'</li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                        <?php if ($contractorRatingDetails) {?>
                        <div id="ratings-reviews" class="sub">
                            <h3>Ratings &amp; Reviews</h3>
                            <ul class="nobullet">
                                <?php
                                $i = 0;
                                $class = '';
                                foreach($contractorRatingDetails as $key=>$value) {
                                if ($i == (sizeof($contractorRatingDetails) - 1)) {
                                    $class = ' last-child';
                                }
                                $i++;
                                echo '<li class="review'.$class.'">';
                                ?>
                                    <div>
                                        <?php
                                        if ($value['score']) {
                                            echo '
                                            <div class="rating-image">
                                                <i class="rating-static rating-'.($value['score']*10).'"></i>
                                            </div>
                                            <div class="rating-time">
                                                <span>'.$value['timestamp'].'</span>
                                            </div>
                                            ';
                                        }
                                        if (!$value['score'] && !$value['review_count']) {
                                            echo '<div><i>Yet to be rated</i></div>';
                                        }
                                        ?>
                                    </div>
                                    <div class="details">
                                        <div class="rating-person bold">
                                            <?php echo 'Review by '.$value['person'].' in '.$value['place_title'].'' ?>
                                        </div>
                                        <div class="project-details bold">
                                            <?php echo 'Project :  '.$value['project'].'' ?>
                                        </div>
                                    </div>
                                    <?php if ($value['review']) {?>
                                    <div>
                                        <?php echo $value['review'] ?>
                                    </div>
                                    <?php
                                }
                                echo '</li>';
                                }
                                ?>
                            </ul>
                        </div>
                        <?php } ?>
                        <?php echo $pageController->jumpList($jumpListData,'top'); ?>
                    </div>
                    <div class="col-md-4 col-xs-4 col-sm-4">
                        <div class="sb-container sb-center" id="certified-rating">
                            <div class="sb-content">
                                <img src="<?php echo Config::$site_url.'images/contractor/stamp_final.png' ?>" title="Certified Ratings" alt="Certified Ratings" />
                                <h4>Hire with Confidence</h4>
                                <p>This service provider has passed our screening process including checks for criminal background and bankruptcy.</p>
                            </div>
                        </div>
                        <div class="sb-container">
                            <div class="sb-header">
                                <h3>Today&rsquo;s Best Offers</h3>
                            </div>
                            <div class="sb-content">
                                <img src="<?php echo Config::$site_url.'images/global/sidebar/solar_system.png' ?>" title="Hire our pros and win 6000 watt solar system" alt="Hire our pros and win 6000 watt solar system" />
                            </div>
                        </div>
                        <?php
                        $contractorRatingDistribution = $contractor->getRatingDistributionForContractor(array('contractor_id'=>$contractorDetails['contractor_id']));
                        if ($contractorRatingDistribution) {?>
                        <div class="sb-container" id="rating-distribution">
                            <div class="sb-header">
                                <h3>Rating Distribution</h3>
                            </div>
                            <div class="sb-content">
                                <?php
                                for ($i=5;$i>0;$i--) {
                                    if ($contractorRatingDistribution[$i]) {
                                        $barPercentage = ($contractorRatingDistribution[$i]*100)/sizeof($contractorRatingDetails);
                                    } else {
                                        $barPercentage = 0;
                                    }
                                    echo '
                                        <div class="row">
                                            <div class="col-md-3 col-xs-3 col-sm-3">
                                                <span>'.$i.' Stars</span>
                                            </div>
                                            <div class="col-md-9 col-xs-9 col-sm-9">
                                                <div class="horizontal-bar" style="width:'.$barPercentage.'%;"></div>
                                            </div>
                                        </div>
                                    ';
                                }
                                ?>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="sb-container">
                            <div class="sb-header">
                                <h3>Roofing Library</h3>
                            </div>
                            <div class="sb-content">
                                <?php echo $pageController->getArticles(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FOOTER -->
    <?php echo $pageController->printFooterLinks(); ?>
    </div>
<?php echo $pageController->printFooter(); ?>