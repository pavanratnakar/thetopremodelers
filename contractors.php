<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
if (!$_GET['place'] || !$_GET['category']) {
    header( 'Location: '.Config::$site_url.'index.php');
    exit;
}
include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
include_once(Config::$site_path.'controller/pageController.php');
$pageController=new PageController(8);
$placeName = $pageController->getUtils()->checkValues($_GET['place']);
$place = $pageController->getPlace();
$placeDetails = $place->getPlaceDetails($placeName);
$categoryName = $pageController->getUtils()->checkValues($_GET['category']);
$category = $pageController->getCategory();
$categoryDetails = $category->getCategory($categoryName);
$section = $pageController->getSection($categoryName,$placeName);
if (!$_GET['section']) {
    $getAllSections =  $section->getSections();
    $sectionName = $getAllSections[0]['section_name'];
} else {
    $sectionName = $pageController->getUtils()->checkValues($_GET['section']);
}
$sectionDetails = $section->getSectionDetails($sectionName);
$contractor = $pageController->getContractor();
$sort = $pageController->getUtils()->checkValues($_GET['sort']);
$page = $pageController->getUtils()->checkValues($_GET['page']) ? $pageController->getUtils()->checkValues($_GET['page']) : 1;
$contractorDetails = $contractor->getContractors($placeName,$categoryName,$sectionName,$sort,$page);
$allContractorDetails = $contractor->getAllContractors($placeName,$categoryName,$sectionName);
if (sizeof($contractorDetails) == 0) {
    header( 'Location: '.Config::$site_url.'404.php');
    exit;
}
$avoidCrawl = false;
// if ($placeDetails['place_id'] != 2 && $placeDetails['place_id'] != 1 && $placeDetails['place_id'] != 5 && $placeDetails['place_id'] != 36 && $placeDetails['place_id'] != 38 && $placeDetails['place_id'] != 45) {
//     $avoidCrawl = true;
// }
echo $pageController->printHeader($contractor->getContractorsMeta($contractorDetails),$avoidCrawl);
?>
<?php echo $pageController->printHeaderMenu(); ?>
        <div class="container-fluid">
            <div class="row main-container">
                <div class="col-md-3 col-xs-3 col-sm-3 sidebar">
                    <?php echo $pageController->printLogoContainer(); ?>
                    <div class="sidebar-container certifed-container">
                        <img src="<?php echo Config::$site_url ?>images/home/stamp_final.png" alt=""/>
                    </div>
                    <ul class="nav nav-sidebar">
                        <?php echo $pageController->getFormatedCategories(1); ?>
                        <?php echo $pageController->getFormatedCategories(2); ?>
                    </ul>
                    <div class="sidebar-container service-container">
                        <img src="<?php echo Config::$site_url ?>images/home/service.jpg" alt=""/>
                        <h4 class="gold">Need immediate service</h4>
                        <h5>CALL US : 1(214)303 9771</h5>
                    </div>
                </div>
                <div class="col-md-9 col-xs-9 col-sm-9 main top">
                    <h2><?php echo $categoryDetails[0]['category_title'].' in '.$placeDetails['place_title'].' with '.$sectionDetails['section_title'].' speciality'?></h2>
                    <div class="row">
                        <div class="col-md-8 col-xs-8 col-sm-8">
                            <ul class="nobullet contractors-list input-group">
