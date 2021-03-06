<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
include_once(Config::$site_path.'controller/pageController.php');
$pageController=new PageController(4);
$utils = $pageController->getUtils();
if (sizeof($_POST) > 0) {
    foreach ($_POST as $key => $value) {
        $key = $utils->checkValues($key);
        $value = $utils->checkValues($value);
        $questionPair.= $key.',';
        $answerPair.= $value.',';
    }
    $params['questionPair'] = substr($questionPair,0,-1);
    $params['answerPair'] = substr($answerPair,0,-1);
}
$params['placeName'] = $utils->checkValues($_GET['place']);
$params['categoryName'] = $utils->checkValues($_GET['category']);
if ($params['placeName'] && $params['categoryName']) {
    $section = $pageController->getSection($params['categoryName'], $params['placeName']);

    if (!$_GET['section']) {
        $getAllSections =  $section->getSections();
        $params['sectionName'] = $getAllSections[0]['section_name'];
        $background_id = $getAllSections[0]['background_id'];
    } else {
        $params['sectionName']= $pageController->getUtils()->checkValues($_GET['section']);
        $sectionDetails = $section->getSectionDetails($params['sectionName']);
        $background_id = $sectionDetails['background_id'];
    }
}
$params['contractorName'] = $utils->checkValues($_GET['contractor']);
if ($params['placeName'] && $params['categoryName'] && $params['sectionName']) {
    $contractor = $pageController->getContractor();
    $contractorDetails = $contractor->getContractors($params['placeName'], $params['categoryName'], $params['sectionName'], '', '');
    echo $pageController->minifyHTML($pageController->printHeader($contractor->getContractorsMeta($contractorDetails), false, 1, $background_id));
} else {
    echo $pageController->minifyHTML($pageController->printHeader());
}
    echo $pageController->minifyHTML($pageController->printHeaderMenu().
    '<div class="container-fluid">
        <div class="row main-container">
            <div class="col-md-3 col-xs-12 col-sm-3 sidebar">
                '.$pageController->printLogoContainer().'
            </div>
            <div class="regular-main main">
                <h1>Don\'t like forms !! call us 24/7   1(214) 303 97 71</h1>
                <div class="row">
                    <div class="col-md-8 col-xs-12 col-sm-8">
                        <form id="contactForm">
                            <ul style="display:none;" class="formStatus alert-warning nobullet"></ul>
                            <div class="email-header">
                                <span class="bold">To:&nbsp;TopRemodelers.com</span><br/>
                                <label for="emailId"><span class="bold">From:</span></label>
                                <input type="text" name="emailId" id="emailId" class="prepopulate form-control" rel="my email address" />
                            </div>
                            <div class="email-body">
                                <span class="bold">Your Message:</span>
                                <p>
                                    Hello. My name is 
                                    <input type="text" class="prepopulate form-control" size="15" rel="first name" name="firstName" id="firstName" /> 
                                    <input class="prepopulate form-control" size="15" rel="last name" type="text" name="lastName" id="lastName" /> 
                                    I would like to receive Free Estimates for my project.
                                    <br/>
                                    My address is 
                                    <input class="prepopulate form-control" size="30" rel="my street address" type="text" name="address" id="address" /> 
                                    in 
                                    <input class="prepopulate form-control" rel="my city" type="text" name="city" id="city" />
                                    <input class="prepopulate form-control" type="text" rel="zip" name="zip" id="zip" />.<br/>
                                    I would like to be contacted as soon as possible to receive my free estimates.<br/>
                                    Please contact me
                                    <input type="text" size="4" name="phone1" id="phone1" class="form-control"/> - <input type="text" size="4" name="phone2" id="phone2" class="form-control"/> - <input size="6" type="text" name="phone3" id="phone3" class="form-control"/>
                                    in the
                                    <select id="contactTime" name="contactTime" class="form-control">
                                        <option value="morning">morning</option>
                                        <option value="afternoon">afternoon</option>
                                        <option value="evening">evening</option>
                                    </select>.
                                </p>
                                <p>
                                    <span class="bold">Give more details about your project:</span>
                                    <textarea rows="3" class="prepopulate form-control" id="message" rel="Please use this box to provide contractors with additional details about your project."></textarea>
                                </p>
                                <p>
                                    <span class="bold">Thank You</span>
                                </p>
                                <p class="subscribe">
                                    <label>
                                        <input type="checkbox" name="subscribe" id="subscribe"/> Yes, I\'m interested in receiving remodeling news and special offers from Topremodelers.com
                                    </label>
                                </p>
                                <p>');
                                    foreach ($params as $key=>$value) {
                                        if ($value) {
                                            echo $pageController->minifyHTML('<input id="'.$key.'" type="hidden" value="'.$value.'" />');
                                        }
                                    }
                                echo $pageController->minifyHTML('
                                    <input class="submit btn btn-success" type="submit" value="Submit" />
                                </p>
                            </div>
                        </form>
                    </div>');
                    if (!$pageController->isMobile()) {
                    echo $pageController->minifyHTML('
                    <div class="col-md-4 hidden-xs col-sm-4">
                        <div class="sb-container">
                            <div class="sb-header">
                                <h3>No Fees. No Obligations.</h3>
                            </div>
                            <div class="sb-content">
                                <h5>Why Choose The Top Remodelers.com</h5>
                                <div class="art-person"></div>
                            </div>
                        </div>
                    </div>');
                    }
                    echo $pageController->minifyHTML('
                </div>
            </div>
        </div>
        <!-- FOOTER -->
    '.$pageController->printFooterLinks().'
    </div>'.$pageController->printFooter()); ?>