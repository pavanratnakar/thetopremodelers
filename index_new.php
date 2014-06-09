<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
    include_once(Config::$site_path.'controller/pageController.php');
    $pageController=new PageController(0);
    echo $pageController->printHomeHeader();
?>
       <div class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <div class="navbar-collapse collapse">
                    <?php echo $pageController->printHomeNavigation(); ?>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row main-container">
                <div class="col-md-3 sidebar">
                    <div class="nav-logo-container">
                        <div class="logo-container">
                            <a href="http://www.thetopremodelers.com">
                                <img src="images/global/logo.png" alt="The Top Remodelers"/>
                            </a>
                        </div>
                    </div>
                    <ul class="nav nav-sidebar">
                        <?php echo $pageController->getHomeFormatedCategories(null); ?>
                    </ul>
                    <div class="sidebar-container certifed-container">
                        <img src="images/home/stamp_final.png" alt=""/>
                    </div>
                    <div class="sidebar-container service-container">
                        <img src="images/home/service.jpg" alt=""/>
                        <h4 class="gold">Need immediate service</h4>
                        <h5>CALL US : 1(214)303 9771</h5>
                    </div>
                </div>
                <div class="col-md-9 main">
                    <h2>Matching you with our <span class="blue">Prescreened contractors</span></h2>
                    <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh.</p>
                    <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh.</p>
                    <p>Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit.</p>
                    <div class="match-options-container">
                        <div class="row">
                            <div class="col-md-4 first">
                                <div class="match-option-container">
                                    <span class="label number">1</span>
                                    <span class="label">Select a Category</span>
                                </div>
                            </div>
                            <div class="col-md-4 second">
                                <div class="match-option-container">
                                    <span class="label number">2</span>
                                    <span class="label">Describe your need</span>
                                </div>
                            </div>
                            <div class="col-md-4 third">
                                <div class="match-option-container">
                                    <span class="label number">3</span>
                                    <span class="label">Get Matched to Pros</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="process-container">
                        <div class="col-md-7 process-sub-container">
                            <h3>What <span class="blue">Client</span> Says</h3>
                            <?php echo $pageController->printHomeReviewContainer(); ?>
                        </div>
                        <div class="col-md-5">
                            <i class="person"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- FOOTER -->
            <?php echo $pageController->printHomeFooterLinks(); ?>
        </div>
        <?php echo $pageController->printHomeFooter(); ?>