<!--                                 <li class="option row">
                                    <form method="post" class="find-contractors" action="#">
                                        <div class="col-md-10 col-xs-10 col-sm-10" >
                                            <select class="place_select form-control" name="place_select">
                                                <?php
                                                $places = $place->getPlaces();
                                                echo '<option value="">Choose Place</option>';
                                                foreach ($places as $key => $value) {
                                                    echo '<option '.($value['place_name']==$placeName ? 'selected="selected"' : "").'value="'.$value['place_name'].'">'.$value['place_title'].'</option>';
                                                }
                                                ?>
                                            </select>
                                            <select class="category_select form-control" name="category_select">
                                              <?php
                                              echo '<option value="">Choose Category</option>';
                                              foreach ($categoryDetails as $key => $value) {
                                                echo '<option '.($value['category_name']==$categoryName ? 'selected="selected"' : "").'value="'.$value['category_name'].'">'.$value['category_title'].'</option>';
                                            }
                                            ?>
                                            </select>
                                            <select class="section_select form-control" name="section_select">
                                              <?php
                                              echo '<option value="">Choose Task</option>';
                                              foreach ($section->getSections() as $key => $value) {
                                                echo '<option '.($value['section_name']==$sectionName ? 'selected="selected"' : "").'value="'.$value['section_name'].'">'.$value['section_title'].'</option>';
                                            }
                                            ?>
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-xs-2 col-sm-2">
                                            <button class="btn btn-success submit " type="submit">Select</button>
                                        </div>
                                    </form>
                                </li> -->
                                <?php if ($contractorDetails) { ?>
                                <li class="option row">
                                    <div class="col-md-8 col-xs-8 col-sm-8">
                                        <form class="contractorsSort" method="get" action="#">
                                            <fieldset>
                                                <div class="input-group">
                                                    <select class="form-control" name="sort">
                                                        <option value="average_score">Ratings : Highest to Lowest</option>
                                                        <option <?php echo ($sort=="review_count") ? 'selected="selected"' : ''?> value="review_count">Ratings : Most to Least</option>
                                                    </select>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-sm-4">
                                        <?php $pageController->getUtils()->paginate($allContractorDetails['total_count'],Config::$paginationLimit,$page) ?>
                                    </div>
                                </li>
                            <?php
                            $i = 0;
                            foreach($contractorDetails as $key => $value) {
                                ?>
                                <li class="<?php echo ($i%2 == 0) ? 'even' : 'odd';?>" >
                                    <h3><a href="<?php echo Config::$site_url.'contractor/'. $value['contractor_name']?>"><?php echo $value['contractor_title'] ?></a></h3>
                                    <div class="entry-body">
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4 col-sm-4">
                                                <?php if ($value['image_id']) { ?>
                                                <div class="entry-image">
                                                    <a title="<?php echo $value['contractor_title'] ?>" href="<?php echo Config::$site_url.'contractor/'. $value['contractor_name']?>">
                                                        <img alt="<?php echo $value['contractor_title'] ?>" src="<?php echo Config::$site_url ?>/images/contractors/<?php echo $value['image_id']?>.jpg" />
                                                    </a>
                                                </div>
                                                <?php } ?>
                                            </div>
                                            <div class="col-md-3 col-xs-3 col-sm-3">
                                                <div class="reviews">
                                                    <?php
                                                    if ($value['average_score']) {
                                                        echo '<p><i class="rating-static rating-'.($value['average_score']*10).'"></i></p>';
                                                        echo '<p><span class="rating-score">'.$value['average_score'].'</span></p>';
                                                    }
                                                    if ($value['review_count']) {
                                                        echo '<p>'.$value['review_count'].' Reviews</p>';
                                                        echo '<p><a href="'.Config::$site_url.'contractor/'. $value['contractor_name'].'#ratings-reviews" title="See all reviews">See all reviews</a></p>';
                                                    }
                                                    if (!$value['average_score'] && !$value['review_count']) {
                                                        echo '<p><i>Yet to be rated</i></p>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <div class="col-md-5 col-xs-5 col-sm-5">
                                                <div class="contact-details">
                                                <?php
                                                if ($value['contractor_phone']) {
                                                    echo '<span class="telephone">'.$value['contractor_phone'].'</span>';
                                                }
                                                if ($value['contractor_address']) {
                                                    echo '<div class="address">'.$value['contractor_address'].'</div>';
                                                }
                                                ?>
                                                </div>
                                                <a href="<?php echo Config::$site_url.$placeName.'/'.$categoryName.'/'.$sectionName.'/'.$value['contractor_name'].'/need' ?>" class="get-quote btn btn-info">Get a Quote</a>
                                            </div>
                                        </div>
                                        <div class="row more-details">
                                            <div class="col-md-12 col-xs-12 col-sm-12">
                                                <div class="description">
                                                    <p><?php echo $value['contractor_description'] ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php
                            $i++;
                            }
                            ?>
                                <li class="last-child option row">
                                    <div class="col-md-8 col-xs-8 col-sm-8">
                                        <form class="contractorsSort" method="get" action="#">
                                            <fieldset>
                                                <div class="input-group">
                                                    <select class="form-control" name="sort">
                                                        <option value="average_score">Ratings : Highest to Lowest</option>
                                                        <option <?php echo ($sort=="review_count") ? 'selected="selected"' : ''?> value="review_count">Ratings : Most to Least</option>
                                                    </select>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-sm-4">
                                        <?php $pageController->getUtils()->paginate($allContractorDetails['total_count'],Config::$paginationLimit,$page) ?>
                                    </div>
                                </li>
                                <?php } else { ?>
                                <li class="last-child options">
                                    <p class="not-found">We dont have contractors for selected <b><?php echo $placeDetails['place_title']?></b> place under <b><?php echo $categoryDetails[0]['category_title']?></b> category working on <b><?php echo $sectionDetails['section_title']?></b> task.</p>
                                </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="col-md-4 col-xs-4 col-sm-4">
                            <div class="sb-container">
                                <div class="sb-header">
                                    <h3>Today&rsquo;s Best Offers</h3>
                                </div>
                                <div class="sb-content">
                                    <img src="<?php echo Config::$site_url.'images/global/sidebar/solar_system.png' ?>" title="Hire our pros and win 6000 watt solar system" alt="Hire our pros and win 6000 watt solar system" />
                                </div>
                            </div>
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
        </div>
        <!-- FOOTER -->
    <?php echo $pageController->printFooterLinks(); ?>
    </div>
<?php echo $pageController->printFooter(); ?>