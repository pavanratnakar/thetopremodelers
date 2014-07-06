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
echo $pageController->printHomeHeader($contractor->getMeta($contractorDetails));
?>
<?php echo $pageController->printHeaderMenu(); ?>
        <div class="container">
            <div class="row main-container">
                <div class="col-md-3 col-xs-3 col-sm-3 sidebar">
                    <?php echo $pageController->printLogoContainer(); ?>
                </div>
                <div class="container full-main">
                    <div class="col-md-9 col-xs-9 col-sm-9">
                        <div id="top" class="sub top clearfix">
                            <?php echo $pageController->getContractorDetails($contractorDetails,true); ?>
                        </div>
                        <?php echo $pageController->jumpList($jumpListData,'profile'); ?>
                        <div id="profile" class="sub">
                            <h2>Profile</h2>
                            <h3><?php echo $contractorDetails['contractor_title']?></h3>
                            <?php if ($contractorDetails['contractor_description']) { ?>
                            <p><?php echo $contractorDetails['contractor_description'] ?></p>
                            <?php } ?>
                            <div id="service-container" class="sub">
                                <h4>Services Offered</h4>
                                <?php
                                $contractorCategoryDetails = $contractor->getSectionsForContractor(array('contractor_id'=>$contractorDetails['contractor_id']));
                                $category = '';
                                foreach($contractorCategoryDetails as $key=>$value) {
                                    if ($category != $value['category_title']) {
                                        echo '<h5>'.$value['category_title'].'</h5>';
                                        if ($category) echo '</ul>';
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
                        <?php
                        if ($contractorRatingDetails) {
                            echo $pageController->jumpList($jumpListData,'ratings-reviews');
                            ?>
                            <div id="ratings-reviews" class="sub">
                                <h2>Ratings &amp; Reviews</h2>
                                <h3><?php echo $contractorDetails['contractor_title']?></h3>
                                <ul>
                                    <li>
                                        <div class="showing-details">Showing 1 - <?php echo sizeof($contractorRatingDetails)?></div>
                                    </li>
                                    <?php
                                    foreach($contractorRatingDetails as $key=>$value) { ?>
                                    <li>
                                        <div class="rating">
                                            <?php
                                            if ($value['score']) {
                                                echo '
                                                <div class="clearfix">
                                                <div class="rating-image left">
                                                <i class="rating-static rating-'.($value['score']*10).'"></i>
                                                </div>
                                                <div class="rating-text left">
                                                <span class="rating-score">'.$value['score'].'</span>
                                                </div>
                                                <div class="rating-date">
                                                | <span>'.$value['timestamp'].'</span>
                                                </div>
                                                </div>
                                                ';
                                            }
                                            if (!$value['score'] && !$value['review_count']) {
                                                echo '<div><i>Yet to be rated</i></div>';
                                            }
                                            ?>
                                        </div>
                                        <div class="details">
                                            <div class="rating-person">
                                                <?php echo 'Review by '.$value['person'].' in '.$value['place_title'].'' ?>
                                            </div>
                                            <div class="project-details">
                                                <?php echo 'Project :  '.$value['project'].'' ?>
                                            </div>
                                        </div>
                                        <?php if ($value['review']) {?>
                                        <div class="review">
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
                    <div class="col-md-3 col-xs-3 col-sm-3">
                        <div class="sidebar-container" id="certified-rating">
                            <div class="sidebar-content">
                                <img src="<?php echo Config::$site_url.'images/contractor/stamp_final.png' ?>" title="Certified Ratings" alt="Certified Ratings" />
                                <h4>Hire with Confidence</h4>
                                <p>This service provider has passed our screening process including checks for criminal background and bankruptcy.</p>
                            </div>
                        </div>
                        <div class="sidebar-container">
                            <div class="sidebar-header">
                                <h3>Today&rsquo;s Best Offers</h3>
                            </div>
                            <div class="sidebar-content">
                                <img src="<?php echo Config::$site_url.'images/global/sidebar/solar_system.png' ?>" title="Hire our pros and win 6000 watt solar system" alt="Hire our pros and win 6000 watt solar system" />
                            </div>
                        </div>
                        <?php
                        $contractorRatingDistribution = $contractor->getRatingDistributionForContractor(array('contractor_id'=>$contractorDetails['contractor_id']));
                        if ($contractorRatingDistribution) {?>
                        <div class="sidebar-container" id="rating-distribution">
                            <div class="sidebar-header">
                                <h3>Rating Distribution</h3>
                            </div>
                            <div class="sidebar-content">
                                <?php
                                for ($i=5;$i>0;$i--) {
                                    if ($contractorRatingDistribution[$i]) {
                                        $barPercentage = ($contractorRatingDistribution[$i]*100)/sizeof($contractorRatingDetails);
                                    } else {
                                        $barPercentage = 0;
                                    }
                                    echo '
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4 col-sm-4">
                                                <span>'.$i.' Stars</span>
                                            </div>
                                            <div class="col-md-8 col-xs-8 col-sm-8">
                                                <div class="horizontal-bar" style="width:'.$barPercentage.'%;"></div>
                                            </div>
                                        </div>
                                    ';
                                }
                                ?>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="sidebar-container">
                            <div class="sidebar-header">
                                <h3>Roofing Library</h3>
                            </div>
                            <div class="sidebar-content">
                                <?php echo $pageController->getArticles(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FOOTER -->
    <?php echo $pageController->printHomeFooterLinks(); ?>
    </div>
<?php echo $pageController->printHomeFooter(); ?>