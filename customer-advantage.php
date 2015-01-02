<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
    include_once(Config::$site_path.'controller/pageController.php');
    $pageController=new PageController(1);
    echo $pageController->minifyHTML($pageController->printHeader().$pageController->printHeaderMenu().
        '<div class="container-fluid">
            <div class="row main-container">
                <div class="col-md-3 col-xs-12 col-sm-3 sidebar">
                    '.$pageController->printLogoContainer().'
                </div>
                <div class="container full-main">
                    <h1>Do you know how to find the most reliable and competent home improvement? We do.</h1>
                    <div class="sub">
                        <h2>Benefit to the homeowner</h2>
                        <div class="content-container">
                            <ul>
                                <li>Our service is free just enter your request or Call us 1(214)303 9771</li>
                                <li>Why take a chance with a random contractor from the yellow pages, newspaper ads</li>
                                <li>Provides hassle free and easy access to contractors</li>
                                <li>Save you the time of locating quality contractors</li>
                                <li>Get 5 stars certified contractors</li>
                                <li>Ensure that all the contractors sign in have proper licensing, insurance, and bond</li>
                                <li>Back ground check thru our services</li>
                                <li>Ensure competitive biding</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- FOOTER -->
            '.$pageController->printFooterLinks().'
        </div>'.$pageController->printFooter());