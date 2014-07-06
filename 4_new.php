<?php
    header('HTTP/1.0 404 Not Found');
    include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
    include_once(Config::$site_path.'controller/pageController.php');
    $pageController=new PageController(5);
    echo $pageController->printHomeHeader();
?>
        <?php echo $pageController->printHeaderMenu(); ?>
        <div class="container-fluid">
            <div class="row main-container">
                <div class="col-md-3 col-xs-3 col-sm-3 sidebar">
                    <?php echo $pageController->printLogoContainer(); ?>
                </div>
                <div class="container full-main">
                    <h1>Page Not Found</h1>
                    <div class="sub">
                        <h2>We're sorry, but the page you requested was not found.</h2>
                        <div class="content-container">
                            <p>Please return to the <a href="<?php echo Config::$site_url?>index.php" title="The Top Remodelers">The Top Remodelers</a> home page or reference the navigation choices above.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- FOOTER -->
            <?php echo $pageController->printHomeFooterLinks(); ?>
        </div>
        <?php echo $pageController->printHomeFooter(); ?>