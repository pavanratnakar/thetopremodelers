<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
    include_once(Config::$site_path.'controller/pageController.php');
    $pageController=new PageController(9);
    $place = $pageController->getPlace();
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
                    <h2>Matching you with our prescreened Contractors by Place</h2>
                    <div class="sub">
                        <h3>Find Contractors for cities within Dallas County</h3>
                        <div class="content-container">
                            <div class="place-container">
                                <?php
                                $places = $place->getPlaces();
                                $placesCount = ceil(sizeof($places)/4);
                                $placesArray[0] = array_slice($places, 0,$placesCount);
                                $placesArray[1] = array_slice($places, $placesCount,$placesCount);
                                $placesArray[2] = array_slice($places, $placesCount*2,$placesCount);
                                $placesArray[3] = array_slice($places, $placesCount*3,$placesCount);
                                foreach ($placesArray as $placesArrayKey => $placesArrayValue) {
                                    echo '<ul class="col-xs-3 col-sm-3 col-md-3 col-lg-3">';
                                    foreach ($placesArrayValue as $key => $value) {
                                        echo '<li><a href="'.Config::$site_url.'place/'.$value['place_name'].'" title="'.$value['place_title'].'">'.$value['place_title'].'</a></li>';
                                    }
                                    echo '</ul>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- FOOTER -->
            <?php echo $pageController->printHomeFooterLinks(); ?>
        </div>
        <?php echo $pageController->printHomeFooter(); ?>