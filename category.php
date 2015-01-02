<?php
    if(!$_GET['category']){
        header( 'Location: index.php');
        exit;
    }
    include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
    include_once(Config::$site_path.'controller/pageController.php');
    $pageController=new PageController(2);
    $placeName = $pageController->getUtils()->checkValues($_GET['place']);
    $place = $pageController->getPlace();
    $placeDetails = $place->getPlaceDetails($placeName);
    $categoryName = $pageController->getUtils()->checkValues($_GET['category']);
    $category = $pageController->getCategory();
    $categoryDetails = $category->getCategory($placeName);
    $formatedSection = $pageController->getFormatedSections($categoryName,$placeName);
    if (!$formatedSection) {
        header( 'Location: '.Config::$site_url.'404.php');
        exit;
    }
    echo $pageController->minifyHTML($pageController->printHeader($category->getMeta($categoryName,$placeDetails['place_title']), true).$pageController->printNavigation().
            '<div class="main-content-container clearfix">
                <div class="header">
                    '.$pageController->printUserStepsText(1).'
                    <div class="back-button clearfix">
                        <a class="button orange small rounded" title="Back" href="'.Config::$site_url.'place/'.$placeName.'">
                            Back
                        </a>
                    </div>
                </div>
                <div class="content clearfix">
                    <div class="main left">
                        <h2>Get Matched to Top-Rated '.$categoryDetails[0]['category_title'].' for '.$placeDetails['place_title'].'</h2>
                        <ul>
                            '.$formatedSection.'
                        </ul>
                    </div>
                    <div class="sidebar right">
                        <div class="sidebar-container">
                            <div class="sidebar-header">
                                <h3>Roofing Library</h3>
                            </div>
                            <div class="sidebar-content">
                                '.$pageController->getArticles().'
                            </div>
                        </div>
                    </div>
                </div>
                '.$pageController->printFooterLinks().'
            </div>
            <div class="clear"></div>
        </div>'.$pageController->printFooter());