<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
    include_once(Config::$site_path.'controller/pageController.php');
    $pageController=new PageController(9);
    $place = $pageController->getPlace();
    echo $pageController->printHeader();
?>
        <?php echo $pageController->printHeaderMenu(); ?>
        <div class="container-fluid">
            <div class="row main-container">
                <div class="col-md-3 col-xs-3 col-sm-3 sidebar">
                    <?php echo $pageController->printLogoContainer(); ?>
                    <ul class="nav nav-sidebar">
                        <?php echo $pageController->getFormatedCategories(1); ?>
                    </ul>
                </div>
                <div class="sidebar sub-sidebar">
                    <ul class="nav nav-sidebar">
                        <?php echo $pageController->getFormatedCategories(2); ?>
                    </ul>
                </div>
                <div class="col-md-9 col-xs-9 col-sm-9 container main top">
                    <h1>Matching you with our prescreened Contractors by Place</h1>
                    <div class="sub">
                        <h2>Find Contractors for cities within Dallas County</h2>
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
            <?php echo $pageController->printFooterLinks(); ?>
        </div>
        <?php echo $pageController->printFooter(); ?>