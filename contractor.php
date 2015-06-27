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
echo $pageController->minifyHTML($pageController->printHeader($contractor->getMeta($contractorDetails), false, 1, $contractorDetails->background_id).$pageController->printHeaderMenu().
    '<div class="container-fluid">
        <div class="row main-container">
            <div class="col-md-3 col-xs-12 col-sm-3 sidebar">
                '.$pageController->printLogoContainer().'
            </div>
            <div class="regular-main">
                <div class="row">
                    <div class="col-md-8 col-xs-12 col-sm-8">
                        <div id="top" class="sub top">
                            '.$pageController->getContractorDetails($contractorDetails, true).'
                        </div>
                        <div id="profile" class="sub">
                            <h3>Profile</h3>');
                            if ($contractorDetails['contractor_description']) {
                            echo $pageController->minifyHTML('
                            <p>'.$contractorDetails['contractor_description'].'</p>
                            ');
                            }
                            echo $pageController->minifyHTML('
                            <div id="service-container">
                                <h4>Services Offered</h4>');
                                $contractorCategoryDetails = $contractor->getSectionsForContractor(array('contractor_id'=>$contractorDetails['contractor_id']));
                                $category = '';
                                $output = '';
                                foreach($contractorCategoryDetails as $key=>$value) {
                                    if ($category != $value['category_title']) {
                                        if ($category) $output .= '</ul>';
                                        $output .= '<h5>'.$value['category_title'].'</h5>';
                                        $output .= '<ul>';
                                        $category = $value['category_title'];
                                    }
                                    $output .= '<li>'.$value['section_title'].'</li>';
                                }
                                $output .= '</ul>';
                                echo $pageController->minifyHTML($output.
                                '<h4>Service Area</h4>
                                <ul>');
                                    $contractorPlaceDetails = $contractor->getPlacesForContractor(array('contractor_id'=>$contractorDetails['contractor_id']));
                                    foreach($contractorPlaceDetails as $key=>$value) {
                                        echo $pageController->minifyHTML('<li>'.$value['place_title'].'</li>');
                                    }
                                echo $pageController->minifyHTML('
                                </ul>
                            </div>
                        </div>');
                        if ($contractorRatingDetails) {
                        echo $pageController->minifyHTML('
                        <div id="ratings-reviews" class="sub">
                            <h3>Ratings &amp; Reviews</h3>
                            <ul class="nobullet">');
                                $i = 0;
                                $class = '';
                                $output = '';
                                foreach($contractorRatingDetails as $key=>$value) {
                                if ($i == (sizeof($contractorRatingDetails) - 1)) {
                                    $class = ' last-child';
                                }
                                $i++;
                                $output .= '<li class="review'.$class.'">';
                                ?>
                                    <div>
                                        <?php
                                        if ($value['score']) {
                                            $output .= '
                                            <div class="rating-image">
                                                <i class="rating-static rating-'.($value['score']*10).'"></i>
                                            </div>
                                            <div class="rating-time">
                                                <span>'.$value['timestamp'].'</span>
                                            </div>
                                            ';
                                        }
                                        if (!$value['score'] && !$value['review_count']) {
                                            $output .= '<div><i>Yet to be rated</i></div>';
                                        }
                                        ?>
                                    </div>
                                    <div class="details">
                                        <div class="rating-person bold">
                                            <?php $output .= 'Review by '.$value['person'].' in '.$value['place_title'].'' ?>
                                        </div>
                                        <div class="project-details bold">
                                            <?php $output .= 'Project :  '.$value['project'].'' ?>
                                        </div>
                                    </div>
                                    <?php if ($value['review']) {?>
                                    <div>
                                        <?php $output .= $value['review'] ?>
                                    </div>
                                    <?php
                                }
                                $output .= '</li>';
                                }
                                echo $pageController->minifyHTML($output.'
                            </ul>
                        </div>');
                        }
                        echo $pageController->minifyHTML('
                        '.$pageController->jumpList($jumpListData,'top').'
                    </div>');
                    if (!$pageController->isMobile()) {
                    echo $pageController->minifyHTML('
                    <div class="col-md-4 hidden-xs col-sm-4">');
                        $contractorRatingDistribution = $contractor->getRatingDistributionForContractor(array('contractor_id'=>$contractorDetails['contractor_id']));
                        if ($contractorRatingDistribution) {
                        echo $pageController->minifyHTML('
                        <div class="sb-container" id="rating-distribution">
                            <div class="sb-header">
                                <h3>Rating Distribution</h3>
                            </div>
                            <div class="sb-content">');
                                for ($i=5;$i>0;$i--) {
                                    if ($contractorRatingDistribution[$i]) {
                                        $barPercentage = ($contractorRatingDistribution[$i]*100)/sizeof($contractorRatingDetails);
                                    } else {
                                        $barPercentage = 0;
                                    }
                                    echo $pageController->minifyHTML('
                                        <div class="row">
                                            <div class="col-md-3 col-xs-3 col-sm-3">
                                                <span>'.$i.' Stars</span>
                                            </div>
                                            <div class="col-md-9 col-xs-9 col-sm-9">
                                                <div class="horizontal-bar" style="width:'.$barPercentage.'%;"></div>
                                            </div>
                                        </div>
                                    ');
                                }
                            echo $pageController->minifyHTML('
                            </div>
                        </div>');
                        }
                        echo $pageController->minifyHTML('
                    </div>');
                    }
                    echo $pageController->minifyHTML('
                </div>
            </div>
        </div>
        <!-- FOOTER -->
    '.$pageController->printFooterLinks().'
    </div>'.$pageController->printFooter()); ?>