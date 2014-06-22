<?php
    header('HTTP/1.0 404 Not Found');
    include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
    include_once(Config::$site_path.'controller/pageController.php');
    $pageController=new PageController(5);
    echo $pageController->printHeader(); 
?>
            <?php echo $pageController->printNavigation(); ?>
            <div class="main-content-container clearfix">
                <div class="content clearfix">
                    <div class="main">
                        <h2>Page Not Found</h2>
                        <div class="sub clearfix">
                            <h3>We're sorry, but the page you requested was not found.</h3>
                            <div class="content-container">
                                <p>Please return to the <a href="<?php echo Config::$site_url?>index.php" title="The Top Remodelers">The Top Remodelers</a> home page or reference the navigation choices above.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo $pageController->printFooterLinks(); ?>
            </div>
            <div class="clear"></div>
        </div>
<?php echo $pageController->printFooter(); ?>