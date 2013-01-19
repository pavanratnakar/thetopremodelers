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
<?php echo $pageController->printNavigation(); ?>
<div class="main-content-container clearfix">
    <div class="content clearfix">
        <div class="main left">
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
        <div class="sidebar right">
            <div class="sidebar-container noBorder" id="certified-rating">
                <div class="sidebar-content image">
                    <img src="<?php echo Config::$site_url.'images/contractor/stamp_final.png' ?>" title="Certified Ratings" alt="Certified Ratings" />
                    <h4>Hire with Confidence</h4>
                    <p>This service provider has passed our screening process including checks for criminal background and bankruptcy.</p>
                </div>
            </div>
            <div class="sidebar-container">
                <div class="sidebar-header">
                    <h3>Today&rsquo;s Best Offers</h3>
                </div>
                <div class="sidebar-content image">
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
                    <ul>
                        <?php
                        for ($i=5;$i>0;$i--) {
                            if ($contractorRatingDistribution[$i]) {
                                $barPercentage = ($contractorRatingDistribution[$i]*100)/sizeof($contractorRatingDetails);
                            } else {
                                $barPercentage = 0;
                            }
                            echo '
                            <li class="clearfix">
                            <h6 class="left">'.$i.' Stars</h6>
                            <div class="left horizontal-bar-container">
                            <div class="horizontal-bar" style="width:'.$barPercentage.'%;"></div>
                            </div>
                            </li>
                            ';
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <?php } ?>
            <div class="sidebar-container">
                <div class="sidebar-header">
                    <h3>Roofing Library</h3>
                </div>
                <div class="sidebar-content">
                    <ul>
                        <li class="first-child">
                            <h4>A roofing contractor</h4>
                            <ul class="clearfix">
                                <li><a href="<?php echo Config::$site_url.'article/having_right_contract' ?>" title="Have the right contract">Have the right contract</a></li>
                                <li><a href="<?php echo Config::$site_url.'article/roofings_warranty' ?>" title="Choosing your roofing warranty">Choosing your roofing warranty</a></li>
                            </ul>
                        </li>
                        <li>
                            <h4>Your choices of flat roofs</h4>
                            <ul class="clearfix">
                                <li><a href="<?php echo Config::$site_url.'article/tpo_and_pvc_roofs' ?>" title="Tpo and pvc roofs">Tpo and pvc roofs</a></li>
                                <li><a href="<?php echo Config::$site_url.'article/rubber_membrane_usage' ?>" title="Rubber membrane">Rubber membrane</a></li>
                                <li><a href="<?php echo Config::$site_url.'article/built_up_flat_roofs' ?>" title="Rubber membrane">Built up Flat Roofs</a></li>
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
                            </ul>
                        </li>
                        <li>
                            <h4>Your Insurance</h4>
                            <ul class="clearfix">
                                <li><a href="<?php echo Config::$site_url.'article/dallas_under_dangerous_threat_hailstorm' ?>" title="Dallas under the dangerous threat of Hailstorm in June 2012">Dallas under the dangerous threat of Hailstorm in June 2012</a></li>
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