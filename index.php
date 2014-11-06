<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
    include_once(Config::$site_path.'controller/pageController.php');
    $pageController=new PageController(0);
    echo $pageController->printHeader();
?>
        <?php echo $pageController->printHeaderMenu(); ?>
        <div class="container-fluid">
            <div class="row main-container">
                <div class="col-md-3 col-xs-12 col-sm-3 sidebar">
                    <?php echo $pageController->printLogoContainer(); ?>
                    <ul class="nav nav-sidebar hidden-xs">
                        <?php echo $pageController->getFormatedCategories(1); ?>
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
                        <?php echo $pageController->getFormatedCategories(2); ?>
                    </ul>
                </div>
                <div class="col-md-9 col-xs-12 col-sm-9 main bottom">
                    <h1>Matching you with our <span class="blue">Prescreened contractors</span></h1>
<!--                     <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh.</p>
                    <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh.</p>
                    <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</p> -->
                    <div class="hidden-xs">
                        <?php echo $pageController->printUserStepsText(); ?>
                    </div>
                    <div class="process-container">
                        <div class="col-md-7 col-xs-12 col-sm-7 process-sub-container">
                            <h2>What <span class="blue">Client</span> Says</h2>
                            <?php echo $pageController->printReviewContainer(); ?>
                        </div>
                        <div class="col-md-5 hidden-xs col-sm-5">
                            <i class="person"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- FOOTER -->
            <?php echo $pageController->printFooterLinks(); ?>
        </div>
        <?php echo $pageController->printFooter(); ?>