<?php
    include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
    include_once(Config::$site_path.'controller/pageController.php');
    $pageController=new PageController(9);
    $place = $pageController->getPlace();
    echo $pageController->printHeader(); 
?>
            <?php echo $pageController->printNavigation(); ?>
            <div class="main-content-container clearfix">
                <div class="content clearfix">
                    <div class="main">
                        <h2>Matching you with our prescreened Contractors by Place</h2>
                        <div class="sub clearfix">
                            <h3>Find Contractors for cities within Dallas County</h3>
                            <div class="content-container">
                                <div class="place-container clearfix">
                                    <?php
                                    $places = $place->getPlaces();
                                    $placesCount = Round(sizeof($places)/4);
                                    $placesArray[0] = array_slice($places, 0,$placesCount);
                                    $placesArray[1] = array_slice($places, $placesCount,$placesCount*2);
                                    $placesArray[2] = array_slice($places, $placesCount*2,$placesCount*3);
                                    $placesArray[3] = array_slice($places, $placesCount*3,$placesCount*4);
                                    foreach ($placesArray as $placesArrayKey => $placesArrayValue) {
                                        echo '<ul class="left container">';
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
                <?php echo $pageController->printFooterLinks(); ?>
            </div>
            <div class="clear"></div>
        </div>
<?php echo $pageController->printFooter(); ?>