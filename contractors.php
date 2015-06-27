<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
if (!$_GET['place'] || !$_GET['category']) {
    header( 'Location: '.Config::$site_url.'index.php');
    exit;
}
include_once(Config::$site_path.'controller/pageController.php');
$pageController=new PageController(8);
$placeName = $pageController->getUtils()->checkValues($_GET['place']);
$place = $pageController->getPlace();
$placeDetails = $place->getPlaceDetails($placeName);
$categoryName = $pageController->getUtils()->checkValues($_GET['category']);
$category = $pageController->getCategory();
$categoryDetails = $category->getCategory($categoryName);
$section = $pageController->getSection($categoryName, $placeName);
$contactUs = 'placeRequest?place='.$placeName.'&category='.$categoryDetails[0]['category_name'];
if (!$_GET['section']) {
    $getAllSections =  $section->getSections();
    $sectionName = $getAllSections[0]['section_name'];
    $sectionId = $getAllSections[0]['section_id'];
    $background_id = $getAllSections[0]['background_id'];
} else {
    $sectionName = $pageController->getUtils()->checkValues($_GET['section']);
    $sectionDetails = $section->getSectionDetails($sectionName);
    $contactUs .= '$section='.$sectionDetails['section_name'];
    $background_id = $sectionDetails['background_id'];
}
$contractor = $pageController->getContractor();
$sort = $pageController->getUtils()->checkValues($_GET['sort']);
$page = $pageController->getUtils()->checkValues($_GET['page']) ? $pageController->getUtils()->checkValues($_GET['page']) : 1;
$contractorDetails = $contractor->getContractors($placeName,$sectionName,$sort,$page);
$allContractorDetails = $contractor->getAllContractors($placeName,$sectionName);
if (sizeof($contractorDetails) == 0) {
    header( 'Location: '.Config::$site_url.'contact-us');
    exit;
}
$meta = $contractor->getContractorsMeta($contractorDetails, $categoryDetails[0]['category_title'], $sectionDetails ? $sectionDetails['section_title'] : '');
// if ($sectionId) {
    $getMetaData = $section->getMetaData($categoryDetails[0]['category_id'], $placeDetails['place_id']);
    if ($getMetaData) {
        foreach (array('title', 'description', 'keywords') as $m) {
            if ($getMetaData[$m]) {
                $meta[$m] = $getMetaData[$m];
            }
        }
    }
