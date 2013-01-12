<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/config.class.php');
include_once (Config::$site_path.'class/mailClass.php');
include_once (Config::$site_path.'global/Class/utils.class.php');
$utils = new Utils();
$mail=new MailClass();
$message = '';
$placeName = $utils->checkValues($_REQUEST['placeName']);
$categoryName = $utils->checkValues($_REQUEST['categoryName']);
$sectionName = $utils->checkValues($_REQUEST['sectionName']);
$contractorName = $utils->checkValues($_REQUEST['contractorName']);
$questionPair = $utils->checkValues($_REQUEST['questionPair']);
$answerPair = $utils->checkValues($_REQUEST['answerPair']);
if ($placeName) {
    include_once(Config::$site_path.'class/place.class.php');
    $place = new Place();
    $placeDetails = $place->getPlaceDetails($placeName);
    $message .= '<hr/><b>Place</b> : '.$placeDetails['place_title'].'<hr/>';
}
if ($categoryName) {
    include_once(Config::$site_path.'class/category.class.php');
    $category = new Category();
    $message .= '<hr/><b>Category</b> : '.$category->getCategoryValueByName($categoryName).'<hr/>';
}
if ($sectionName) {
    include_once(Config::$site_path.'class/section.class.php');
    $section = new Section($categoryName,$placeName);
    $message .= '<hr/><b>Section</b> : '.$section->getSectionTitleByName($sectionName).'<hr/>';
}
if ($contractorName) {
    include_once(Config::$site_path.'class/contractor.class.php');
    $contractor = new Contractor();
    $contractorDetails = $contractor->getContractor($contractorName);
    $message .= '<hr/><b>Contractor</b> : '.$contractorDetails['contractor_title'].'<hr/>';
}
$message .='   
<hr/><b>Customer Details</b><hr/>
<b>Email</b>            :   '.$utils->checkValues($_REQUEST['email']).'<br />
<b>Name</b>             :   '.$utils->checkValues($_REQUEST['name']).'<br />
<b>Address</b>          :   '.$utils->checkValues($_REQUEST['address']).'<br />
<b>City</b>             :   '.$utils->checkValues($_REQUEST['city']).'<br />
<b>Zip</b>              :   '.$utils->checkValues($_REQUEST['zip']).'<br />
<b>Phone</b>            :   '.$utils->checkValues($_REQUEST['phone']).'<br />
<b>Contact Time</b>     :   '.$utils->checkValues($_REQUEST['contactTime']).'<br />
<b>Message</b>          :   '.nl2br($utils->checkValues($_REQUEST['message'])).'<br />
<b>Subscribe</b>        :   '.$utils->checkValues($_REQUEST['subscribe']).'<br />';
if ($questionPair && $answerPair) {

    $questionPair = explode(',',$questionPair);
    $answerPair = explode(',',$answerPair);
    for($i=0;$i<sizeof($questionPair);$i++){
        $questionAnswerArray[$questionPair[$i]]=$answerPair[$i];
    }
    if ($questionAnswerArray) {
        include_once(Config::$site_path.'class/question.class.php');
        $question = new Question();
        $message .= '
        <br/><hr/><b>Questions Answered</b><hr/>
        '.$question->formatQuestionResponse($questionAnswerArray);
    }
}
$message .= '
<br/><hr/><b>Customer Details For Security</b><hr/>
<b>IP Address Used</b> :  '.$utils->ip_address_to_number($_SERVER['REMOTE_ADDR']).'<br /><br/>
';
$status=$mail->sendMailFunction(
    'Customer Interest Mail : Estimates Required',
    $message
    );
$returnArray= array(
    "status" => $status
    );
$response = $_POST["jsoncallback"] . "(" .json_encode($returnArray). ")";
echo $response;
unset($response);
?>