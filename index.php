<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
    include_once(Config::$site_path.'controller/pageController.php');
    $pageController=new PageController(0);
    echo $pageController->minifyHTML($pageController->printHeader().$pageController->printHeaderMenu().
        '<div class="container-fluid">
            <div class="row main-container">
                <div class="col-md-3 col-xs-12 col-sm-3 sidebar">
                    '.$pageController->printLogoContainer().'
                    <ul class="nav nav-sidebar">
                        '.$pageController->getFormatedCategories(1).'
                    </ul>
                    <div class="sidebar-container certifed-container hidden-xs">
                        <img src="images/home/stamp_final.png" alt=""/>
                    </div>
                    <div class="sidebar-container service-container hidden-xs">
                        <img src="images/home/service.jpg" alt=""/>
                        <h4 class="gold">Need immediate service</h4>
                        <h5>CALL US : 1(214)303 9771</h5>
                    </div>
                </div>
                <div class="sidebar sub-sidebar hidden-xs">
                    <ul class="nav nav-sidebar">
                        '.$pageController->getFormatedCategories(2).'
                    </ul>
                </div>
                <div class="col-md-9 col-xs-12 col-sm-9 main bottom">
                    <h1>Matching you with our <span class="blue">Prescreened contractors</span></h1>
                    <div class="hidden-xs">
                        '.$pageController->printUserStepsText().'
                    </div>
                    <div class="process-container">
                        <div class="col-md-7 col-xs-12 col-sm-7 process-sub-container">
                            <h2>What <span class="blue">Client</span> Says</h2>
                            '.$pageController->printReviewContainer().'
                        </div>
                        <div class="col-md-5 hidden-xs col-sm-5">
                            <i class="person"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- FOOTER -->
            '.$pageController->printFooterLinks().'
        </div>'.$pageController->printFooter());