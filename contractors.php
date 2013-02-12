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
$categoryDetails = $category->getCategory($placeName);
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
echo $pageController->printHeader($contractor->getContractorsMeta($contractorDetails));
?>
<?php echo $pageController->printNavigation(); ?>
<div class="main-content-container clearfix">
    <div class="content clearfix">
        <h2><?php echo $categoryDetails[0]['category_title'].' in '.$placeDetails['place_title'].' with '.$sectionDetails['section_title'].' speciality'?></h2>
        <div class="main left">
            <div class="sub top">
                <ul>
                    <li class="options clearfix">
                        <form method="post" class="find-contractors" action="#">
                            <div class="left select-options">
                                <select class="place_select" name="place_select">
                                    <?php
                                    $places = $place->getPlaces();
                                    echo '<option value="">Choose Place</option>';
                                    foreach ($places as $key => $value) {
                                        echo '<option '.($value['place_name']==$placeName ? 'selected="selected"' : "").'value="'.$value['place_name'].'">'.$value['place_title'].'</option>';
                                    }
                                    ?>
                                </select>
                                <select class="category_select" name="category_select">
                                  <?php
                                  echo '<option value="">Choose Category</option>';
                                  foreach ($categoryDetails as $key => $value) {
                                    echo '<option '.($value['category_name']==$categoryName ? 'selected="selected"' : "").'value="'.$value['category_name'].'">'.$value['category_title'].'</option>';
                                }
                                ?>
                            </select>
                            <select class="section_select" name="section_select">
                              <?php
                              echo '<option value="">Choose Task</option>';
                              foreach ($section->getSections() as $key => $value) {
                                echo '<option '.($value['section_name']==$sectionName ? 'selected="selected"' : "").'value="'.$value['section_name'].'">'.$value['section_title'].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="right">
                        <button class="button green rounded small submit " type="submit">Select</button>
                    </div>
                </form>
            </li>
            <?php if($contractorDetails) { ?>
            <li class="options clearfix">
                <div class="left">
                    <form class="contractorsSort" method="get" action="#">
                        <fieldset>
                            <select name="sort">
                                <option value="average_score">Ratings : Highest to Lowest</option>
                                <option <?php echo ($sort=="review_count") ? 'selected="selected"' : ''?> value="review_count">Ratings : Most to Least</option>
                            </select>
                        </fieldset>
                    </form>
                </div>
                <div class="right">
                    <?php $pageController->getUtils()->paginate($allContractorDetails['total_count'],Config::$paginationLimit,$page) ?>
                </div>
            </li>
            <?php
            $i = 0;
            foreach($contractorDetails as $key => $value) {
                ?>
                <li class="clearfix <?php echo ($i%2 == 0) ? 'even' : 'odd';?>" >
                    <h4 class="entry-title"><a href="<?php echo Config::$site_url.'contractor/'. $value['contractor_name']?>"><?php echo $value['contractor_title'] ?></a></h4>
                    <div class="entry-body">
                        <div class="left first-container">
                            <?php if ($value['image_id']) { ?>
                            <div class="entry-image">
                                <a title="<?php echo $value['contractor_title'] ?>" href="<?php echo Config::$site_url.'contractor/'. $value['contractor_name']?>">
                                    <img alt="<?php echo $value['contractor_title'] ?>" src="/images/contractors/<?php echo $value['image_id']?>.jpg" />
                                </a>
                            </div>
                            <?php } ?>
                            <div class="get-quote left">
                                <a href="<?php echo Config::$site_url.$placeName.'/'.$categoryName.'/'.$sectionName.'/'.$value['contractor_name'].'/need' ?>" class="small orange button">Get a Quote</a>
                            </div>
                        </div>
                        <div class="left contact-details">
                            <div class="ratings-reviews">
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
                            <?php
                            if ($value['contractor_phone']) {
                                echo '<span class="telephone">'.$value['contractor_phone'].'</span>';
                            }
                            if ($value['contractor_address']) {
                                echo '<div class="address">'.$value['contractor_address'].'</div>';
                            }
                            ?>
                        </div>
                        <div class="left description">
                            <p><?php echo $value['contractor_description'] ?></p>
                        </div>
                    </div>
                </li>
                <?php
                $i++; 
            }
            ?>
            <li class="last-child options clearfix">
                <div class="left">
                    <form action="#" class="contractorsSort" method="get">
                        <fieldset>
                            <select name="sort">
                                <option value="average_score">Ratings : Highest to Lowest</option>
                                <option <?php echo ($sort=="review_count") ? 'selected="selected"' : ''?> value="review_count">Ratings : Most to Least</option>
                            </select>
                        </fieldset>
                    </form>
                </div>
                <div class="right">
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
</div>
<div class="sidebar right">
    <div class="sidebar-container noBorder" id="certified-rating">
        <div class="sidebar-content image">
            <img src="<?php echo Config::$site_url.'images/contractor/stamp_final.png' ?>" title="Certified Ratings" alt="Certified Ratings" />
            <h4>Hire with Confidence</h4>
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
    <?php if ($placeName==='dallas_texas') { ?>
    <div class="sidebar-container">
        <div class="sidebar-header">
            <h3>Roofing Library</h3>
        </div>
        <div class="sidebar-content">
            <?php echo $pageController->getArticles(); ?>
        </div>
    </div>
    <?php } ?>
</div>
</div>
<?php echo $pageController->printFooterLinks(); ?>
</div>
<div class="clear"></div>
</div>
<?php echo $pageController->printFooter(); ?>