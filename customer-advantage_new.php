<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
    include_once(Config::$site_path.'controller/pageController.php');
    $pageController=new PageController(1);
    echo $pageController->printHomeHeader();
?>
       <div class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-collapse collapse">
                    <div class="nav-contact">
                        <h4 class="gold">Need immediate service</h4>
                        <h5>CALL US : 1(214)303 9771</h5>
                    </div>
                    <?php echo $pageController->printHomeNavigation(); ?>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row main-container">
                <div class="col-md-3 col-xs-3 col-sm-3 sidebar">
                    <div class="nav-logo-container">
                        <div class="logo-container">
                            <a href="http://www.thetopremodelers.com">
                                <img src="images/global/logo.png" alt="The Top Remodelers"/>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="container full-main">
                    <h2>Do you know how to find the most reliable and competent home improvement? We do.</h2>
                    <div class="sub">
                        <h3>Benefit to the homeowner</h3>
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
            <?php echo $pageController->printHomeFooterLinks(); ?>
        </div>
        <?php echo $pageController->printHomeFooter(); ?>