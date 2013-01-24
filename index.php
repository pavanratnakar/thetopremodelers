<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
    include_once(Config::$site_path.'controller/pageController.php');
    $pageController=new PageController(0);
    echo $pageController->printHeader();
?>
            <?php echo $pageController->printNavigation(); ?>
            <div class="art-person"></div>
            <div class="main-content-container round clearfix">
                <div class="header"><h2>Matching you with our prescreened Contractors</h2></div>
                <div class="content clearfix">
                    <?php echo $pageController->printUserStepsText(); ?>
                    <div class="left clearfix first">
                        <ul class="top">
                            <li>Additions &amp; Remodels</li>
                        </ul>
                        <ul class="list clearfix">
                            <?php echo $pageController->getFormatedCategories(1); ?>
                        </ul>
                    </div>
                    <div class="left clearfix">
                        <ul class="top">
                            <li>Handyman Services</li>
                        </ul>
                        <ul class="list clearfix">
                            <?php echo $pageController->getFormatedCategories(2); ?>
                        </ul>
                    </div>
                </div>
                <div id="satis-image"></div>
            </div>
            <div class="clear"></div>
            <?php echo $pageController->printReviewContainer(); ?>
            <div class="clear"></div>
        </div>
<?php echo $pageController->printFooterLinks(); ?>
<?php echo $pageController->printFooter(); ?>