// }
$avoidCrawl = false;
$promotion = '';
if ($categoryDetails[0]['category_title'] === 'Roofing Contractors') {
    $promotion = 'Get multiple free roofing quotes';
} else {
    $promotion = 'Get multiple free quotes';
}
// if ($placeDetails['place_id'] != 2 && $placeDetails['place_id'] != 1 && $placeDetails['place_id'] != 5 && $placeDetails['place_id'] != 36 && $placeDetails['place_id'] != 38 && $placeDetails['place_id'] != 45) {
//     $avoidCrawl = true;
// }
echo $pageController->minifyHTML($pageController->printHeader($meta, $avoidCrawl, 1, $background_id).$pageController->printHeaderMenu().
    '<div class="container-fluid">
        <div class="row main-container">
            <div class="col-md-3 col-xs-12 col-sm-3 sidebar">
                '.$pageController->printLogoContainer().'');
                if (!$pageController->isMobile()) {
                echo $pageController->minifyHTML('
                <div class="sidebar-container certifed-container hidden-xs">
                    <img src="'.Config::$site_url.'images/home/stamp_final.png" data-src="'.Config::$site_url.'images/home/stamp_final.png" alt=""/>
                </div>');
                }
                echo $pageController->minifyHTML('
                <ul class="nav nav-sidebar hidden-xs">
                    '.$pageController->getFormatedCategories(1, $placeName).'
                    '.$pageController->getFormatedCategories(2, $placeName).'
                </ul>');
                if (!$pageController->isMobile()) {
                echo $pageController->minifyHTML('
                <div class="sidebar-container service-container hidden-xs">
                    <img src="'.Config::$site_url.'images/home/service.jpg" data-src="'.Config::$site_url.'images/home/service.jpg" alt=""/>
                    <h4 class="gold">Need immediate service</h4>
                    <h5>CALL US : 1(214)303 9771</h5>
                </div>');
                }
                echo $pageController->minifyHTML('
            </div>
            <div class="col-md-9 col-xs-12 col-sm-9 main no-bg">
                <div class="header-content">
                    <h1>'.$promotion.'</h1>
                    <h2>Immediate service 24/7 call 1(214)303 9771</h2>
                    <a class="btn btn-orange" href="'.Config::$site_url.$contactUs.'">Get Quotes</a>
                </div>
                <div class="bottom">
                    <h2>'.$categoryDetails[0]['category_title'].' in '.$placeDetails['place_title'].($sectionDetails ? ' with '.$sectionDetails['section_title'].' speciality' : '').'</h2>
                    <div class="row">
                        <div class="col-md-8 col-xs-12 col-sm-8">
                            <ul class="nobullet contractors-list input-group">
                               <li class="option row" style="display:none;">
                                    <form method="post" class="find-contractors" action="#">
                                        <div class="col-md-10 col-xs-10 col-sm-10" >
                                            <select class="place_select form-control" name="place_select">');
                                                $output = '';
                                                $places = $place->getPlaces();
                                                $output .= '<option value="">Choose Place</option>';
                                                foreach ($places as $key => $value) {
                                                    $output .= '<option '.($value['place_name']==$placeName ? "selected='selected'" : "").' value="'.$value['place_name'].'">'.$value['place_title'].'</option>';
                                                }
                                                echo $pageController->minifyHTML($output.'
                                            </select>
                                            <select class="category_select form-control" name="category_select">');
                                                $output = '';
                                                $output .= '<option value="">Choose Category</option>';
                                                foreach ($categoryDetails as $key => $value) {
                                                    $output .= '<option '.($value['category_name']==$categoryName ? "selected='selected'" : "").' value="'.$value['category_name'].'">'.$value['category_title'].'</option>';
                                                }
                                                echo $pageController->minifyHTML($output.'
                                            </select>
                                            <select class="section_select form-control" name="section_select">');
                                                $output = '';
                                                $output .= '<option value="">Choose Task</option>';
                                                foreach ($section->getSections() as $key => $value) {
                                                    $output .= '<option '.($value['section_name']==$sectionName ? "selected='selected'" : "").' value="'.$value['section_name'].'">'.$value['section_title'].'</option>';
                                                }
                                                echo $pageController->minifyHTML($output.'
                                            </select>
                                        </div>
                                        <div class="col-md-2 col-xs-2 col-sm-2">
                                            <button class="btn btn-success submit " type="submit">Select</button>
                                        </div>
                                    </form>
                                </li>');
                                if ($contractorDetails) {
                                echo $pageController->minifyHTML('
                                <li class="option row">
                                    <div class="col-md-8 col-xs-8 col-sm-8">
                                        <form class="contractorsSort" method="get" action="#">
                                            <fieldset>
                                                <div class="input-group">
                                                    <select class="form-control" name="sort">
                                                        <option value="average_score">Ratings : Highest to Lowest</option>
                                                        <option '.($sort=="review_count" ? "selected=selected" : "").' value="review_count">Ratings : Most to Least</option>
                                                    </select>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-sm-4">
                                        '.$pageController->getUtils()->paginate($allContractorDetails['total_count'],Config::$paginationLimit,$page).'
                                    </div>
                                </li>');
                            $i = 0;
                            foreach($contractorDetails as $key => $value) {
                                echo $pageController->minifyHTML('
                                <li class="'.($i%2 == 0 ? "even" : "odd").'" >
                                    <h3><a href="'.Config::$site_url.'contractor/'.$value['contractor_name'].'">'.$value['contractor_title'].'</a></h3>
                                    <div class="entry-body">
                                        <div class="row">
                                            <div class="col-md-4 col-xs-4 col-sm-4">
                                                <div class="entry-image">
                                                    <a title="'.$value['contractor_title'].'" href="'.Config::$site_url.'contractor/'. $value['contractor_name'].'">
                                                        <img alt="'.$value['contractor_title'].'" src="'.Config::$site_url.'images/contractors/roof_0.jpg" data-src="'.Config::$site_url.'images/contractors/'.($value['image_id'] ? $value['image_id'] : 'roof_0').'.jpg" />
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-xs-3 col-sm-3">
                                                <div class="reviews">');
                                                    $output = '';
                                                    if ($value['average_score']) {
                                                        $output .= '<p><i class="rating-static rating-'.($value['average_score']*10).'"></i></p>';
                                                        $output .= '<p><span class="rating-score">'.$value['average_score'].'</span></p>';
                                                    }
                                                    if ($value['review_count']) {
                                                        $output .= '<p>'.$value['review_count'].' Reviews</p>';
                                                        $output .= '<p><a href="'.Config::$site_url.'contractor/'. $value['contractor_name'].'#ratings-reviews" title="See all reviews">See all reviews</a></p>';
                                                    }
                                                    if (!$value['average_score'] && !$value['review_count']) {
                                                        $output .= '<p><i>Yet to be rated</i></p>';
                                                    }
                                                    echo $pageController->minifyHTML($output.'
                                                </div>
                                            </div>
                                            <div class="col-md-5 col-xs-5 col-sm-5">
                                                <div class="contact-details">');
                                                if ($value['contractor_phone']) {
                                                    echo $pageController->minifyHTML('<span class="telephone">'.$value['contractor_phone'].'</span>');
                                                }
                                                if ($value['contractor_address']) {
                                                    echo $pageController->minifyHTML('<div class="address">'.$value['contractor_address'].'</div>');
                                                }
                                                echo $pageController->minifyHTML('
                                                </div>
                                                <a href="'.Config::$site_url.$placeName.'/'.$categoryName.'/'.$sectionName.'/'.$value['contractor_name'].'/need" class="get-quote btn btn-info">Get a Quote</a>
                                                <p class="serving-details">Serving '.$placeDetails['place_title'].'</p>
                                            </div>
                                        </div>
                                        <div class="row more-details">
                                            <div class="col-md-12 col-xs-12 col-sm-12">
                                                <div class="description">
                                                    <p>'.$value['contractor_description'].'</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>');
                            $i++;
                            }
                            echo $pageController->minifyHTML('
                                <li class="last-child option row">
                                    <div class="col-md-8 col-xs-8 col-sm-8">
                                        <form class="contractorsSort" method="get" action="#">
                                            <fieldset>
                                                <div class="input-group">
                                                    <select class="form-control" name="sort">
                                                        <option value="average_score">Ratings : Highest to Lowest</option>
                                                        <option '.($sort=="review_count" ? "selected=selected" : "").' value="review_count">Ratings : Most to Least</option>
                                                    </select>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-sm-4">
                                        '.$pageController->getUtils()->paginate($allContractorDetails['total_count'], Config::$paginationLimit, $page).'
                                    </div>
                                </li>');
                                } else {
                                echo $pageController->minifyHTML('
                                <li class="last-child options">
                                    <p class="not-found">We dont have contractors for selected <b>'.$placeDetails['place_title'].'</b> place under <b>'.$categoryDetails[0]['category_title'].'</b>'.$sectionDetails ? ' category working on <b>'.$sectionDetails['section_title'].'</b> task' : ''.'.</p>
                                </li>');
                                }
                                echo $pageController->minifyHTML('
                            </ul>
                        </div>');
                        if (!$pageController->isMobile()) {
                        echo $pageController->minifyHTML('
                        <div class="col-md-4 hidden-xs col-sm-4">
                            <div class="sb-container">
                                <div class="sb-header">
                                    <h3 class="texas_green">Texas is going green</h3>
                                    <p>Read more about our green project</p>
                                </div>
                                <div class="sb-content">
                                    <img src="'.Config::$site_url.'images/global/sidebar/solar_system.jpg" data-src="'.Config::$site_url.'images/global/sidebar/solar_system.jpg" title="" />
                                </div>
                            </div>
                            '.$pageController->getArticles($categoryDetails[0]['category_id']).'
                        </div>');
                        }
                        echo $pageController->minifyHTML('
                    </div>
                </div>
            </div>
        </div>
        <!-- FOOTER -->
        '.$pageController->printFooterLinks().'
    </div>'.$pageController->printFooter()); ?>