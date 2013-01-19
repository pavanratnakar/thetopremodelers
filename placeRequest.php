<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
include_once(Config::$site_path.'controller/pageController.php');
$pageController=new PageController(4);
echo $pageController->printHeader(); 
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
$params['sectionName'] = $utils->checkValues($_GET['section']);
$params['contractorName'] = $utils->checkValues($_GET['contractor']);
?>
<?php echo $pageController->printNavigation(); ?>
<div class="main-content-container round clearfix">
    <div class="art-person"></div>
    <div class="header"><h3>I'am Interested! Please contact Me with Free Estimates!</h3></div>
    <div class="content">
     <div class="mainBodyContent">
        <div id="emailLeftContainer" class="left">
            <div class="roundContainer">
                <form id="contactForm">
                    <ul style="display:none;" class="formStatus"></ul>
                    <div id="emailHeader">
                        <span class="strong">To:&nbsp;TheTopRemodelers.com</span><br/>
                        <label for="emailId"><span class="strong">From:</span></label>
                        <input type="text" name="emailId" id="emailId" class="prepopulate" rel="my email address" />
                    </div>
                    <div id="emailBody">
                        <span class="strong">Your Message:</span>
                        <p class="top">
                            Hello. My name is 
                            <input type="text" class="prepopulate" size="15" rel="first name" name="firstName" id="firstName" /> 
                            <input class="prepopulate" size="15" rel="last name" type="text" name="lastName" id="lastName" /> 
                            I would like to receive Free Estimates for my project.
                            <br/>
                            My address is 
                            <input class="prepopulate" size="30" rel="my street address" type="text" name="address" id="address" /> 
                            in 
                            <input class="prepopulate" rel="my city" type="text" name="city" id="city" />
                            <input class="prepopulate" type="text" rel="zip" name="zip" id="zip" />.<br/>
                            I would like to be contacted as soon as possible to receive my free estimates.<br/>
                            Please contact me
                            <input type="text" size="4" name="phone1" id="phone1" /> - <input type="text" size="4" name="phone2" id="phone2" /> - <input size="6" type="text" name="phone3" id="phone3" />
                            in the
                            <select id="contactTime" name="contactTime">
                                <option value="morning">morning</option>
                                <option value="afternoon">afternoon</option>
                                <option value="evening">evening</option>
                            </select>.
                        </p>
                        <p class="middle">
                            <span>Give more details about your project:</span>
                            <textarea rows="3" cols="94" class="prepopulate" id="message" rel="Please use this box to provide contractors with additional details about your project."></textarea>
                        </p>
                        <p>
                            <span class="strong">Thank You</span>
                        </p>
                        <p class="subscribe">
                            <input type="checkbox" name="subscribe" id="subscribe"/> Yes, I'm interested in receiving remodeling news and special offers from Thetopremodelers.com<br />
                        </p>
                        <p>
                            <?php
                            foreach ($params as $key=>$value) {
                                if ($value) {
                                    echo '<input id="'.$key.'" type="hidden" value="'.$value.'" />';
                                }
                            }
                            ?>
                            <input class="submit button gray small" type="submit" value="Submit" />  
                        </p>
                    </div>
                </form>
            </div>
        </div>
        <div id="emailRightContainer" class="left">
            <h3 class="brown">No Fees. No Obligations.</h3>
            <h4>Why Choose The Top Remodelers.com</h4>
        </div>
    </div>
</div>
<div class="clear"></div>
</div>
</div>
<?php echo $pageController->printFooterLinks(); ?>
<?php echo $pageController->printFooter(); ?>